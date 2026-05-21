<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CheckoutRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'customer_name'    => ['required', 'string', 'max:255'],
            'customer_email'   => ['required', 'email:rfc', 'max:255'],
            'customer_phone'   => ['nullable', 'string', 'max:30'],
            'delivery_address' => ['nullable', 'string', 'max:500'],
            'notes'            => ['nullable', 'string', 'max:1000'],
        ];
    }

    public function messages(): array
    {
        return [
            'customer_name.required'  => 'Le nom est obligatoire.',
            'customer_email.required' => 'L\'adresse email est obligatoire.',
            'customer_email.email'    => 'L\'adresse email n\'est pas valide.',
        ];
    }
}
