@props(['size' => 'md', 'text' => ''])

@php
$sizeClasses = [
    'sm' => 'w-4 h-4',
    'md' => 'w-8 h-8',
    'lg' => 'w-12 h-12',
    'xl' => 'w-16 h-16',
];
$sizeClass = $sizeClasses[$size] ?? $sizeClasses['md'];
@endphp

<div class="flex items-center justify-center {{ $attributes->get('class') }}">
    <div class="relative">
        <div class="{{ $sizeClass }} border-4 border-gray-200 border-t-blue-600 rounded-full animate-spin"></div>
        @if($text)
            <p class="mt-2 text-sm text-gray-600 text-center">{{ $text }}</p>
        @endif
    </div>
</div>

