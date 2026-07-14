<?php

namespace App\Http\Requests\Api\V1;

use Illuminate\Foundation\Http\FormRequest;

class HeartbeatRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'installation_id' => ['required', 'uuid'],
            'name' => ['nullable', 'string', 'max:120'],
            'machine_name' => ['required', 'string', 'max:120'],
            'agent_version' => ['required', 'string', 'max:40'],
            'os_name' => ['nullable', 'string', 'max:120'],
            'os_version' => ['nullable', 'string', 'max:120'],
            'local_ip' => ['nullable', 'ip'],
            'metadata' => ['nullable', 'array'],
        ];
    }
}
