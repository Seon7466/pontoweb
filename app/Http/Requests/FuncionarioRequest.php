<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class FuncionarioRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check()
            && auth()->user()->empresa_id !== null;
    }

    public function rules(): array
    {
        $empresaId = auth()->user()->empresa_id;
        $funcionario = $this->route('funcionario');

        $funcionarioId = is_object($funcionario)
            ? $funcionario->getKey()
            : $funcionario;

        return [
            'nome' => [
                'required',
                'string',
                'max:255',
            ],

            'cpf' => [
                'required',
                'string',
                'max:14',
                Rule::unique('funcionarios', 'cpf')
                    ->where(
                        fn ($query) => $query
                            ->where('empresa_id', $empresaId)
                    )
                    ->ignore($funcionarioId),
            ],

            'rg' => [
                'nullable',
                'string',
                'max:30',
            ],

            'pis' => [
                'nullable',
                'string',
                'max:20',
            ],

            'matricula' => [
                'nullable',
                'string',
                'max:50',
                Rule::unique('funcionarios', 'matricula')
                    ->where(
                        fn ($query) => $query
                            ->where('empresa_id', $empresaId)
                    )
                    ->ignore($funcionarioId),
            ],

            'codigo_relogio' => [
                'nullable',
                'string',
                'max:100',
                Rule::unique('funcionarios', 'codigo_relogio')
                    ->where(
                        fn ($query) => $query
                            ->where('empresa_id', $empresaId)
                    )
                    ->ignore($funcionarioId),
            ],

            'nascimento' => [
                'nullable',
                'date',
                'before:today',
            ],

            'admissao' => [
                'required',
                'date',
            ],

            'demissao' => [
                'nullable',
                'date',
                'after_or_equal:admissao',
            ],

            'email' => [
                'nullable',
                'email:rfc',
                'max:255',
            ],

            'telefone' => [
                'nullable',
                'string',
                'max:20',
            ],

            'foto' => [
                'nullable',
                'string',
                'max:255',
            ],

            'status' => [
                'nullable',
                'boolean',
            ],

            'departamento_id' => [
                'nullable',
                'integer',
                Rule::exists('departamentos', 'id')
                    ->where(
                        fn ($query) => $query
                            ->where('empresa_id', $empresaId)
                    ),
            ],

            'cargo_id' => [
                'nullable',
                'integer',
                Rule::exists('cargos', 'id')
                    ->where(
                        fn ($query) => $query
                            ->where('empresa_id', $empresaId)
                    ),
            ],

            'horario_id' => [
                'nullable',
                'integer',
                Rule::exists('horarios', 'id')
                    ->where(
                        fn ($query) => $query
                            ->where('empresa_id', $empresaId)
                    ),
            ],

            'escala_id' => [
                'nullable',
                'integer',
                Rule::exists('escalas', 'id')
                    ->where(
                        fn ($query) => $query
                            ->where('empresa_id', $empresaId)
                    ),
            ],
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'status' => $this->boolean('status'),
        ]);
    }
}
