@props(['striped' => false, 'hover' => true])

<div class="overflow-hidden rounded-xl border border-slate-200/70 bg-white shadow-soft">
    <div class="overflow-x-auto scrollbar-thin">
        <table {{ $attributes->class([
            'min-w-full divide-y divide-slate-100 text-sm',
        ]) }}>
            {{ $slot }}
        </table>
    </div>
</div>
