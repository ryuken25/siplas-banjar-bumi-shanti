@props(['items' => []])

<ol class="relative space-y-6 ml-2">
    @foreach($items as $i => $item)
        @php
            $color = $item['color'] ?? 'primary';
            $isActive = $item['active'] ?? false;
            $palette = [
                'primary' => 'bg-primary-500 ring-primary-100',
                'amber'   => 'bg-amber-500 ring-amber-100',
                'sky'     => 'bg-sky-500 ring-sky-100',
                'rose'    => 'bg-rose-500 ring-rose-100',
                'slate'   => 'bg-slate-300 ring-slate-100',
                'emerald' => 'bg-emerald-500 ring-emerald-100',
            ];
            $dotClass = $palette[$color] ?? $palette['slate'];
        @endphp
        <li class="relative pl-8">
            @if(! $loop->last)
                <span class="absolute left-2.5 top-4 w-px h-full bg-slate-200" aria-hidden="true"></span>
            @endif
            <span class="absolute left-0 top-1 inline-flex w-5 h-5 rounded-full ring-4 {{ $dotClass }} {{ $isActive ? 'animate-soft-pulse' : '' }}"></span>
            <div>
                <p class="text-sm font-semibold text-slate-900">{{ $item['title'] ?? '' }}</p>
                @if(! empty($item['description']))
                    <p class="text-sm text-slate-600 mt-0.5">{{ $item['description'] }}</p>
                @endif
                @if(! empty($item['time']))
                    <p class="text-xs text-slate-400 mt-1">{{ $item['time'] }}</p>
                @endif
            </div>
        </li>
    @endforeach
</ol>
