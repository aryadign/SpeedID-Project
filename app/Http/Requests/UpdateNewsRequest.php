<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateNewsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->hasPermissionTo('manage news');
    }

    public function rules(): array
    {
        return [
            'category_id' => 'sometimes|exists:news_categories,id',
            'title' => 'sometimes|string|max:255',
            'content' => 'sometimes|string',
            'thumbnail' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'subdistrict_id' => 'nullable|exists:subdistricts,id',
            'is_emergency' => 'boolean',
            'status' => 'in:draft,published',
        ];
    }
}
