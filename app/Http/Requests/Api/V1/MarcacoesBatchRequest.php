<?php

namespace App\Http\Requests\Api\V1;

use Illuminate\Foundation\Http\FormRequest;

class MarcacoesBatchRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'installation_id' => ['required', 'uuid'],
            'equipment_id' => ['required', 'integer'],
            'markings' => ['required', 'array', 'min:1', 'max:1000'],
            'markings.*.employee_code' => ['required', 'string', 'max:80'],
            'markings.*.occurred_at' => ['required', 'date'],
            'markings.*.nsr' => ['nullable', 'string', 'max:80'],
            'markings.*.raw' => ['nullable'],
        ];
    }
}
