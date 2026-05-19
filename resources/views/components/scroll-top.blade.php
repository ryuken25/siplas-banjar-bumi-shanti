<div x-data="{ visible: false }"
     x-init="
        window.addEventListener('scroll', () => { visible = window.scrollY > 480 }, { passive: true });
     "
     x-show="visible" x-cloak
     x-transition:enter="transition ease-out duration-200"
     x-transition:enter-start="opacity-0 translate-y-2"
     x-transition:enter-end="opacity-100 translate-y-0"
     x-transition:leave="transition ease-in duration-150"
     x-transition:leave-start="opacity-100"
     x-transition:leave-end="opacity-0"
     class="fixed bottom-6 right-6 z-30">
    <button @click="window.scrollTo({ top: 0, behavior: 'smooth' })"
            aria-label="Kembali ke atas"
            class="group w-12 h-12 rounded-full bg-white border border-slate-200 shadow-lg flex items-center justify-center hover:shadow-xl hover:-translate-y-0.5 transition-all duration-200 focus:outline-none focus-visible:ring-4 focus-visible:ring-primary-500/30">
        <svg class="w-5 h-5 text-slate-600 group-hover:text-primary-600 transition" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M5 15l7-7 7 7"/>
        </svg>
    </button>
</div>
