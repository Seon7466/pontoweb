<aside class="min-h-screen w-64 flex-shrink-0 bg-gray-900 text-white">
    <div class="border-b border-gray-700 p-6">
        <a href="{{ route('dashboard') }}" class="text-2xl font-bold text-white">
            PontoWeb
        </a>
    </div>

    <nav class="space-y-2 p-4">
        <a href="{{ route('dashboard') }}"
           class="block rounded px-4 py-2 transition hover:bg-gray-700 {{ request()->routeIs('dashboard') ? 'bg-gray-700' : '' }}">
            Painel
        </a>

        <div class="mt-5 px-1 text-xs uppercase tracking-wide text-gray-400">
            Cadastros
        </div>

        <a href="{{ route('empresas.index') }}"
           class="block rounded px-4 py-2 transition hover:bg-gray-700 {{ request()->routeIs('empresas.*') ? 'bg-gray-700' : '' }}">
            Empresa
        </a>

        <a href="/departamentos"
   class="block rounded px-4 py-2 transition hover:bg-gray-700 {{ request()->is('departamentos*') ? 'bg-gray-700' : '' }}">
    Departamentos
</a>

        <a href="{{ route('cargos.index') }}"
           class="block rounded px-4 py-2 transition hover:bg-gray-700 {{ request()->routeIs('cargos.*') ? 'bg-gray-700' : '' }}">
            Cargos
        </a>

        <a href="{{ route('horarios.index') }}"
           class="block rounded px-4 py-2 transition hover:bg-gray-700 {{ request()->routeIs('horarios.*') ? 'bg-gray-700' : '' }}">
            Horários
        </a>

        <a href="{{ route('escalas.index') }}"
           class="block rounded px-4 py-2 transition hover:bg-gray-700 {{ request()->routeIs('escalas.*') ? 'bg-gray-700' : '' }}">
            Escalas
        </a>

        <a href="{{ route('funcionarios.index') }}"
           class="block rounded px-4 py-2 transition hover:bg-gray-700 {{ request()->routeIs('funcionarios.*') ? 'bg-gray-700' : '' }}">
            Funcionários
        </a>

        <div class="mt-5 px-1 text-xs uppercase tracking-wide text-gray-400">
            Ponto
        </div>

        <a href="{{ route('ponto.index') }}"
           class="block rounded px-4 py-2 transition hover:bg-gray-700 {{ request()->routeIs('ponto.*') ? 'bg-gray-700' : '' }}">
            Batidas
        </a>

        <a href="{{ route('equipamentos.index') }}"
           class="block rounded px-4 py-2 transition hover:bg-gray-700 {{ request()->routeIs('equipamentos.*') ? 'bg-gray-700' : '' }}">
            Equipamentos
        </a>

        <span class="block cursor-not-allowed rounded px-4 py-2 text-gray-500">
            Relatórios (em breve)
        </span>
    </nav>
</aside>
