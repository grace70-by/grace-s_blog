@props([
    'title' => config('app.name'),
    'description' => null,
    'image' => null,
    'url' => null,
    'type' => 'website',
])

@php
    $description = $description ?: config('app.name').' — Blog et communauté';
    $url = $url ?: url()->current();
    $image = $image ?: asset('images/app-icon.png');
@endphp

<meta name="description" content="{{ $description }}">
<link rel="canonical" href="{{ $url }}">

<meta property="og:type" content="{{ $type }}">
<meta property="og:title" content="{{ $title }}">
<meta property="og:description" content="{{ $description }}">
<meta property="og:url" content="{{ $url }}">
<meta property="og:image" content="{{ $image }}">
<meta property="og:locale" content="fr_FR">
<meta property="og:site_name" content="{{ config('app.name') }}">

<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="{{ $title }}">
<meta name="twitter:description" content="{{ $description }}">
<meta name="twitter:image" content="{{ $image }}">
