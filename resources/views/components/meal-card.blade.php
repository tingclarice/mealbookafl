<div class="card shadow-sm border-0 h-md-100 h-80" style="border-radius: 20px;">
    <a href="{{ route('menu.show', $meal->id) }}" class="text-decoration-none">
        <img src="{{ asset($meal->image_url) }}" class="card-img-top" alt="{{ $meal->name }}" 
            style="border-top-left-radius: 20px; border-top-right-radius: 20px; height: 200px; object-fit: cover;">
    </a>
    <div class="card-body d-flex flex-column">
        <a href="{{ route('menu.show', $meal->id) }}" class="text-decoration-none">
            <h5 class="card-title mb-1" style="color: #1E293B;">{{ $meal->name }}</h5>
        </a>
        <p class="text-muted mb-2 flex-grow-1" style="font-size: 0.85rem;">
            {{ $meal->short_description }}
        </p>
        <p class="text-muted mb-3 fw-bold">{{ $meal->formatted_price }}</p>
        <a href="{{ route('menu.show', $meal->id) }}" class="btn w-100 fw-bold text-decoration-none" 
            style="background-color: #F97352; color: white; border-radius: 15px; border: none; padding: 8px 0;">
            Lihat Detail
        </a>
    </div>
</div>