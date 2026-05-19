@props(['items' => []])

<nav class="flex items-center text-sm" aria-label="Breadcrumb">
    <ol class="inline-flex items-center gap-1.5 flex-wrap">
        @foreach($items as $i => $item)
            <li class="inline-flex items-center gap-1.5">
                @if($i > 0)
                    <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                @endif
                @if(! empty($item['href']) && ! ($item['active'] ?? false))
                    <a href="{{ $item['href'] }}" class="text-slate-500 hover:text-primary-700 font-medium transition">{{ $item['label'] }}</a>
                @else
                    <span class="text-slate-900 font-semibold">{{ $item['label'] }}</span>
                @endif
            </li>
        @endforeach
    </ol>
</nav>
