<button {{ $attributes->merge(['type' => 'submit', 'class' => 'btn btn-danger btn-sm text-uppercase fw-semibold shadow-sm']) }}>
    {{ $slot }}
</button>
