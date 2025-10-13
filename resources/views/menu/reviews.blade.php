@extends('layouts.app')

@section('head')
<link rel="stylesheet" href="{{ asset('css/reviews.css') }}">
@endsection

@section('content')
<div class="container my-5">

    {{-- Section 1: Meal & Rating Summary --}}
    <div class="card mb-4">
        <div class="card-body p-4">
            <div class="row align-items-center">
                {{-- Meal Image --}}
                <div class="col-md-3 text-center text-md-start mb-3 mb-md-0">
                    <img src="{{ asset($meal->image_url) }}" alt="{{ $meal->name }}" class="meal-review-img">
                </div>
                {{-- Meal Name & Average Rating --}}
                <div class="col-md-9">
                    <h1 class="fw-bold">{{ $meal->name }}</h1>
                    <p class="text-muted">{{ $meal->description }}</p>

                    @if ($reviewCount > 0)
                        <div class="d-flex align-items-center">
                            <h2 class="fw-bolder me-3 mb-0">{{ $averageRating }}</h2>
                            <div class="stars fs-4 me-3">
                                {{-- Logic to display fractional stars --}}
                                @for ($i = 1; $i <= 5; $i++)
                                    @if ($i <= $averageRating)
                                        <i class="bi bi-star-fill"></i>
                                    @elseif ($i - 0.5 <= $averageRating)
                                        <i class="bi bi-star-half"></i>
                                    @else
                                        <i class="bi bi-star"></i>
                                    @endif
                                @endfor
                            </div>
                            <span class="text-muted">Based on {{ $reviewCount }} reviews</span>
                        </div>
                    @else
                        <p class="text-muted">No reviews yet.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- Section 2: List of All Reviews --}}
    <div class="card">
        <div class="card-header bg-white border-0 pt-4 px-4">
            <h4 class="fw-bold">All Reviews</h4>
        </div>
        <div class="card-body p-0">
            @forelse ($reviews as $review)
                <div class="review-item p-4">
                    <div class="d-flex mb-2">
                        <div class="flex-grow-1">
                            <h6 class="fw-bold mb-0">{{ $review->user->name }}</h6>
                            <small class="text-muted">{{ $review->created_at->diffForHumans() }}</small>
                        </div>
                        <div class="stars">
                            {{-- Star rating for this specific review --}}
                            @for ($i = 0; $i < 5; $i++)
                                <i class="bi {{ $i < $review->rate ? 'bi-star-fill' : 'bi-star' }}"></i>
                            @endfor
                        </div>
                    </div>
                    <p class="mb-0">{{ $review->message }}</p>
                </div>
            @empty
                <div class="p-4 text-center">
                    <p class="mb-0">Be the first to leave a review for this meal!</p>
                </div>
            @endforelse
        </div>
    </div>
</div>
@endsection