@if ($paginator->hasPages())
    <nav role="navigation" aria-label="Navigasi halaman" class="mt-6 flex items-center justify-between gap-3 flex-wrap">
        <p class="text-sm text-slate-500">
            Menampilkan
            <span class="font-semibold text-slate-700">{{ $paginator->firstItem() ?? 0 }}</span>
            sampai
            <span class="font-semibold text-slate-700">{{ $paginator->lastItem() ?? 0 }}</span>
            dari
            <span class="font-semibold text-slate-700">{{ $paginator->total() }}</span>
            data
        </p>

        <ul class="inline-flex items-center gap-1">
            {{-- Previous --}}
            @if ($paginator->onFirstPage())
                <li>
                    <span class="inline-flex items-center justify-center w-9 h-9 rounded-lg bg-white text-slate-400 cursor-not-allowed border border-slate-200">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
                    </span>
                </li>
            @else
                <li>
                    <a href="{{ $paginator->previousPageUrl() }}" rel="prev" class="inline-flex items-center justify-center w-9 h-9 rounded-lg bg-white text-slate-700 hover:bg-primary-50 hover:text-primary-700 border border-slate-200 transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
                    </a>
                </li>
            @endif

            {{-- Numbers --}}
            @foreach ($elements as $element)
                @if (is_string($element))
                    <li><span class="inline-flex items-center justify-center w-9 h-9 text-slate-400">{{ $element }}</span></li>
                @endif
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <li><span class="inline-flex items-center justify-center w-9 h-9 rounded-lg bg-gradient-to-b from-primary-500 to-primary-600 text-white font-semibold shadow-sm shadow-primary-500/30">{{ $page }}</span></li>
                        @else
                            <li><a href="{{ $url }}" class="inline-flex items-center justify-center w-9 h-9 rounded-lg bg-white text-slate-700 hover:bg-primary-50 hover:text-primary-700 border border-slate-200 transition">{{ $page }}</a></li>
                        @endif
                    @endforeach
                @endif
            @endforeach

            {{-- Next --}}
            @if ($paginator->hasMorePages())
                <li>
                    <a href="{{ $paginator->nextPageUrl() }}" rel="next" class="inline-flex items-center justify-center w-9 h-9 rounded-lg bg-white text-slate-700 hover:bg-primary-50 hover:text-primary-700 border border-slate-200 transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                    </a>
                </li>
            @else
                <li>
                    <span class="inline-flex items-center justify-center w-9 h-9 rounded-lg bg-white text-slate-400 cursor-not-allowed border border-slate-200">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                    </span>
                </li>
            @endif
        </ul>
    </nav>
@endif
