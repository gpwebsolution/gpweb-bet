<div class="flex items-center gap-3 px-2 py-3">
    <div class="relative">
        <img src="{{ method_exists($setting, 'logoUrl') ? $setting->logoUrl() : asset('assets/images/logo.svg') ?? asset('assets/images/favicon.png') }}"
             alt="{{ $setting?->software_name ?? 'MarioBET' }}"
             class="h-10 w-10 rounded-xl shadow-lg shadow-casino-red/20 ring-2 ring-casino-gold/20">
        <div class="absolute -bottom-0.5 -right-0.5 w-3 h-3 bg-emerald-500 rounded-full border-2 border-gray-900"></div>
    </div>
    <div class="flex flex-col">
        <span class="text-lg font-extrabold tracking-tight text-white leading-none">
            {{ $setting?->software_name ?? 'MarioBET' }}
        </span>
        <span class="text-[10px] font-semibold text-casino-gold/60 uppercase tracking-[0.2em]">Admin Panel</span>
    </div>
</div>
