<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateTransactionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'branch_id' => [
                Rule::requiredIf(fn () => $this->user()?->isSuperAdmin() === true),
                'uuid',
                'exists:branches,id',
            ],
            'customer_id' => ['nullable', 'uuid', 'exists:customers,id'],
            'type' => ['required', 'in:buy,sell'],
            'payment_method' => ['required', 'in:cash,transfer'],
            'occurred_at' => ['required', 'date'],
            'additional_fee' => ['nullable', 'numeric', 'min:0'],
            'notes' => ['nullable', 'string'],
            'items' => ['required', 'array', 'min:1'],
            'items.*.gold_level_id' => ['required', 'uuid', 'exists:gold_levels,id'],
            'items.*.product_type' => ['required', 'in:bullion,jewelry'],
            'items.*.weight' => ['required', 'numeric', 'min:0.001'],
            'items.*.price_per_gram' => ['required', 'numeric', 'min:0'],
        ];
    }

    public function messages(): array
    {
        return [
            'type.required' => 'Tipe transaksi wajib diisi.',
            'payment_method.required' => 'Metode pembayaran wajib diisi.',
            'items.required' => 'Item transaksi wajib diisi.',
            'items.min' => 'Minimal harus ada 1 item transaksi.',
        ];
    }
}
