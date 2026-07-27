@php
    $item = $item ?? null;
@endphp

<div class="space-y-6">
    <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
        <x-pw.input
            label="Nome do departamento"
            name="nome"
            :value="old('nome', $item?->nome)"
            placeholder="Ex.: Recursos Humanos"
            required
        />

        <x-pw.input
            label="Responsável"
            name="responsavel"
            :value="old('responsavel', $item?->responsavel)"
            placeholder="Nome do gestor responsável"
        />
    </div>

    <div class="rounded-lg border border-slate-200 bg-slate-50 p-4">
        <x-pw.checkbox
            label="Departamento ativo"
            name="ativo"
            :checked="(bool) old('ativo', $item?->ativo ?? true)"
            help="Departamentos inativos não devem ser utilizados em novos cadastros de funcionários e cargos."
        />
    </div>
</div>
