<x-filament-panels::page>
    {{-- Page content --}}
    {{ $this->form }}
    <div>
        <div wire:loading.flex
             style="width: 100%; flex-direction: column; align-items: center; justify-content: center; gap: 10px;">

            {{-- Apply spin with inline CSS directly to the component --}}
            <x-uiw-loading style="width: 20px; height: 20px; animation: spin 1s linear infinite;"/>

            <span class="mr-2">جارٍ التحميل ...</span>
        </div>

        <div wire:loading.remove>
            {{ $this->table }}
        </div>
    </div>

    {{-- Inline CSS animation --}}
    <style>
        @keyframes spin {
            from {
                transform: rotate(0deg);
            }
            to {
                transform: rotate(360deg);
            }
        }
    </style>
</x-filament-panels::page>
