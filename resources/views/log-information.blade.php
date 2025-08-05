<div style="overflow:hidden; background-color:white; box-shadow:0 1px 3px rgba(0,0,0,0.1);">
    <div style="padding:1rem 1.5rem; background-color:#f9fafb; border-bottom:1px solid #e5e7eb; border-color:#e5e7eb;">
        <h3 style="font-size:0.875rem; font-weight:600; color:#111827;">
            {{ __('filament-log-viewer::log.table.detail.title') }}
        </h3>
    </div>
    <div style="padding:1rem 1.5rem;">
        <div style="display:flex; align-items:center; padding-top:0.75rem; padding-bottom:0.75rem; border-bottom:1px solid #e5e7eb;">
            <div style="font-size:0.875rem; font-weight:500; color:#111827; width:9rem; margin-inline-end:0.75rem;">
                {{ __('filament-log-viewer::log.table.detail.file_path') }}:
            </div>
            <div style="font-size:0.875rem; color:#6b7280;">{{ $data->path() }}</div>
        </div>

        <div style="display:flex; flex-direction:column; justify-content:space-between; padding-top:0.75rem; padding-bottom:0.75rem;">
            <div style="display:flex; align-items:center; padding-top:0.5rem; padding-bottom:0.5rem;">
                <div style="font-size:0.875rem; font-weight:500; color:#111827; width:9rem; margin-inline-end:0.75rem;">
                    {{ __('filament-log-viewer::log.table.detail.log_entries') }}:
                </div>
                <div style="font-size:0.875rem; color:#6b7280;">{{ $data->entries()->count() }}</div>
            </div>
            <div style="display:flex; align-items:center; padding-top:0.5rem; padding-bottom:0.5rem;">
                <div style="font-size:0.875rem; font-weight:500; color:#111827; width:9rem; margin-inline-end:0.75rem;">
                    {{ __('filament-log-viewer::log.table.detail.size') }}:
                </div>
                <div style="font-size:0.875rem; color:#6b7280;">{{ $data->size() }}</div>
            </div>
            <div style="display:flex; align-items:center; padding-top:0.5rem; padding-bottom:0.5rem;">
                <div style="font-size:0.875rem; font-weight:500; color:#111827; width:9rem; margin-inline-end:0.75rem;">
                    {{ __('filament-log-viewer::log.table.detail.created_at') }}:
                </div>
                <div style="font-size:0.875rem; color:#6b7280;">{{ $data->createdAt() }}</div>
            </div>
            <div style="display:flex; align-items:center; padding-top:0.5rem; padding-bottom:0.5rem;">
                <div style="font-size:0.875rem; font-weight:500; color:#111827; width:9rem; margin-inline-end:0.75rem;">
                    {{ __('filament-log-viewer::log.table.detail.updated_at') }}:
                </div>
                <div style="font-size:0.875rem; color:#6b7280;">{{ $data->updatedAt() }}</div>
            </div>
        </div>
    </div>
</div>
<div style="padding-top:0.5rem; padding-bottom:0.5rem; background-color:#f9fafb; border-bottom:1px solid #e5e7eb; border-top:1px solid #e5e7eb;"></div>
