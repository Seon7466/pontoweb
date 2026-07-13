<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class HorarioRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->empresa_id !== null;
    }

    public function rules(): array
    {
        $empresaId = auth()->user()->empresa_id;
        $horario = $this->route('horario');
        $id = is_object($horario) ? $horario->getKey() : $horario;

        return [
            'descricao' => [
                'required', 'string', 'max:255',
                Rule::unique('horarios', 'descricao')
                    ->where(fn ($query) => $query->where('empresa_id', $empresaId))
                    ->ignore($id),
            ],
            'entrada' => ['required', 'date_format:H:i'],
            'saida' => ['required', 'date_format:H:i', 'different:entrada'],
            'inicio_intervalo' => ['nullable', 'date_format:H:i', 'required_with:fim_intervalo'],
            'fim_intervalo' => ['nullable', 'date_format:H:i', 'required_with:inicio_intervalo', 'different:inicio_intervalo'],
            'tolerancia_entrada' => ['nullable', 'integer', 'min:0', 'max:180'],
            'tolerancia_saida' => ['nullable', 'integer', 'min:0', 'max:180'],
        ];
    }
}
