<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PaymentSettingController extends Controller
{
    public function edit(): View
    {
        $settings = Setting::allKeyed();

        return view('admin.payment-settings.edit', compact('settings'));
    }

    public function update(Request $request): RedirectResponse
    {
        $data = $request->validate([
            // Coordonnées bancaires
            'payment_bank_name'          => ['nullable', 'string', 'max:120'],
            'payment_bank_holder'        => ['nullable', 'string', 'max:120'],
            'payment_bank_iban'          => ['nullable', 'string', 'max:40'],
            'payment_bank_bic'           => ['nullable', 'string', 'max:15'],
            'payment_allow_installments' => ['nullable'],
            // Contact
            'contact_whatsapp'           => ['nullable', 'string', 'max:30'],
            'contact_email'              => ['nullable', 'email', 'max:120'],
            // Identité société
            'company_logo'               => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'company_name'               => ['nullable', 'string', 'max:120'],
            'company_tagline'            => ['nullable', 'string', 'max:255'],
            'company_address'            => ['nullable', 'string', 'max:255'],
            'company_phone'              => ['nullable', 'string', 'max:30'],
            // Légal France
            'company_legal_form'         => ['nullable', 'string', 'max:80'],
            'company_capital'            => ['nullable', 'string', 'max:80'],
            'company_siret'              => ['nullable', 'string', 'max:20'],
            'company_rcs'                => ['nullable', 'string', 'max:120'],
            'company_ape'                => ['nullable', 'string', 'max:10'],
            'company_vat'                => ['nullable', 'string', 'max:20'],
            'company_footer_legal'       => ['nullable', 'string', 'max:1000'],
            // TVA
            'vat_regime'                 => ['nullable', 'in:standard,marge,exempt'],
            'vat_rate'                   => ['nullable', 'numeric', 'min:0', 'max:30'],
            // Documents
            'doc_currency'               => ['nullable', 'string', 'max:5'],
            'doc_quote_validity'         => ['nullable', 'integer', 'min:1', 'max:365'],
            'doc_guarantee_text'         => ['nullable', 'string', 'max:1000'],
            'doc_terms_text'             => ['nullable', 'string', 'max:1000'],
        ]);

        // Logo upload
        if ($request->hasFile('company_logo')) {
            $old = Setting::get('company_logo');
            if ($old && file_exists(public_path('uploads/logo/' . $old))) {
                unlink(public_path('uploads/logo/' . $old));
            }
            if (! is_dir(public_path('uploads/logo'))) {
                mkdir(public_path('uploads/logo'), 0755, true);
            }
            $file = $request->file('company_logo');
            $name = bin2hex(random_bytes(8)) . '.' . strtolower($file->getClientOriginalExtension());
            $file->move(public_path('uploads/logo'), $name);
            $data['company_logo'] = $name;
        }

        // Remove logo from data if no file uploaded (don't overwrite existing)
        if (! $request->hasFile('company_logo')) {
            unset($data['company_logo']);
        }

        $data['payment_allow_installments'] = $request->boolean('payment_allow_installments') ? '1' : '0';

        Setting::setMany($data);

        return back()->with('success', 'Paramètres enregistrés avec succès.');
    }
}
