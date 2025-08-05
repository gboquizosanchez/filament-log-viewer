<x-filament-panels::page>
    @if ($this->table->getQuery()->count() > 0)
        @livewire(\Boquizo\FilamentLogViewer\Widgets\StatsOverviewWidget::class)
        @livewire(\Boquizo\FilamentLogViewer\Widgets\IconsWidget::class)
    @endif
    <div class="w-full mt-2">
        {{ $this->table }}
    </div>
</x-filament-panels::page>
