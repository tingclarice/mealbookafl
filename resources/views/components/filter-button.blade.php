<a href="{{ $category ? route('menu', ['category' => $category]) : route('menu') }}" 
    class="btn px-4 py-2 fw-bold {{ $isActive ? 'active' : '' }}"
    style="background-color: {{ $isActive ? '#F97352' : '#fff' }}; 
            color: {{ $isActive ? '#fff' : '#1E293B' }}; 
            border-radius: 25px; 
            border: 2px solid #F97352;">
    {{ $label }}
</a>