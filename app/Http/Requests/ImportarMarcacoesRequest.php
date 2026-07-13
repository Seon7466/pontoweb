<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ImportarMarcacoesRequest extends FormRequest
{
    public function authorize(): bool { return auth()->check(); }

    public function rules(): array
    {
        return [
            'equipamento_id' => ['required', 'integer'],
            'arquivo' => ['required', 'file', 'mimes:csv,txt', 'max:10240'],
        ];
    }
}
