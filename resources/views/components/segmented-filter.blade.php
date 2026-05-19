@props(['name' => 'status', 'options' => [], 'current' => '', 'route' => ''])

<div class="inline-flex items-center bg-slate-100 rounded-lg p-1 overflow-x-auto scrollbar-thin">
    @foreach($options as $key => $label)
        @php
            $isActive = (string) $current === (string) $key;
            $url = $route
                ? $route . '?' . http_build_query(array_filter(array_merge(request()->query(), [$name => $key === '' ? null : $key])))
                : '?' . http_build_query(array_filter(array_merge(request()->query(), [$name => $key === '' ? null : $key])));
        @endphp
        <a href="{{ $url }}"
           class="px-3.5 py-1.5 text-sm font-medium rounded-md transition-all whitespace-nowrap {{ $isActive ? 'bg-white shadow-sm text-primary-700' : 'text-slate-600 hover:text-slate-900' }}">
            {{ $label }}
        </a>
    @endforeach
</div>
