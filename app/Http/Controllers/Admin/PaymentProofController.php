<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\ProofStatusChanged;
use App\Models\PaymentProof;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;

class PaymentProofController extends Controller
{
    public function index(): View
    {
        $pending = PaymentProof::with('order')
            ->where('status', 'pending')
            ->latest()
            ->get();

        $recent = PaymentProof::with('order')
            ->whereIn('status', ['validated', 'rejected'])
            ->latest('reviewed_at')
            ->take(20)
            ->get();

        return view('admin.payment-proofs.index', compact('pending', 'recent'));
    }

    public function validate(PaymentProof $proof): RedirectResponse
    {
        DB::transaction(function () use ($proof) {
            $proof->update([
                'status'      => 'validated',
                'reviewed_at' => now(),
            ]);

            $order = $proof->order;

            if ($order->isFullyPaid()) {
                $order->update([
                    'payment_status' => 'paid',
                    'status'         => 'confirmed',
                ]);

                $order->items()->with('product')->get()->each(
                    fn ($item) => $item->product?->decrement('stock_quantity', $item->quantity)
                );
            }
        });

        try {
            Mail::to($proof->order->customer_email)->send(new ProofStatusChanged($proof->load('order')));
        } catch (\Throwable) {}

        return back()->with('success', "Preuve #{$proof->id} validée.");
    }

    public function reject(Request $request, PaymentProof $proof): RedirectResponse
    {
        $request->validate([
            'admin_notes' => ['required', 'string', 'max:500'],
        ]);

        $proof->update([
            'status'      => 'rejected',
            'admin_notes' => $request->admin_notes,
            'reviewed_at' => now(),
        ]);

        try {
            Mail::to($proof->order->customer_email)->send(new ProofStatusChanged($proof->load('order')));
        } catch (\Throwable) {}

        return back()->with('success', "Preuve #{$proof->id} rejetée.");
    }
}
