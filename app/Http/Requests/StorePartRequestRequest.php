<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePartRequestRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'requested_part_name' => ['required', 'string', 'max:190'],
            'reference'           => ['nullable', 'string', 'max:120'],
            'vehicle_make'        => ['nullable', 'string', 'max:120'],
            'vehicle_model'       => ['nullable', 'string', 'max:120'],
            'vehicle_year'        => ['nullable', 'integer', 'min:1950', 'max:' . (date('Y') + 1)],
            'description'         => ['nullable', 'string', 'max:2000'],
            'contact_name'        => ['required', 'string', 'max:120'],
            'contact_email'       => ['nullable', 'email:rfc', 'max:190'],
            'contact_phone'       => ['nullable', 'string', 'max:40'],
        ];
    }

    public function withValidator($validator): void
    {
        $validator->after(function ($v) {
            if (! $this->filled('contact_email') && ! $this->filled('contact_phone')) {
                $v->errors()->add('contact_phone', 'Un email ou un numéro de téléphone est obligatoire.');
            }
        });
    }

    public function messages(): array
    {
        return [
            'requested_part_name.required' => 'Le nom de la pièce est obligatoire.',
            'contact_name.required'        => 'Votre nom est obligatoire.',
            'contact_email.email'          => 'L\'adresse email n\'est pas valide.',
            'vehicle_year.min'             => 'L\'année doit être supérieure à 1950.',
            'vehicle_year.max'             => 'L\'année ne peut pas être dans le futur.',
        ];
    }
}
