<?php

namespace App\Http\Requests\V1\Api;

use Illuminate\Foundation\Http\FormRequest;

class FileUpdateRequest extends FormRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'image' => [
                'required',
                'file',
                'mimes:jpeg,png,jpg,gif', // Allow only specific image formats
                'max:2048', // Maximum file size in kilobytes (e.g., 2048 KB = 2 MB)
            ],
        ];
    }
}
