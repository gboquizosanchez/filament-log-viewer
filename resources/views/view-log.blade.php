<x-filament-panels::page>
    <div class="w-full">
        <div class="flex flex-col 2xl:flex-row gap-6">
            <div class="2xl:w-1/3 flex items-center justify-center">

            </div>
        </div>
    </div>
    <div class="flex flex-col gap-y-6">
        <x-filament-panels::resources.tabs />

        {{ $this->table }}
    </div>
</x-filament-panels::page>
