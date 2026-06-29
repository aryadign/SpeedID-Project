<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreSlotRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->hasPermissionTo('manage slots');
    }

    public function rules(): array
    {
        return [
            'service_id' => 'required|exists:services,id',
            'date' => 'required|date|after_or_equal:today',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'quota' => 'required|integer|min:1|max:999',
        ];
    }

    protected function prepareForValidation(): void
    {
        if ($this->route('service')) {
            $this->merge([
                'service_id' => $this->route('service') instanceof \App\Models\Service
                    ? $this->route('service')->id
                    : $this->route('service'),
            ]);
        }
    }
}
