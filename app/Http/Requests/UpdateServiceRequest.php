<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateServiceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->hasPermissionTo('manage services');
    }

    public function rules(): array
    {
        return [
            'name' => 'sometimes|string|max:255',
            'description' => 'nullable|string',
            'duration' => 'sometimes|integer|min:5|max:480',
            'daily_quota' => 'sometimes|integer|min:1|max:999',
            'is_active' => 'boolean',
        ];
    }
}
