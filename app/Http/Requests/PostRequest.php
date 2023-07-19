<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PostRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'title' => 'required|max:255',
            'content' => 'required',
            'file' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'category' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'title.required' => 'The title field is required.',
            'title.max' => 'The title field must not exceed 255 characters.',
            'content.required' => 'The content field is required.',
            'file.required' => 'The image field is required.',
            'file.image' => 'The file must be an image.',
            'file.mimes' => 'The file must be a JPEG, PNG, JPG, GIF, or SVG file.',
            'file.max' => 'The file must not exceed 2048 kilobytes.',
            'category.required' => 'The category field is required.',
        ];
    }
}
