<?php

namespace App\Http\Requests\Blog;

use Illuminate\Foundation\Http\FormRequest;

class StorePostRequest extends FormRequest
{
   
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
{
    $rules = [
        'model' => 'required|in:post,faq', // Ensure the user selects either post or faq
        'status' => 'required|in:draft,published,archived',
    ];

    if ($this->input('model') === 'post') {
        // Blog post validation rules
        $rules['title'] = 'required|string|max:255';
        $rules['excerpt'] = 'nullable|string';
        $rules['content'] = 'required|string';
        $rules['image'] = 'nullable|image|mimes:jpg,jpeg,png,gif,svg|max:2048';
        $rules['category_id'] = 'required|array'; // Categories are required
        $rules['category_id.*'] = 'exists:categories,id'; // Validate each category exists
        $rules['is_featured'] = 'nullable|boolean';
    }

    if ($this->input('model') === 'faq') {
        // FAQ post validation rules
        $rules['excerpt'] = 'required|string|max:255'; // Excerpt field becomes 'question'
        $rules['content'] = 'required|string'; // Content field becomes 'answer'
        $rules['category_id'] = 'required|array'; // Categories are required for FAQs too
        $rules['category_id.*'] = 'exists:categories,id'; // Validate each category exists
        // The image and is_featured fields are still prohibited for FAQ
        $rules['image'] = ['prohibited'];
        $rules['is_featured'] = ['prohibited'];
    }

    return $rules;
}

}
