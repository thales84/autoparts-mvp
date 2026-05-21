<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Mail\ProofReceived;
use App\Models\Order;
use App\Models\PaymentProof;
use App\Models\Setting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class AccountController extends Controller
{
    public function profile(Request $request): View
    {
        return view('public.account.profile', ['user' => $request->user()]);
    }

    public function updateProfile(Request $request): RedirectResponse
    {
        $user = $request->user();

        $data = $request->validate([
            'name'                  => ['required', 'string', 'max:100'],
            'email'                 => ['required', 'email:rfc', 'max:150', Rule::unique('users')->ignore($user->id)],
            'phone'                 => ['nullable', 'string', 'max:20'],
            'current_password'      => ['required', 'string'],
            'password'              => ['nullable', 'string', 'min:8', 'confirmed'],
        ]);

        if (! Hash::check($data['current_password'], $user->password)) {
            return back()->withErrors(['current_password' => 'Mot de passe actuel incorrect.'])->withInput();
        }

        $user->name  = $data['name'];
        $user->email = $data['email'];
        $user->phone = $data['phone'] ?? null;

        if (! empty($data['password'])) {
            $user->password = Hash::make($data['password']);
        }

        $user->save();

        return back()->with('success', 'Profil mis à jour avec succès.');
    }

    public function orders(Request $request): View
    {
        $orders = $request->user()
            ->orders()
            ->latest()
            ->paginate(15);

        return view('public.account.orders', compact('orders'));
    }

    public function orderShow(Request $request, Order $order): View
    {
        $this->authorizeOrder($order);

        $order->load('items', 'proofs');
        $settings = Setting::allKeyed();

        return view('public.account.order-show', compact('order', 'settings'));
    }

    public function submitProof(Request $request, Order $order): RedirectResponse
    {
        $this->authorizeOrder($order);

        $request->validate([
            'amount' => ['required', 'numeric', 'min:1', 'max:' . $order->total],
            'file'   => ['required', 'file', 'mimes:jpg,jpeg,png,pdf,webp', 'max:5120'],
        ]);

        $file = $request->file('file');
        $name = bin2hex(random_bytes(8)) . '.' . strtolower($file->getClientOriginalExtension());
        $file->move(public_path('uploads/proofs'), $name);

        $proof = PaymentProof::create([
            'order_id'  => $order->id,
            'amount'    => $request->amount,
            'file_path' => 'uploads/proofs/' . $name,
        ]);

        try {
            $adminEmail = Setting::get('contact_email', config('mail.from.address'));
            Mail::to($adminEmail)->send(new ProofReceived($proof->load('order')));
        } catch (\Throwable) {}

        return back()->with('success', 'Preuve de paiement soumise. L\'admin la validera dans les meilleurs délais.');
    }

    public function downloadDevis(Request $request, Order $order)
    {
        $this->authorizeOrder($order);

        $order->load('items');
        $settings   = Setting::allKeyed();
        $logoBase64 = $this->logoBase64($settings);
        $docNumber  = str_replace('CMD-', 'DVS-', $order->order_number);

        $pdf = app('dompdf.wrapper');
        $pdf->loadView('pdf.devis', compact('order', 'settings', 'logoBase64', 'docNumber'));
        $pdf->setPaper('A4', 'portrait');

        return $pdf->download("{$docNumber}.pdf");
    }

    public function downloadBonCommande(Request $request, Order $order)
    {
        $this->authorizeOrder($order);

        if ($order->payment_status !== 'paid') {
            return back()->with('error', 'Le bon de commande est disponible uniquement après validation du paiement.');
        }

        $order->load('items');
        $settings   = Setting::allKeyed();
        $logoBase64 = $this->logoBase64($settings);
        $docNumber  = str_replace('CMD-', 'BC-', $order->order_number);

        $pdf = app('dompdf.wrapper');
        $pdf->loadView('pdf.bon_commande', compact('order', 'settings', 'logoBase64', 'docNumber'));
        $pdf->setPaper('A4', 'portrait');

        return $pdf->download("{$docNumber}.pdf");
    }

    public function downloadFacture(Request $request, Order $order)
    {
        $this->authorizeOrder($order);

        if ($order->status !== 'delivered') {
            return back()->with('error', 'La facture est disponible uniquement après livraison de la commande.');
        }

        $order->load('items', 'proofs');
        $settings   = Setting::allKeyed();
        $logoBase64 = $this->logoBase64($settings);
        $docNumber  = str_replace('CMD-', 'FAC-', $order->order_number);

        $pdf = app('dompdf.wrapper');
        $pdf->loadView('pdf.facture', compact('order', 'settings', 'logoBase64', 'docNumber'));
        $pdf->setPaper('A4', 'portrait');

        return $pdf->download("{$docNumber}.pdf");
    }

    public function downloadRecu(Request $request, Order $order)
    {
        $this->authorizeOrder($order);

        $order->load('items', 'proofs');
        $settings = Setting::allKeyed();

        $validatedProofs = $order->proofs->where('status', 'validated');

        if ($validatedProofs->isEmpty()) {
            return back()->with('error', 'Aucun versement validé pour le moment. Soumettez votre preuve de paiement.');
        }

        $logoBase64 = $this->logoBase64($settings);

        // Paiement complet → facture définitive
        if ($order->isFullyPaid()) {
            $docNumber = str_replace('CMD-', 'FAC-', $order->order_number);
            $pdf = app('dompdf.wrapper');
            $pdf->loadView('pdf.facture', compact('order', 'settings', 'logoBase64', 'docNumber'));
            $pdf->setPaper('A4', 'portrait');
            return $pdf->download("{$docNumber}.pdf");
        }

        // Paiement partiel + tranches autorisées → reçu provisoire
        if (($settings['payment_allow_installments'] ?? '0') !== '1') {
            return back()->with('error', 'La facture est disponible uniquement après validation du paiement complet.');
        }

        $docNumber       = str_replace('CMD-', 'VRS-', $order->order_number);
        $amountPaid      = $order->amountPaid();
        $amountRemaining = $order->amountRemaining();

        $pdf = app('dompdf.wrapper');
        $pdf->loadView('pdf.recu_partiel', compact('order', 'settings', 'logoBase64', 'docNumber', 'amountPaid', 'amountRemaining'));
        $pdf->setPaper('A4', 'portrait');

        return $pdf->download("{$docNumber}.pdf");
    }

    private function authorizeOrder(Order $order): void
    {
        if ($order->user_id !== auth()->id()) {
            abort(403);
        }
    }

    private function logoBase64(array $settings): ?string
    {
        if (empty($settings['company_logo'])) {
            return null;
        }
        $path = public_path('uploads/logo/' . $settings['company_logo']);
        if (! file_exists($path)) {
            return null;
        }

        return 'data:' . mime_content_type($path) . ';base64,' . base64_encode(file_get_contents($path));
    }
}
