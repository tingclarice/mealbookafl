@extends('layouts.app')

@section('content')
<section class="py-5" style="background-color: #FFF9F7; min-height: 100vh;">
    <div class="container">
        {{-- Back Button --}}
        <a href="{{ route('home') }}#menu" class="btn mb-4 d-inline-flex align-items-center" 
            style="background: none; border: none; color: #4B205F; font-size: 1.25rem; padding: 0;">
            <svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M15 18l-6-6 6-6"/>
            </svg>
        </a>

        {{-- Meal Title & Price --}}
        <div class="text-center mb-5">
            <h1 style="font-family: 'Preahvihear', sans-serif; font-weight: 600; color: #2D114B; font-size: 2rem;">
                {{ $meal->name }}
            </h1>
            <p style="color: #2D114B; font-size: 1.25rem; margin-bottom: 0;">
                {{ $meal->formatted_price }}
            </p>
        </div>

        {{-- Image & Description --}}
        <div class="row justify-content-center align-items-center g-5">
            <div class="col-md-5 text-center">
                <img src="{{ asset($meal->image_url) }}" 
                        alt="{{ $meal->name }}" 
                        style="border-radius: 25px; width: 100%; max-width: 400px; height: auto; object-fit: cover;">
            </div>

            <div class="col-md-6">
                <h4 style="font-family: 'Preahvihear', sans-serif; font-weight: 600; color: #2D114B; font-size: 1.25rem;">
                    Description
                </h4>
                <p style="color: #4A3763; font-size: 1rem; line-height: 1.7; margin-top: 10px;">
                    {{ $meal->description }}
                </p>
                <button class="btn mt-4 px-5 py-3 fw-bold"
                        style="background-color: #2D114B; color: #fff; border: none; border-radius: 25px; font-size: 1rem;">
                    Add to Cart
                </button>
            </div>
        </div>

        {{-- Reviews Section --}}
        <div class="mt-5 pt-4 border-top">
            {{-- Section Header with Average Rating --}}
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h4 style="font-family: 'Preahvihear', sans-serif; font-weight: 600; color: #2D114B; margin-bottom: 0;">
                    Reviews
                </h4>
                @if ($reviewCount > 0)
                <div class="d-flex align-items-center">
                    <span class="fw-bold me-2" style="color: #2D114B;">{{ $averageRating }}</span>
                    <div style="color: #ffc107;"> @for ($i = 1; $i <= 5; $i++)
                            @if ($i <= $averageRating) <i class="bi bi-star-fill"></i>
                            @elseif ($i - 0.5 <= $averageRating) <i class="bi bi-star-half"></i>
                            @else <i class="bi bi-star"></i>
                            @endif
                        @endfor
                    </div>
                    <span class="ms-2 text-muted small">({{ $reviewCount }} reviews)</span>
                </div>
                @endif
            </div>

            {{-- Loop to display the 2 latest reviews --}}
            @forelse($latestReviews as $review)
                <div class="card mb-3 border-0" style="background-color: #fff; border-radius: 15px;">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <h6 class="fw-bold" style="color: #2D114B;">{{ $review->user->name }}</h6>
                            <div style="color: #ffc107;">
                                {{-- Star rating for this specific review --}}
                                @for ($i = 0; $i < 5; $i++)
                                    <i class="bi {{ $i < $review->rate ? 'bi-star-fill' : 'bi-star' }}"></i>
                                @endfor
                            </div>
                        </div>
                        <p class="mb-0" style="color: #4A3763;">{{ $review->message }}</p>
                    </div>
                </div>
            @empty
                <p class="text-muted text-center">No reviews for this meal yet.</p>
            @endforelse

            {{-- "Show all reviews" button (only appears if there are more than 2 reviews) --}}
            @if($reviewCount > 2)
                <div class="text-center mt-4">
                    <a href="{{ route('menu.reviews', $meal->id) }}" class="btn px-4 py-2" 
                    style="background-color: transparent; color: #2D114B; border: 1px solid #2D114B; border-radius: 25px;">
                        Show all reviews
                    </a>
                </div>
            @endif
        </div>

        {{-- Suggested Section --}}
        <div class="mt-5">
            <h3 class="mb-4" style="font-family: 'Preahvihear', sans-serif; color: #2D114B; font-weight: 600;">
                Suggested for you
            </h3>
            <div class="d-flex flex-wrap gap-4 justify-content-start">
                @foreach($suggestedMeals as $suggested)
                    <a href="{{ route('menu.show', $suggested->id) }}" class="text-decoration-none">
                        <div class="card border-0 text-center"
                                style="width: 180px; border-radius: 25px; background-color: #fff; 
                                    box-shadow: 0 4px 8px rgba(0,0,0,0.05);">
                            <img src="{{ asset($suggested->image_url) }}" 
                                    alt="{{ $suggested->name }}" 
                                    style="border-top-left-radius: 25px; border-top-right-radius: 25px; 
                                            width: 100%; height: 140px; object-fit: cover;">
                            <div class="p-3">
                                <h6 style="color: #2D114B; font-size: 0.95rem; margin-bottom: 5px;">
                                    {{ $suggested->name }}
                                </h6>
                                <p style="color: #4A3763; font-size: 0.9rem; margin: 0;">
                                    {{ $meal->formatted_price }}
                                </p>
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    </div>
</section>
@endsection
