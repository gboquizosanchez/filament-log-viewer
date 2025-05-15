<x-filament-panels::page>
    @if ($this->table->getQuery()->count() > 0)
        <div class="w-full">
            <div class="flex flex-col 2xl:flex-row gap-6">
                <div class="2xl:w-1/3 flex items-center justify-center">
                    <div class="w-full max-w-sm">
                        @livewire(\Boquizo\FilamentLogViewer\Widgets\StatsOverviewWidget::class)
                    </div>
                </div>

                <div class="2xl:w-2/3 w-full">
                    @livewire(\Boquizo\FilamentLogViewer\Widgets\IconsWidget::class)
                </div>
            </div>
        </div>
    @endif
    <div class="w-full mt-2">
        {{ $this->table }}
    </div>
</x-filament-panels::page>
