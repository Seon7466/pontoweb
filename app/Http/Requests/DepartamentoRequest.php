<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class DepartamentoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check()
            && auth()->user()->empresa_id !== null;
    }

    public function rules(): array
    {
        $empresaId = auth()->user()->empresa_id;

        $departamento = $this->route('departamento');

        $id = is_object($departamento)
            ? $departamento->getKey()
            : $departamento;

        return [
            'nome' => [
                'required',
                'string',
                'max:100',
                Rule::unique('departamentos', 'nome')
                    ->where(
                        fn ($query) => $query
                            ->where('empresa_id', $empresaId)
                    )
                    ->ignore($id),
            ],

            'responsavel' => [
                'nullable',
                'string',
                'max:255',
            ],

            'ativo' => [
                'nullable',
                'boolean',
            ],
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'ativo' => $this->boolean('ativo'),
        ]);
    }
}
