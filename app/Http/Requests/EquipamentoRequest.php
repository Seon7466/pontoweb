<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class EquipamentoRequest extends FormRequest
{
    public function authorize(): bool { return auth()->check(); }

    public function rules(): array
    {
        return [
            'nome' => ['required', 'string', 'max:120'],
            'fabricante' => ['nullable', 'string', 'max:120'],
            'modelo' => ['nullable', 'string', 'max:120'],
            'numero_serie' => ['nullable', 'string', 'max:120'],
            'ip' => ['nullable', 'ip'],
            'porta' => ['nullable', 'integer', 'between:1,65535'],
            'protocolo' => ['required', Rule::in(['http', 'https'])],
            'tipo_integracao' => ['required', Rule::in(['arquivo', 'api', 'rede', 'software_intermediario'])],
            'usuario_api' => ['nullable', 'string', 'max:120'],
            'senha_api' => ['nullable', 'string', 'max:255'],
            'verificar_ssl' => ['nullable', 'boolean'],
            'modo_671' => ['nullable', 'boolean'],
            'timeout_segundos' => ['required', 'integer', 'between:3,120'],
            'timezone' => ['required', 'timezone'],
            'ativo' => ['nullable', 'boolean'],
        ];
    }
}
