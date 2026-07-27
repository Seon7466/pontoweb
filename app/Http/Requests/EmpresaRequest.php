<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class EmpresaRequest extends FormRequest
{
    public function authorize(): bool
    {
        if (! auth()->check()) {
            return false;
        }

        $empresa = $this->route('empresa');
        $empresaId = is_object($empresa) ? $empresa->getKey() : $empresa;
        $usuarioEmpresaId = auth()->user()->empresa_id;

        if ($empresaId === null) {
            return $usuarioEmpresaId === null;
        }

        return (int) $usuarioEmpresaId === (int) $empresaId;
    }

    public function rules(): array
    {
        $empresa = $this->route('empresa');
        $empresaId = is_object($empresa) ? $empresa->getKey() : $empresa;

        return [
            'razao_social' => ['required', 'string', 'max:255'],
            'nome_fantasia' => ['required', 'string', 'max:255'],

            'cnpj' => [
                'required',
                'string',
                'max:18',
                Rule::unique('empresas', 'cnpj')->ignore($empresaId),
            ],

            'inscricao_estadual' => ['nullable', 'string', 'max:255'],
            'telefone' => ['nullable', 'string', 'max:20'],
            'email' => ['nullable', 'email:rfc', 'max:255'],
            'cep' => ['nullable', 'string', 'max:10'],
            'endereco' => ['nullable', 'string', 'max:255'],
            'numero' => ['nullable', 'string', 'max:20'],
            'bairro' => ['nullable', 'string', 'max:255'],
            'cidade' => ['nullable', 'string', 'max:255'],
            'estado' => ['nullable', 'string', 'size:2'],

            'logo' => [
                'nullable',
                'image',
                'mimes:png,jpg,jpeg,webp',
                'max:2048',
            ],

            'ativo' => ['nullable', 'boolean'],
        ];
    }
}