<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class WarrantyClaimRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'full_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'whatsapp' => ['required', 'string', 'max:30'],
            'invoice_number' => ['required', 'string', 'max:255'],
            'product_name' => ['required', 'string', 'max:255'],
            'complaint' => ['required', 'string'],
            'attachment' => ['nullable', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:2048'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'full_name' => 'nama lengkap',
            'email' => 'email',
            'whatsapp' => 'nomor WhatsApp',
            'invoice_number' => 'nomor invoice',
            'product_name' => 'nama produk',
            'complaint' => 'keluhan',
            'attachment' => 'lampiran',
        ];
    }
}
