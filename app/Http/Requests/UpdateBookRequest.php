<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateBookRequest extends FormRequest
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
            'title' => 'required|string|max:255',
            'publisher' => 'nullable|string|max:255',
            'publish_year' => 'nullable|integer',
            'category' => 'required|string',
            'related_course' => 'nullable|string',
            'cover' => 'nullable|image|mimes:jpeg,png,jpg|max:2048', // Cover baru opsional
            'file' => 'nullable|mimes:pdf|max:20000',               // PDF baru opsional
        ];
    }
}
