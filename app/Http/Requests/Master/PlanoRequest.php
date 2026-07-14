<?php

namespace App\Http\Requests\Master;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PlanoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return (bool) $this->user()?->is_master;
    }

    public function rules(): array
    {
        $plano = $this->route('plano');
        $planoId = is_object($plano) ? $plano->getKey() : $plano;

        return [
            'nome' => ['required', 'string', 'max:100', Rule::unique('planos', 'nome')->ignore($planoId)],
            'descricao' => ['nullable', 'string', 'max:2000'],
            'max_funcionarios' => ['nullable', 'integer', 'min:1'],
            'max_relogios' => ['nullable', 'integer', 'min:1'],
            'valor_mensal' => ['nullable', 'numeric', 'min:0', 'max:99999999.99'],
            'armazenamento_gb' => ['nullable', 'integer', 'min:1'],
            'suporte' => ['required', 'string', 'max:50'],
            'api_disponivel' => ['nullable', 'boolean'],
            'app_mobile' => ['nullable', 'boolean'],
            'bi_disponivel' => ['nullable', 'boolean'],
            'geolocalizacao' => ['nullable', 'boolean'],
            'integracoes' => ['nullable', 'boolean'],
            'backup_automatico' => ['nullable', 'boolean'],
            'marketplace' => ['nullable', 'boolean'],
            'ordem' => ['nullable', 'integer', 'min:0', 'max:999'],
            'ativo' => ['nullable', 'boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'nome.unique' => 'Já existe um plano com este nome.',
            'max_funcionarios.min' => 'Informe pelo menos 1 funcionário ou deixe vazio para ilimitado.',
            'max_relogios.min' => 'Informe pelo menos 1 relógio ou deixe vazio para ilimitado.',
        ];
    }
}
