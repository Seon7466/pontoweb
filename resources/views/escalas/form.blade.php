<div class="space-y-8">
    <section>
        <div class="mb-4">
            <h3 class="text-base font-semibold text-slate-900">Identificação da escala</h3>
            <p class="mt-1 text-sm text-slate-500">Defina o nome e o modelo de jornada utilizado.</p>
        </div>

        <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
            <x-pw.input
                label="Descrição"
                name="descricao"
                :value="$item->descricao ?? ''"
                placeholder="Ex.: Administrativo 5x2"
                required
            />

            <x-pw.select label="Tipo" name="tipo" :value="$item->tipo ?? '5x2'" required>
                @foreach (['5x2', '6x1', '12x36', 'Personalizada'] as $tipo)
                    <option value="{{ $tipo }}" @selected(old('tipo', $item->tipo ?? '5x2') === $tipo)>{{ $tipo }}</option>
                @endforeach
            </x-pw.select>
        </div>
    </section>

    <section class="border-t border-slate-200 pt-7">
        <div class="mb-4">
            <h3 class="text-base font-semibold text-slate-900">Dias de trabalho</h3>
            <p class="mt-1 text-sm text-slate-500">Selecione pelo menos um dia para compor a escala.</p>
        </div>

        <div class="grid grid-cols-1 gap-3 rounded-xl border border-slate-200 bg-slate-50 p-4 sm:grid-cols-2 lg:grid-cols-4">
            <x-pw.checkbox label="Domingo" name="domingo" :checked="$item->domingo ?? false" />
            <x-pw.checkbox label="Segunda-feira" name="segunda" :checked="$item->segunda ?? true" />
            <x-pw.checkbox label="Terça-feira" name="terca" :checked="$item->terca ?? true" />
            <x-pw.checkbox label="Quarta-feira" name="quarta" :checked="$item->quarta ?? true" />
            <x-pw.checkbox label="Quinta-feira" name="quinta" :checked="$item->quinta ?? true" />
            <x-pw.checkbox label="Sexta-feira" name="sexta" :checked="$item->sexta ?? true" />
            <x-pw.checkbox label="Sábado" name="sabado" :checked="$item->sabado ?? false" />
        </div>

        @error('segunda')
            <p class="pw-error">{{ $message }}</p>
        @enderror
    </section>

    <section class="border-t border-slate-200 pt-7">
        <x-pw.checkbox
            label="Escala ativa"
            name="ativo"
            :checked="$item->ativo ?? true"
            help="Somente escalas ativas ficam disponíveis no cadastro de funcionários."
        />
    </section>
</div>
