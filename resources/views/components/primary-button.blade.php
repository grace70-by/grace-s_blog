<button {{ $attributes->merge(['type' => 'submit', 'class' => 'btn-ig']) }}>
    {{ $slot }}
</button>
