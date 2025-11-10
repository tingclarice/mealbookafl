@extends('layouts.guest')

@section('title', 'Register')

@section('content')
<section class="d-flex align-items-center justify-content-center" style="min-height: 80vh; background-color: #FFF9F7;">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-5">
                <div class="card border-0 shadow-sm" style="border-radius: 25px;">
                    <div class="card-body p-5">
                        {{-- Logo/Title --}}
                        <h2 class="text-center mb-4" style="font-family: 'Pacifico'; color: #F97352;">
                            Join MealBook!
                        </h2>
                        <p class="text-center text-muted mb-4">Create your account to get started</p>

                        {{-- Register Form --}}
                        <form method="POST" action="{{ route('register') }}">
                            @csrf

                            {{-- Name --}}
                            <div class="mb-3">
                                <label for="name" class="form-label fw-bold" style="color: #1E293B;">Name</label>
                                <input type="text" 
                                        class="form-control @error('name') is-invalid @enderror" 
                                        id="name" 
                                        name="name" 
                                        value="{{ old('name') }}"
                                        required 
                                        autofocus
                                        style="border-radius: 15px; padding: 12px;">
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Email --}}
                            <div class="mb-3">
                                <label for="email" class="form-label fw-bold" style="color: #1E293B;">Email</label>
                                <input type="email" 
                                        class="form-control @error('email') is-invalid @enderror" 
                                        id="email" 
                                        name="email" 
                                        value="{{ old('email') }}"
                                        required
                                        style="border-radius: 15px; padding: 12px;">
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Password --}}
                            <div class="mb-3">
                                <label for="password" class="form-label fw-bold" style="color: #1E293B;">Password</label>
                                <input type="password" 
                                        class="form-control @error('password') is-invalid @enderror" 
                                        id="password" 
                                        name="password" 
                                        required
                                        style="border-radius: 15px; padding: 12px;">
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">At least 8 characters</small>
                            </div>

                            {{-- Confirm Password --}}
                            <div class="mb-4">
                                <label for="password_confirmation" class="form-label fw-bold" style="color: #1E293B;">Confirm Password</label>
                                <input type="password" 
                                        class="form-control" 
                                        id="password_confirmation" 
                                        name="password_confirmation" 
                                        required
                                        style="border-radius: 15px; padding: 12px;">
                            </div>

                            {{-- Submit Button --}}
                            <button type="submit" class="btn w-100 py-3 fw-bold mb-3" 
                                    style="background-color: #F97352; color: white; border-radius: 15px; border: none;">
                                Create Account
                            </button>
                        </form>

                        {{-- Divider --}}
                        <div class="d-flex align-items-center my-4">
                            <hr class="flex-grow-1">
                            <span class="px-3 text-muted">OR</span>
                            <hr class="flex-grow-1">
                        </div>

                        {{-- Google Sign Up --}}
                        <a href="{{ route('google.login') }}" class="btn btn-outline-dark w-100 py-3 fw-bold mb-3" 
                            style="border-radius: 15px; border: 2px solid #1E293B;">
                            <i class="bi bi-google me-2"></i>
                            Continue with Google
                        </a>

                        {{-- Login Link --}}
                        <p class="text-center mb-0 mt-4" style="color: #64748B;">
                            Already have an account? 
                            <a href="{{ route('login') }}" class="fw-bold text-decoration-none" style="color: #F97352;">
                                Log In
                            </a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection