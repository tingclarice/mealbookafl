<button {{ $attributes->merge(['type' => 'button', 'class' => 'btn btn-warning btn-sm text-uppercase shadow-sm']) }}>
    {{ $slot }}
</button>