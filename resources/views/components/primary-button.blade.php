<button {{ $attributes->merge(['type' => 'submit', 'class' => 'btn btn-warning btn-sm text-uppercase shadow-sm']) }}>
    {{ $slot }}
</button>