<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreBookRequest extends FormRequest
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
            'judul_buku' => 'required|string|max:255',
            'penerbit' => 'required|string|max:255',
            'tahun_terbit' => 'required|numeric',
            'kategori' => 'required|string',
            'file_pdf' => 'required|mimes:pdf|max:30720', // Max 30MB
            'cover_image' => 'nullable|image|max:2048', // Max 2MB
            'mata_kuliah_terkait' => 'nullable|string',
            'is_buku_kuliah' => 'nullable',
        ];
    }
}
