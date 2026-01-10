<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductUpdateRequest extends FormRequest
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
            'kategori' => 'required|string|max:1000',
            'isi_per_pack' => 'required|integer|min:1',
            'satuan_pack' => 'required|string|max:50',
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
            'kategori.required' => 'Kategori wajib dipilih.',
            'kategori.max' => 'Kategori maksimal 1000 karakter.',
            'isi_per_pack.required' => 'Isi per pack wajib diisi.',
            'isi_per_pack.integer' => 'Isi per pack harus berupa angka.',
            'isi_per_pack.min' => 'Isi per pack minimal 1.',
            'satuan_pack.required' => 'Satuan pack wajib dipilih.',
            'satuan_pack.max' => 'Satuan pack maksimal 50 karakter.',
        ];
    }
}
