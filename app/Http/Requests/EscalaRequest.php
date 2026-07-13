<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class EscalaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->empresa_id !== null;
    }

    protected function prepareForValidation(): void
    {
        foreach (['domingo', 'segunda', 'terca', 'quarta', 'quinta', 'sexta', 'sabado', 'ativo'] as $campo) {
            $this->merge([$campo => $this->boolean($campo)]);
        }
    }

    public function rules(): array
    {
        $empresaId = auth()->user()->empresa_id;
        $escala = $this->route('escala');
        $id = is_object($escala) ? $escala->getKey() : $escala;

        return [
            'descricao' => [
                'required', 'string', 'max:255',
                Rule::unique('escalas', 'descricao')
                    ->where(fn ($query) => $query->where('empresa_id', $empresaId))
                    ->ignore($id),
            ],
            'tipo' => ['required', Rule::in(['5x2', '6x1', '12x36', 'Personalizada'])],
            'domingo' => ['required', 'boolean'],
            'segunda' => ['required', 'boolean'],
            'terca' => ['required', 'boolean'],
            'quarta' => ['required', 'boolean'],
            'quinta' => ['required', 'boolean'],
            'sexta' => ['required', 'boolean'],
            'sabado' => ['required', 'boolean'],
            'ativo' => ['required', 'boolean'],
        ];
    }

    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            $dias = ['domingo', 'segunda', 'terca', 'quarta', 'quinta', 'sexta', 'sabado'];
            if (! collect($dias)->contains(fn ($dia) => $this->boolean($dia))) {
                $validator->errors()->add('segunda', 'Selecione pelo menos um dia de trabalho.');
            }
        });
    }
}
