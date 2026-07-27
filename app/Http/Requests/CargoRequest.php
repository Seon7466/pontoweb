<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CargoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check()
            && auth()->user()->empresa_id !== null;
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'ativo' => $this->boolean('ativo'),
        ]);
    }

    public function rules(): array
    {
        $empresaId = auth()->user()->empresa_id;

        $cargo = $this->route('cargo');
        $id = is_object($cargo)
            ? $cargo->getKey()
            : $cargo;

        return [
            'nome' => [
                'required',
                'string',
                'max:120',
                Rule::unique('cargos', 'nome')
                    ->where(fn ($query) => $query->where('empresa_id', $empresaId))
                    ->ignore($id),
            ],

            'departamento_id' => [
                'nullable',
                'integer',
                Rule::exists('departamentos', 'id')
                    ->where(fn ($query) => $query->where('empresa_id', $empresaId)),
            ],

            'cbo' => [
                'nullable',
                'string',
                'max:20',
            ],

            'descricao' => [
                'nullable',
                'string',
                'max:5000',
            ],

            'ativo' => [
                'nullable',
                'boolean',
            ],
        ];
    }
}
