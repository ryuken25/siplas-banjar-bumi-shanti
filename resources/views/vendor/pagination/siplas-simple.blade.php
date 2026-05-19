@if ($paginator->hasPages())
    <nav class="mt-6 flex items-center justify-between gap-3">
        @if ($paginator->onFirstPage())
            <span class="px-4 py-2 rounded-lg bg-white text-slate-400 border border-slate-200 text-sm cursor-not-allowed">← Sebelumnya</span>
        @else
            <a href="{{ $paginator->previousPageUrl() }}" class="px-4 py-2 rounded-lg bg-white text-slate-700 hover:bg-primary-50 hover:text-primary-700 border border-slate-200 transition text-sm">← Sebelumnya</a>
        @endif
        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}" class="px-4 py-2 rounded-lg bg-white text-slate-700 hover:bg-primary-50 hover:text-primary-700 border border-slate-200 transition text-sm">Selanjutnya →</a>
        @else
            <span class="px-4 py-2 rounded-lg bg-white text-slate-400 border border-slate-200 text-sm cursor-not-allowed">Selanjutnya →</span>
        @endif
    </nav>
@endif
