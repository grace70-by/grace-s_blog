@props(['size' => 'md'])

@php
    $sizes = [
        'sm' => 'h-9 w-9',
        'md' => 'h-10 w-10',
        'lg' => 'h-16 w-16',
        'xl' => 'h-20 w-20',
    ];
    $class = $sizes[$size] ?? $sizes['md'];
@endphp

<img src="{{ asset('images/app-icon.png') }}" alt="{{ config('app.name') }}" {{ $attributes->merge(['class' => $class.' rounded-xl object-cover shadow-md']) }}>
