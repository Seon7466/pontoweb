@php
    $item = $item ?? null;

    $dias = [
        'domingo' => 'Domingo',
        'segunda' => 'Segunda-feira',
        'terca' => 'Terça-feira',
        'quarta' => 'Quarta-feira',
        'quinta' => 'Quinta-feira',
        'sexta' => 'Sexta-feira',
        'sabado' => 'Sábado',
    ];

    $diasPadrao = [
        'domingo' => false,
        'segunda' => true,
        'terca' => true,
        'quarta' => true,
        'quinta' => true,
        'sexta' => true,
        'sabado' => false,
    ];
@endphp

<div class="space-y-8">
    <section>
        <div class="mb-4">
            <h3 class="text-base font-semibold text-slate-900">
                Identificação da escala
            </h3>

            <p class="mt-1 text-sm text-slate-500">
                Defina o nome e o modelo de jornada utilizado.
            </p>
        </div>

        <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
            <x-pw.input
                label="Descrição"
                name="descricao"
                :value="old('descricao', $item?->descricao)"
                placeholder="Ex.: Administrativo 5x2"
                required
                autofocus
            />

            <x-pw.select
                label="Tipo"
                name="tipo"
                :value="old('tipo', $item?->tipo ?? '5x2')"
                required
            >
                @foreach (['5x2', '6x1', '12x36', 'Personalizada'] as $tipo)
                    <option
                        value="{{ $tipo }}"
                        @selected(old('tipo', $item?->tipo ?? '5x2') === $tipo)
                    >
                        {{ $tipo }}
                    </option>
                @endforeach
            </x-pw.select>
        </div>
    </section>

    <section class="border-t border-slate-200 pt-7">
        <div class="mb-4">
            <h3 class="text-base font-semibold text-slate-900">
                Dias de trabalho
            </h3>

            <p class="mt-1 text-sm text-slate-500">
                Selecione pelo menos um dia para compor a escala.
            </p>
        </div>

        <div class="grid grid-cols-1 gap-3 rounded-xl border border-slate-200 bg-slate-50 p-4 sm:grid-cols-2 lg:grid-cols-4">
            @foreach ($dias as $campo => $label)
                <x-pw.checkbox
                    :label="$label"
                    :name="$campo"
                    :checked="(bool) old($campo, $item?->{$campo} ?? $diasPadrao[$campo])"
                />
            @endforeach
        </div>

        @error('segunda')
            <p class="pw-error">
                {{ $message }}
            </p>
        @enderror
    </section>

    <section class="border-t border-slate-200 pt-7">
        <x-pw.checkbox
            label="Escala ativa"
            name="ativo"
            :checked="(bool) old('ativo', $item?->ativo ?? true)"
            help="Somente escalas ativas ficam disponíveis no cadastro de funcionários."
        />
    </section>
</div>
