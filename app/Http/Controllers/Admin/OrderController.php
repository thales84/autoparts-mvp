<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Setting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class OrderController extends Controller
{
    public function index(): View
    {
        $orders = Order::with('user')
            ->latest()
            ->paginate(20);

        return view('admin.orders.index', compact('orders'));
    }

    public function show(Order $order): View
    {
        $order->load('items.product', 'user', 'payments', 'proofs');

        return view('admin.orders.show', compact('order'));
    }

    public function updateStatus(Request $request, Order $order): RedirectResponse
    {
        $request->validate([
            'status'         => ['required', 'in:pending,confirmed,processing,shipped,delivered,cancelled'],
            'payment_status' => ['required', 'in:unpaid,pending,paid,failed,refunded'],
        ]);

        $order->update([
            'status'         => $request->status,
            'payment_status' => $request->payment_status,
        ]);

        return back()->with('success', 'Statut de la commande mis à jour.');
    }

    public function pdfDevis(Order $order)
    {
        $order->load('items');
        $settings   = Setting::allKeyed();
        $logoBase64 = $this->logoBase64($settings);
        $docNumber  = str_replace('CMD-', 'DVS-', $order->order_number);

        $pdf = app('dompdf.wrapper');
        $pdf->loadView('pdf.devis', compact('order', 'settings', 'logoBase64', 'docNumber'));
        $pdf->setPaper('A4', 'portrait');

        return $pdf->download("{$docNumber}.pdf");
    }

    public function pdfBonCommande(Order $order)
    {
        $order->load('items');
        $settings   = Setting::allKeyed();
        $logoBase64 = $this->logoBase64($settings);
        $docNumber  = str_replace('CMD-', 'BC-', $order->order_number);

        $pdf = app('dompdf.wrapper');
        $pdf->loadView('pdf.bon_commande', compact('order', 'settings', 'logoBase64', 'docNumber'));
        $pdf->setPaper('A4', 'portrait');

        return $pdf->download("{$docNumber}.pdf");
    }

    public function pdfFacture(Order $order)
    {
        $order->load('items', 'proofs');
        $settings   = Setting::allKeyed();
        $logoBase64 = $this->logoBase64($settings);
        $docNumber  = str_replace('CMD-', 'FAC-', $order->order_number);

        $pdf = app('dompdf.wrapper');
        $pdf->loadView('pdf.facture', compact('order', 'settings', 'logoBase64', 'docNumber'));
        $pdf->setPaper('A4', 'portrait');

        return $pdf->download("{$docNumber}.pdf");
    }

    public function pdfRecu(Order $order)
    {
        $order->load('items', 'proofs');
        $settings   = Setting::allKeyed();
        $logoBase64 = $this->logoBase64($settings);

        if ($order->isFullyPaid()) {
            $docNumber = str_replace('CMD-', 'FAC-', $order->order_number);
            $pdf = app('dompdf.wrapper');
            $pdf->loadView('pdf.facture', compact('order', 'settings', 'logoBase64', 'docNumber'));
            $pdf->setPaper('A4', 'portrait');
            return $pdf->download("{$docNumber}.pdf");
        }

        $docNumber       = str_replace('CMD-', 'VRS-', $order->order_number);
        $amountPaid      = $order->amountPaid();
        $amountRemaining = $order->amountRemaining();

        $pdf = app('dompdf.wrapper');
        $pdf->loadView('pdf.recu_partiel', compact('order', 'settings', 'logoBase64', 'docNumber', 'amountPaid', 'amountRemaining'));
        $pdf->setPaper('A4', 'portrait');

        return $pdf->download("{$docNumber}.pdf");
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
