<div class="filament-tables-table overflow-hidden bg-white dark:bg-gray-900 shadow sm:rounded-lg">
    <div class="px-6 py-4 bg-gray-50 dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700 divide-y divide-gray-200 dark:divide-gray-700">
        <h3 class="text-md font-semibold text-gray-900 dark:text-gray-100">
            {{ __('filament-log-viewer::log.table.detail.title') }}
        </h3>
    </div>
    <div class="px-6 py-4">
        <div class="flex items-center py-3 border-b border-gray-200 dark:border-gray-700">
            <div class="text-sm font-medium text-gray-900 dark:text-gray-100 w-36 me-3">
                {{ __('filament-log-viewer::log.table.detail.file_path') }}:
            </div>
            <div class="text-sm text-gray-500 dark:text-gray-400">{{ $data->path() }}</div>
        </div>

        <div class="flex flex-col lg:flex-row justify-between py-3">
            <div class="flex items-center py-2 lg:py-0">
                <div class="text-sm font-medium text-gray-900 dark:text-gray-100 w-36 me-3">
                    {{ __('filament-log-viewer::log.table.detail.log_entries') }}:
                </div>
                <div class="text-sm text-gray-500 dark:text-gray-400">{{ $data->entries()->count() }}</div>
            </div>
            <div class="flex items-center py-2 sm:py-0">
                <div class="text-sm font-medium text-gray-900 dark:text-gray-100 w-36 me-3">
                    {{ __('filament-log-viewer::log.table.detail.size') }}:
                </div>
                <div class="text-sm text-gray-500 dark:text-gray-400">{{ $data->size() }}</div>
            </div>
            <div class="flex items-center py-2 sm:py-0">
                <div class="text-sm font-medium text-gray-900 dark:text-gray-100 w-36 me-3">
                    {{ __('filament-log-viewer::log.table.detail.created_at') }}:
                </div>
                <div class="text-sm text-gray-500 dark:text-gray-400">{{ $data->createdAt() }}</div>
            </div>
            <div class="flex items-center py-2 sm:py-0">
                <div class="text-sm font-medium text-gray-900 dark:text-gray-100 w-36 me-3">
                    {{ __('filament-log-viewer::log.table.detail.updated_at') }}:
                </div>
                <div class="text-sm text-gray-500 dark:text-gray-400">{{ $data->updatedAt() }}</div>
            </div>
        </div>
    </div>
</div>
<div class="py-2 bg-gray-50 dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700 divide-y divide-gray-200 dark:divide-gray-700">
</div>
