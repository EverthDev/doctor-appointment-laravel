@props(['tab'])

<div x-show="tab === '{{ $tab }}'" style="display: none" x-cloak>
    {{ $slot }}
</div>