@props(['user', 'size' => 'md'])

@php
    $sizes = [
        'sm' => ['wrap' => 'h-8 w-8', 'text' => 'text-xs'],
        'md' => ['wrap' => 'h-10 w-10', 'text' => 'text-sm'],
        'lg' => ['wrap' => 'h-12 w-12', 'text' => 'text-base'],
    ];
    $s = $sizes[$size] ?? $sizes['md'];
    $initial = strtoupper(mb_substr($user->name ?? '?', 0, 1));
@endphp

<div class="avatar-ring {{ $s['wrap'] }}">
    <div class="avatar-inner {{ $s['wrap'] }} {{ $s['text'] }}">
        {{ $initial }}
    </div>
</div>
