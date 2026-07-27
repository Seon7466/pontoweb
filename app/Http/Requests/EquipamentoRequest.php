<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class EquipamentoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'ativo' => $this->boolean('ativo'),
            'verificar_ssl' => $this->boolean('verificar_ssl'),
            'modo_671' => $this->boolean('modo_671'),
        ]);
    }

    public function rules(): array
    {
        return [
            'nome' => [
                'required',
                'string',
                'max:120',
            ],

            'fabricante' => [
                'nullable',
                'string',
                'max:120',
            ],

            'modelo' => [
                'nullable',
                'string',
                'max:120',
            ],

            'numero_serie' => [
                'nullable',
                'string',
                'max:120',
            ],

            'ip' => [
                'nullable',
                'ip',
            ],

            'porta' => [
                'nullable',
                'integer',
                'between:1,65535',
            ],

            'protocolo' => [
                'required',
                Rule::in([
                    'http',
                    'https',
                ]),
            ],

            'tipo_integracao' => [
                'required',
                Rule::in([
                    'arquivo',
                    'api',
                    'rede',
                    'software_intermediario',
                ]),
            ],

            'usuario_api' => [
                'nullable',
                'string',
                'max:120',
            ],

            'senha_api' => [
                'nullable',
                'string',
                'max:255',
            ],

            'verificar_ssl' => [
                'boolean',
            ],

            'modo_671' => [
                'boolean',
            ],

            'timeout_segundos' => [
                'required',
                'integer',
                'between:3,120',
            ],

            'timezone' => [
                'required',
                'timezone',
            ],

            'ativo' => [
                'boolean',
            ],
        ];
    }
}
