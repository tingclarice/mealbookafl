<button {{ $attributes->merge([
    'type' => 'submit',
    'class' => 'btn btn-sm text-uppercase shadow-sm',
    'style' => 'background-color: #FB7C5B; color: white; border: none; margin-top:10px'
]) }}>
    {{ $slot }}
</button>