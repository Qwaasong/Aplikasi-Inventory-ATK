<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductStoreRequest extends FormRequest
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
            'nama' => 'required|string|max:255',
            'satuan_pack' => 'required|string',
            'kategori' => 'required|string',
            'isi_per_pack' => 'nullable|integer|min:1',
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'nama.required' => 'Nama produk wajib diisi.',
            'nama.max' => 'Nama produk maksimal 255 karakter.',
            'satuan_pack.required' => 'Satuan pack wajib dipilih.',
            'kategori.required' => 'Kategori wajib dipilih.',
            'isi_per_pack.integer' => 'Isi per pack harus berupa angka.',
            'isi_per_pack.min' => 'Isi per pack minimal 1.',
        ];
    }
}
