<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ImportarMarcacoesRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        $empresaId = auth()->user()?->empresa_id;

        return [
            'equipamento_id' => [
                'required',
                'integer',
                Rule::exists('equipamentos', 'id')
                    ->where('empresa_id', $empresaId)
                    ->where('ativo', true),
            ],

            'arquivo' => [
                'required',
                'file',
                'mimes:csv,txt',
                'max:10240',
            ],
        ];
    }
}
