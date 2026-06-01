<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreReportRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'category_id' => 'required|exists:report_categories,id',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'anonymous' => 'boolean',
            'media' => 'nullable|array',
            'media.*' => 'file|mimes:jpg,jpeg,png,webp,mp4|max:20480',
        ];
    }

    public function messages(): array
    {
        return [
            'media.*.max' => 'Setiap file maksimal 20 MB',
            'media.*.mimes' => 'File harus berupa: jpg, jpeg, png, webp, atau mp4',
        ];
    }
}
