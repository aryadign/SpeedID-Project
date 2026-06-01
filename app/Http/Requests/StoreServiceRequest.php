<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreServiceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->hasPermissionTo('manage services');
    }

    public function rules(): array
    {
        return [
            'institution_id' => 'required|exists:institutions,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'duration' => 'required|integer|min:5|max:480',
            'daily_quota' => 'required|integer|min:1|max:999',
            'is_active' => 'boolean',
        ];
    }
}
