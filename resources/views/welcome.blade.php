<x-guest-layout>
    <div class="w-full max-w-xl rounded-2xl border border-slate-200 bg-white/90 p-8 text-slate-900 shadow-xl shadow-slate-900/10">
        <h1 class="text-3xl font-bold tracking-tight">POS Toko Emas</h1>
        <p class="mt-3 text-sm text-slate-600">
            Platform kasir modern untuk operasional toko emas multi cabang dengan dukungan offline.
        </p>
        <div class="mt-6 flex flex-wrap gap-3">
            <a href="{{ route('login') }}" class="inline-flex items-center rounded-lg bg-amber-500 px-4 py-2 text-sm font-semibold text-white hover:bg-amber-400">
                Masuk
            </a>
            <a href="{{ route('register') }}" class="inline-flex items-center rounded-lg border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50">
                Daftar
            </a>
        </div>
    </div>
</x-guest-layout>
