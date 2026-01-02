<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreSyncTransactionRequest extends FormRequest
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
            'transactions' => ['required', 'array', 'min:1'],
            'transactions.*.id' => ['required', 'uuid'],
            'transactions.*.customer_id' => ['nullable', 'uuid'],
            'transactions.*.type' => ['required', 'in:buy,sell'],
            'transactions.*.payment_method' => ['required', 'in:cash,transfer'],
            'transactions.*.occurred_at' => ['required', 'date'],
            'transactions.*.additional_fee' => ['nullable', 'numeric', 'min:0'],
            'transactions.*.notes' => ['nullable', 'string'],
            'transactions.*.items' => ['required', 'array', 'min:1'],
            'transactions.*.items.*.id' => ['nullable', 'uuid'],
            'transactions.*.items.*.gold_level_id' => ['required', 'uuid', 'exists:gold_levels,id'],
            'transactions.*.items.*.product_type' => ['required', 'in:bullion,jewelry'],
            'transactions.*.items.*.weight' => ['required', 'numeric', 'min:0.001'],
            'transactions.*.items.*.price_per_gram' => ['required', 'numeric', 'min:0'],
        ];
    }

    public function messages(): array
    {
        return [
            'transactions.required' => 'Data transaksi wajib diisi.',
            'transactions.min' => 'Minimal ada satu transaksi.',
            'transactions.*.id.required' => 'UUID transaksi wajib diisi.',
            'transactions.*.items.required' => 'Item transaksi wajib diisi.',
            'transactions.*.items.min' => 'Minimal ada satu item transaksi.',
        ];
    }
}
