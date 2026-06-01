<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreNewsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->hasPermissionTo('manage news');
    }

    public function rules(): array
    {
        return [
            'category_id' => 'required|exists:news_categories,id',
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'thumbnail' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'subdistrict_id' => 'nullable|exists:subdistricts,id',
            'is_emergency' => 'boolean',
            'status' => 'in:draft,published',
        ];
    }
}
