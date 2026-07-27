<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SincronizarControlIdRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'processar' => $this->boolean('processar'),
        ]);
    }

    public function rules(): array
    {
        return [
            'processar' => [
                'boolean',
            ],
        ];
    }
}
