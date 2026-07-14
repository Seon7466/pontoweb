<?php

namespace App\Http\Requests\Master;

use App\Models\Licenca;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class LicencaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return (bool) $this->user()?->is_master;
    }

    protected function prepareForValidation(): void
    {
        $nullableIntegers = ['limite_funcionarios', 'limite_relogios'];
        $nullableValues = ['valor_contratado', 'termina_em', 'periodo_teste_ate'];
        $merge = [];

        foreach (array_merge($nullableIntegers, $nullableValues) as $field) {
            if ($this->input($field) === '') {
                $merge[$field] = null;
            }
        }

        $merge['renovacao_automatica'] = $this->boolean('renovacao_automatica');
        $this->merge($merge);
    }

    public function rules(): array
    {
        $licenca = $this->route('licenca');
        $licencaId = is_object($licenca) ? $licenca->getKey() : $licenca;

        return [
            'empresa_id' => ['required', 'integer', 'exists:empresas,id', Rule::unique('licencas', 'empresa_id')->ignore($licencaId)],
            'plano_id' => ['required', 'integer', 'exists:planos,id'],
            'status' => ['required', Rule::in([
                Licenca::STATUS_TESTE, Licenca::STATUS_ATIVA, Licenca::STATUS_SUSPENSA,
                Licenca::STATUS_VENCIDA, Licenca::STATUS_CANCELADA,
            ])],
            'ciclo_cobranca' => ['required', Rule::in(['mensal', 'anual', 'personalizado'])],
            'inicia_em' => ['required', 'date'],
            'termina_em' => ['nullable', 'date', 'after_or_equal:inicia_em'],
            'periodo_teste_ate' => ['nullable', 'date', 'after_or_equal:inicia_em'],
            'limite_funcionarios' => ['nullable', 'integer', 'min:1'],
            'limite_relogios' => ['nullable', 'integer', 'min:1'],
            'valor_contratado' => ['nullable', 'numeric', 'min:0', 'max:99999999.99'],
            'renovacao_automatica' => ['boolean'],
            'observacoes' => ['nullable', 'string', 'max:5000'],
        ];
    }

    public function messages(): array
    {
        return [
            'empresa_id.unique' => 'Esta empresa já possui uma licença cadastrada.',
            'termina_em.after_or_equal' => 'O vencimento não pode ser anterior à ativação.',
        ];
    }
}
