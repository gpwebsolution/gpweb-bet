<x-filament-panels::page>
    <div class="flex flex-wrap gap-3 mb-6 p-4 bg-gradient-to-r from-casino-surface/50 to-casino-surface-alt/50 rounded-xl border border-casino-gold/10">
        <div>
            <label class="text-xs font-semibold text-casino-muted uppercase tracking-wider">De</label>
            <input type="date" wire:model.live="startDate" class="bg-gray-900 border border-casino-gold/20 rounded-lg px-3 py-1.5 text-sm text-white focus:border-casino-gold/50 focus:ring-1 focus:ring-casino-gold/20 transition-all">
        </div>
        <div>
            <label class="text-xs font-semibold text-casino-muted uppercase tracking-wider">Até</label>
            <input type="date" wire:model.live="endDate" class="bg-gray-900 border border-casino-gold/20 rounded-lg px-3 py-1.5 text-sm text-white focus:border-casino-gold/50 focus:ring-1 focus:ring-casino-gold/20 transition-all">
        </div>
    </div>

    {{-- Row 1 --}}
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-4">
        <div class="relative overflow-hidden rounded-xl p-5 bg-gradient-to-br from-blue-900/40 via-blue-800/20 to-transparent border border-blue-500/20 group hover:border-blue-500/40 transition-all duration-300 hover:shadow-lg hover:shadow-blue-500/10">
            <div class="absolute -top-6 -right-6 w-20 h-20 bg-blue-500/5 rounded-full blur-xl group-hover:bg-blue-500/10 transition-all"></div>
            <div class="relative">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-xs font-bold text-blue-400 uppercase tracking-widest">Sessões</span>
                    <x-heroicon-o-play class="w-5 h-5 text-blue-400/60" />
                </div>
                <p class="text-3xl font-extrabold text-white tracking-tight">{{ number_format($totalSessions, 0, ',', '.') }}</p>
                <div class="mt-2 h-1 w-full bg-blue-500/10 rounded-full overflow-hidden">
                    <div class="h-full w-full bg-gradient-to-r from-blue-500 to-blue-400 rounded-full" style="width: 65%"></div>
                </div>
            </div>
        </div>

        <div class="relative overflow-hidden rounded-xl p-5 bg-gradient-to-br from-emerald-900/40 via-emerald-800/20 to-transparent border border-emerald-500/20 group hover:border-emerald-500/40 transition-all duration-300 hover:shadow-lg hover:shadow-emerald-500/10">
            <div class="absolute -top-6 -right-6 w-20 h-20 bg-emerald-500/5 rounded-full blur-xl group-hover:bg-emerald-500/10 transition-all"></div>
            <div class="relative">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-xs font-bold text-emerald-400 uppercase tracking-widest">Receita (GGR)</span>
                    <x-heroicon-o-currency-dollar class="w-5 h-5 text-emerald-400/60" />
                </div>
                <p class="text-3xl font-extrabold text-white tracking-tight">R$ {{ number_format($totalRevenue, 2, ',', '.') }}</p>
                <div class="mt-2 h-1 w-full bg-emerald-500/10 rounded-full overflow-hidden">
                    <div class="h-full w-full bg-gradient-to-r from-emerald-500 to-emerald-400 rounded-full" style="width: 80%"></div>
                </div>
            </div>
        </div>

        <div class="relative overflow-hidden rounded-xl p-5 bg-gradient-to-br from-violet-900/40 via-violet-800/20 to-transparent border border-violet-500/20 group hover:border-violet-500/40 transition-all duration-300 hover:shadow-lg hover:shadow-violet-500/10">
            <div class="absolute -top-6 -right-6 w-20 h-20 bg-violet-500/5 rounded-full blur-xl group-hover:bg-violet-500/10 transition-all"></div>
            <div class="relative">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-xs font-bold text-violet-400 uppercase tracking-widest">Apostas</span>
                    <x-heroicon-o-arrow-trending-up class="w-5 h-5 text-violet-400/60" />
                </div>
                <p class="text-3xl font-extrabold text-white tracking-tight">R$ {{ number_format($totalBets, 2, ',', '.') }}</p>
                <div class="mt-2 h-1 w-full bg-violet-500/10 rounded-full overflow-hidden">
                    <div class="h-full w-full bg-gradient-to-r from-violet-500 to-violet-400 rounded-full" style="width: 55%"></div>
                </div>
            </div>
        </div>

        <div class="relative overflow-hidden rounded-xl p-5 bg-gradient-to-br from-sky-900/40 via-sky-800/20 to-transparent border border-sky-500/20 group hover:border-sky-500/40 transition-all duration-300 hover:shadow-lg hover:shadow-sky-500/10">
            <div class="absolute -top-6 -right-6 w-20 h-20 bg-sky-500/5 rounded-full blur-xl group-hover:bg-sky-500/10 transition-all"></div>
            <div class="relative">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-xs font-bold text-sky-400 uppercase tracking-widest">Depósitos Pagos</span>
                    <x-heroicon-o-arrow-down-tray class="w-5 h-5 text-sky-400/60" />
                </div>
                <p class="text-3xl font-extrabold text-white tracking-tight">R$ {{ number_format($totalDeposits, 2, ',', '.') }}</p>
                <div class="mt-2 h-1 w-full bg-sky-500/10 rounded-full overflow-hidden">
                    <div class="h-full w-full bg-gradient-to-r from-sky-500 to-sky-400 rounded-full" style="width: 70%"></div>
                </div>
            </div>
        </div>
    </div>

    {{-- Row 2 --}}
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
        <div class="relative overflow-hidden rounded-xl p-5 bg-gradient-to-br from-amber-900/40 via-amber-800/20 to-transparent border border-amber-500/20 group hover:border-amber-500/40 transition-all duration-300 hover:shadow-lg hover:shadow-amber-500/10">
            <div class="absolute -top-6 -right-6 w-20 h-20 bg-amber-500/5 rounded-full blur-xl group-hover:bg-amber-500/10 transition-all"></div>
            <div class="relative">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-xs font-bold text-amber-400 uppercase tracking-widest">Usuários Ativos</span>
                    <x-heroicon-o-users class="w-5 h-5 text-amber-400/60" />
                </div>
                <p class="text-3xl font-extrabold text-white tracking-tight">{{ number_format($activeUsers, 0, ',', '.') }}</p>
                <div class="mt-2 h-1 w-full bg-amber-500/10 rounded-full overflow-hidden">
                    <div class="h-full w-full bg-gradient-to-r from-amber-500 to-amber-400 rounded-full" style="width: 90%"></div>
                </div>
            </div>
        </div>

        <div class="relative overflow-hidden rounded-xl p-5 bg-gradient-to-br from-rose-900/40 via-rose-800/20 to-transparent border border-rose-500/20 group hover:border-rose-500/40 transition-all duration-300 hover:shadow-lg hover:shadow-rose-500/10">
            <div class="absolute -top-6 -right-6 w-20 h-20 bg-rose-500/5 rounded-full blur-xl group-hover:bg-rose-500/10 transition-all"></div>
            <div class="relative">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-xs font-bold text-rose-400 uppercase tracking-widest">Suspensos</span>
                    <x-heroicon-o-no-symbol class="w-5 h-5 text-rose-400/60" />
                </div>
                <p class="text-3xl font-extrabold text-white tracking-tight">{{ number_format($suspendedUsers, 0, ',', '.') }}</p>
                <div class="mt-2 h-1 w-full bg-rose-500/10 rounded-full overflow-hidden">
                    <div class="h-full w-full bg-gradient-to-r from-rose-500 to-rose-400 rounded-full" style="width: 15%"></div>
                </div>
            </div>
        </div>

        <div class="relative overflow-hidden rounded-xl p-5 bg-gradient-to-br from-orange-900/40 via-orange-800/20 to-transparent border border-orange-500/20 group hover:border-orange-500/40 transition-all duration-300 hover:shadow-lg hover:shadow-orange-500/10">
            <div class="absolute -top-6 -right-6 w-20 h-20 bg-orange-500/5 rounded-full blur-xl group-hover:bg-orange-500/10 transition-all"></div>
            <div class="relative">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-xs font-bold text-orange-400 uppercase tracking-widest">Saques Pagos</span>
                    <x-heroicon-o-arrow-up-tray class="w-5 h-5 text-orange-400/60" />
                </div>
                <p class="text-3xl font-extrabold text-white tracking-tight">R$ {{ number_format($totalWithdrawals, 2, ',', '.') }}</p>
                <div class="mt-2 h-1 w-full bg-orange-500/10 rounded-full overflow-hidden">
                    <div class="h-full w-full bg-gradient-to-r from-orange-500 to-orange-400 rounded-full" style="width: 45%"></div>
                </div>
            </div>
        </div>

        <div class="relative overflow-hidden rounded-xl p-5 bg-gradient-to-br from-purple-900/40 via-purple-800/20 to-transparent border border-purple-500/20 group hover:border-purple-500/40 transition-all duration-300 hover:shadow-lg hover:shadow-purple-500/10">
            <div class="absolute -top-6 -right-6 w-20 h-20 bg-purple-500/5 rounded-full blur-xl group-hover:bg-purple-500/10 transition-all"></div>
            <div class="relative">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-xs font-bold text-purple-400 uppercase tracking-widest">Comissões Pend.</span>
                    <x-heroicon-o-banknotes class="w-5 h-5 text-purple-400/60" />
                </div>
                <p class="text-3xl font-extrabold text-white tracking-tight">R$ {{ number_format($pendingCommissions, 2, ',', '.') }}</p>
                <div class="mt-2 h-1 w-full bg-purple-500/10 rounded-full overflow-hidden">
                    <div class="h-full w-full bg-gradient-to-r from-purple-500 to-purple-400 rounded-full" style="width: 40%"></div>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="rounded-xl border border-casino-gold/10 bg-gradient-to-br from-casino-surface/80 to-casino-surface-alt/80 overflow-hidden shadow-lg">
            @livewire(\App\Filament\Widgets\RevenueChartWidget::class)
        </div>
        <div class="rounded-xl border border-casino-gold/10 bg-gradient-to-br from-casino-surface/80 to-casino-surface-alt/80 overflow-hidden shadow-lg">
            @livewire(\App\Filament\Widgets\TopAffiliatesWidget::class)
        </div>
    </div>
</x-filament-panels::page>
