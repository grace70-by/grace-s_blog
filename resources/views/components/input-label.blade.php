@props(['value'])

<label {{ $attributes->merge(['class' => 'ig-label']) }}>
    {{ $value ?? $slot }}
</label>
