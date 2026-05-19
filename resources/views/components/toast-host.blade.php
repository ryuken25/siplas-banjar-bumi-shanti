{{-- Global toast host. Place once near root of layout. --}}
<div x-data="toastHost()"
     x-init="
        @if(session('success')) push({type:'success', message: @js(session('success'))}); @endif
        @if(session('error'))   push({type:'error',   message: @js(session('error'))});   @endif
        @if(session('warning')) push({type:'warning', message: @js(session('warning'))}); @endif
        @if(session('info'))    push({type:'info',    message: @js(session('info'))});    @endif
     "
     class="fixed top-4 right-4 z-[60] flex flex-col gap-2 max-w-sm w-[calc(100%-2rem)] sm:w-96 pointer-events-none"
     aria-live="polite" aria-atomic="false">

    <template x-for="t in toasts" :key="t.id">
        <div x-show="t.show"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 translate-x-6"
             x-transition:enter-end="opacity-100 translate-x-0"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 translate-x-0"
             x-transition:leave-end="opacity-0 translate-x-6"
             class="pointer-events-auto relative overflow-hidden rounded-xl bg-white shadow-xl border border-slate-200/70 flex gap-3 p-3.5 pr-4"
             :class="{
                'border-l-4 border-l-emerald-500': t.type === 'success',
                'border-l-4 border-l-rose-500':    t.type === 'error',
                'border-l-4 border-l-amber-500':   t.type === 'warning',
                'border-l-4 border-l-sky-500':     t.type === 'info'
             }">
            {{-- Icon --}}
            <div class="shrink-0 mt-0.5">
                <template x-if="t.type === 'success'">
                    <span class="inline-flex w-8 h-8 rounded-full bg-emerald-100 text-emerald-700 items-center justify-center">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                    </span>
                </template>
                <template x-if="t.type === 'error'">
                    <span class="inline-flex w-8 h-8 rounded-full bg-rose-100 text-rose-700 items-center justify-center">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                    </span>
                </template>
                <template x-if="t.type === 'warning'">
                    <span class="inline-flex w-8 h-8 rounded-full bg-amber-100 text-amber-700 items-center justify-center">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/></svg>
                    </span>
                </template>
                <template x-if="t.type === 'info'">
                    <span class="inline-flex w-8 h-8 rounded-full bg-sky-100 text-sky-700 items-center justify-center">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </span>
                </template>
            </div>

            <div class="flex-1 min-w-0 pt-0.5">
                <p class="text-sm font-semibold text-slate-900" x-text="t.title ?? labelFor(t.type)"></p>
                <p class="text-sm text-slate-600 mt-0.5 leading-snug" x-text="t.message"></p>
            </div>

            <button @click="dismiss(t.id)" class="shrink-0 rounded-md p-1 -m-1 text-slate-400 hover:bg-slate-100 hover:text-slate-700 transition" aria-label="Tutup">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>

            {{-- Auto-dismiss progress bar --}}
            <span class="absolute bottom-0 left-0 h-0.5 bg-slate-200 w-full" aria-hidden="true">
                <span class="block h-full origin-left"
                      :class="{
                        'bg-emerald-500': t.type === 'success',
                        'bg-rose-500':    t.type === 'error',
                        'bg-amber-500':   t.type === 'warning',
                        'bg-sky-500':     t.type === 'info'
                      }"
                      :style="`animation: toast-shrink ${t.duration}ms linear forwards;`"></span>
            </span>
        </div>
    </template>
</div>

<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('toastHost', () => ({
            toasts: [],
            counter: 0,
            push(opts) {
                const id = ++this.counter;
                const toast = {
                    id,
                    type: opts.type || 'info',
                    title: opts.title,
                    message: opts.message ?? '',
                    duration: opts.duration ?? 5000,
                    show: true,
                };
                this.toasts.push(toast);
                setTimeout(() => this.dismiss(id), toast.duration);
            },
            dismiss(id) {
                const t = this.toasts.find(x => x.id === id);
                if (!t) return;
                t.show = false;
                setTimeout(() => { this.toasts = this.toasts.filter(x => x.id !== id); }, 250);
            },
            labelFor(type) {
                return {success:'Berhasil', error:'Gagal', warning:'Perhatian', info:'Informasi'}[type] || 'Notifikasi';
            }
        }));

        // Global helper: window.toast({type, title, message, duration})
        window.toast = (opts) => {
            const host = document.querySelector('[x-data^="toastHost"]')?._x_dataStack?.[0];
            if (host) host.push(opts);
        };
    });
</script>

<style>
    @keyframes toast-shrink {
        from { transform: scaleX(1); }
        to   { transform: scaleX(0); }
    }
</style>
