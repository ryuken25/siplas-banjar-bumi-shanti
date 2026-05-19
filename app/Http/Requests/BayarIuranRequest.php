<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BayarIuranRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->isWarga() ?? false;
    }

    public function rules(): array
    {
        $isTransfer = $this->input('metode_bayar') === 'transfer';

        return [
            'metode_bayar' => ['required', 'in:transfer,tunai'],
            'bukti_bayar' => [$isTransfer ? 'required' : 'nullable', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],
            'catatan' => ['nullable', 'string', 'max:500'],
        ];
    }

    public function messages(): array
    {
        return [
            'metode_bayar.required' => 'Metode pembayaran wajib dipilih.',
            'bukti_bayar.required' => 'Bukti transfer wajib diunggah.',
            'bukti_bayar.max' => 'Ukuran bukti maksimal 2 MB.',
            'bukti_bayar.image' => 'Bukti harus berupa gambar.',
        ];
    }
}
