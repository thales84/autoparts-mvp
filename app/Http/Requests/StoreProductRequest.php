<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->role === 'admin';
    }

    public function rules(): array
    {
        return [
            'name'          => ['required', 'string', 'max:255'],
            'sku'           => ['required', 'string', 'max:100', 'unique:products,sku'],
            'oem_reference' => ['nullable', 'string', 'max:100'],
            'category_id'   => ['nullable', 'integer', 'exists:categories,id'],
            'description'   => ['nullable', 'string', 'max:2000'],
            'condition'     => ['required', 'in:used_good,used_fair,refurbished,for_parts'],
            'price'         => ['required', 'numeric', 'min:0'],
            'stock_quantity'=> ['required', 'integer', 'min:0'],
            'status'        => ['required', 'in:active,inactive'],
            'location'      => ['nullable', 'string', 'max:100'],
            'image'         => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ];
    }
}
