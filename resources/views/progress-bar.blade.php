@php
    $blankSpace = str_repeat(' ', 40);
@endphp
<div style="margin-top: 8px;">
    <div style="align-items: center; font-size: 12px; margin-bottom: 8px;">
        <span style="width: 100%; font-weight: 700; color: #1f2937;">{{ $percent }}% {!! $blankSpace !!}</span>
    </div>
    <div style="width: 100%; background-color: #e5e7eb; height: 12px; border-radius: 6px; overflow: hidden; box-shadow: inset 0 1px 2px rgba(0, 0, 0, 0.1);">
        <div style="width: {{ $percent }}%; background-color: {{ $progressColor }}; height: 100%; transition: width 0.4s cubic-bezier(0.4, 0, 0.2, 1); border-radius: 6px;">
        </div>
    </div>
</div>
