<x-app-layout>
    <x-slot name="header">
        <h2 class="text-lg font-semibold text-slate-900">Empresas</h2>
    </x-slot>

    <x-pw.page-header title="Empresa" description="Consulte e atualize os dados da empresa vinculada à sua conta.">
        <x-slot name="actions">
            @if (!auth()->user()?->empresa_id)
                <x-pw.button :href="route('empresas.create')">
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M12 5v14M5 12h14" />
                    </svg>
                    Nova empresa
                </x-pw.button>
            @endif
        </x-slot>
    </x-pw.page-header>

    <x-pw.table-card title="Dados empresariais" description="Informações cadastrais utilizadas em toda a plataforma.">
        <x-slot name="actions">
            <x-pw.search-form placeholder="Buscar por razão social, CNPJ ou cidade" />
        </x-slot>

        <table class="pw-table min-w-[860px]">
            <thead>
                <tr>
                    <th>Empresa</th>
                    <th>CNPJ</th>
                    <th>Contato</th>
                    <th>Localização</th>
                    <th>Status</th>
                    <th class="text-right">Ações</th>
                </tr>
            </thead>

            <tbody>
                @forelse ($items as $empresa)
                    <tr>
                        <td>
                            <div class="font-semibold text-slate-900">
                                {{ $empresa->nome_fantasia }}
                            </div>

                            <div class="mt-0.5 text-xs text-slate-500">
                                {{ $empresa->razao_social }}
                            </div>
                        </td>

                        <td class="whitespace-nowrap">
                            {{ $empresa->cnpj }}
                        </td>

                        <td>
                            <div>
                                {{ $empresa->email ?: 'E-mail não informado' }}
                            </div>

                            <div class="mt-0.5 text-xs text-slate-500">
                                {{ $empresa->telefone ?: 'Telefone não informado' }}
                            </div>
                        </td>

                        <td>
                            {{ collect([$empresa->cidade, $empresa->estado])->filter()->join(' / ') ?:
                                'Não informada' }}
                        </td>

                        <td>
                            <x-pw.badge :variant="$empresa->ativo ? 'success' : 'neutral'">
                                {{ $empresa->ativo ? 'Ativa' : 'Inativa' }}
                            </x-pw.badge>
                        </td>

                        <td>
                            <div class="flex justify-end gap-2">
                                <x-pw.button :href="route('empresas.edit', $empresa)" variant="ghost" size="sm"
                                    aria-label="Editar empresa">
                                    Editar
                                </x-pw.button>

                                <form action="{{ route('empresas.destroy', $empresa->id) }}" method="POST"
                                    class="inline"
                                    onsubmit="return confirm('Deseja realmente excluir esta empresa? Esta ação não poderá ser desfeita.')">
                                    @csrf
                                    @method('DELETE')

                                    <x-pw.button type="submit" variant="danger" size="sm"
                                        aria-label="Excluir empresa">
                                        Excluir
                                    </x-pw.button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="!p-0">
                            <x-pw.empty-state title="Nenhuma empresa encontrada"
                                description="Cadastre uma empresa para começar a utilizar o PontoWeb.">
                                @if (!auth()->user()?->empresa_id)
                                    <x-pw.button :href="route('empresas.create')">
                                        Cadastrar empresa
                                    </x-pw.button>
                                @endif
                            </x-pw.empty-state>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        @if ($items->hasPages())
            <x-slot name="footer">
                {{ $items->links() }}
            </x-slot>
        @endif
    </x-pw.table-card>
</x-app-layout>
