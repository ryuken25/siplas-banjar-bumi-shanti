@props([
    'label' => null,
    'name' => 'foto',
    'accept' => 'image/jpeg,image/png,image/jpg',
    'maxSize' => '2 MB',
    'helper' => null,
    'error' => null,
    'currentUrl' => null,
    'required' => false,
])

@php
    $errorMessage = $error ?? ($name ? $errors->first($name) : null);
    $hasError = ! empty($errorMessage);
    $id = $attributes->get('id') ?: $name . '_input';
@endphp

<div class="w-full"
     x-data="{
        preview: @js($currentUrl),
        fileName: '',
        dragging: false,
        handle(e) {
            const file = e.target.files[0];
            if (!file) return;
            this.fileName = file.name;
            const reader = new FileReader();
            reader.onload = ev => this.preview = ev.target.result;
            reader.readAsDataURL(file);
        },
        drop(e) {
            this.dragging = false;
            const file = e.dataTransfer.files[0];
            if (!file) return;
            const input = document.getElementById('{{ $id }}');
            const dt = new DataTransfer();
            dt.items.add(file);
            input.files = dt.files;
            input.dispatchEvent(new Event('change'));
        },
        clear() {
            this.preview = null;
            this.fileName = '';
            document.getElementById('{{ $id }}').value = '';
        }
     }">

    @if($label)
        <label class="block text-sm font-medium text-slate-700 mb-1.5">
            {{ $label }}
            @if($required) <span class="text-rose-500">*</span> @endif
        </label>
    @endif

    <div :class="dragging ? 'border-primary-500 bg-primary-50' : 'border-slate-300 bg-slate-50'"
         class="relative rounded-xl border-2 border-dashed transition-colors"
         @dragenter.prevent="dragging = true"
         @dragover.prevent="dragging = true"
         @dragleave.prevent="dragging = false"
         @drop.prevent="drop($event)">

        <template x-if="preview">
            <div class="relative p-3">
                <img :src="preview" class="rounded-lg w-full max-h-72 object-cover shadow-md" alt="Pratinjau">
                <button type="button" @click="clear()"
                        class="absolute top-5 right-5 w-9 h-9 rounded-full bg-rose-500 hover:bg-rose-600 text-white shadow-md flex items-center justify-center transition-transform hover:scale-110">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
                <p class="mt-2 text-xs text-slate-600 text-center" x-text="fileName"></p>
            </div>
        </template>

        <template x-if="!preview">
            <label for="{{ $id }}" class="flex flex-col items-center justify-center py-12 px-6 cursor-pointer text-center group">
                <div class="w-14 h-14 rounded-full bg-primary-100 text-primary-600 flex items-center justify-center mb-3 group-hover:scale-110 transition-transform">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5m-13.5-9L12 3m0 0l4.5 4.5M12 3v13.5"/>
                    </svg>
                </div>
                <p class="text-sm font-medium text-slate-700">
                    <span class="text-primary-700">Klik untuk pilih</span>
                    <span class="hidden sm:inline">atau drag & drop foto di sini</span>
                </p>
                <p class="text-xs text-slate-500 mt-1">JPG / PNG, maksimal {{ $maxSize }}</p>
            </label>
        </template>

        <input
            type="file"
            id="{{ $id }}"
            name="{{ $name }}"
            accept="{{ $accept }}"
            @if($required && ! $currentUrl) required @endif
            @change="handle($event)"
            class="sr-only"
        />
    </div>

    @if($hasError)
        <p class="mt-1.5 text-sm text-rose-600 flex items-center gap-1">⚠️ {{ $errorMessage }}</p>
    @elseif($helper)
        <p class="mt-1.5 text-sm text-slate-500">{{ $helper }}</p>
    @endif
</div>
