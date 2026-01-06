@extends('layouts.guest')

@section('title', 'Login')

@section('content')
<section class="d-flex align-items-center justify-content-center" style="min-height: auto; padding-top: 20px; padding-bottom: 30px; background-color: #FFF9F7;">
    <div class="container mt-3">
        <div class="row justify-content-center">
            <div class="col-md-4">
                <div class="card border-0 shadow-sm" style="border-radius: 25px;">
                    <div class="card-body p-4">
                        {{-- Logo/Title --}}
                        <h2 class="text-center mb-4" style="font-family: 'Pacifico'; color: #F97352;">
                            Welcome Back!
                        </h2>
                        <p class="text-center text-muted mb-4">Login to your MealBook account</p>

                        {{-- Session Status --}}
                        @if (session('status'))
                            <div class="alert alert-success mb-4" role="alert">
                                {{ session('status') }}
                            </div>
                        @endif

                        {{-- Login Form --}}
                        <form method="POST" action="{{ route('login') }}">
                            @csrf

                            {{-- Email --}}
                            <div class="mb-3">
                                <label for="email" class="form-label fw-bold" style="color: #1E293B;">Email</label>
                                <input type="email" 
                                        class="form-control @error('email') is-invalid @enderror" 
                                        id="email" 
                                        name="email" 
                                        value="{{ old('email') }}"
                                        required 
                                        autofocus
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
                            </div>

                            {{-- Remember Me --}}
                            <div class="form-check mb-4">
                                <input class="form-check-input" type="checkbox" id="remember_me" name="remember">
                                <label class="form-check-label" for="remember_me" style="color: #64748B;">
                                    Remember me
                                </label>
                            </div>

                            {{-- Submit Button --}}
                            <button type="submit" class="btn w-100 py-3 fw-bold mb-3" 
                                    style="background-color: #F97352; color: white; border-radius: 15px; border: none;">
                                Log In
                            </button>

                            {{-- Forgot Password --}}
                            @if (Route::has('password.request'))
                                <div class="text-center mb-3">
                                    <a href="{{ route('password.request') }}" class="text-decoration-none" style="color: #F97352;">
                                        Forgot your password?
                                    </a>
                                </div>
                            @endif
                        </form>

                        {{-- Divider --}}
                        <div class="d-flex align-items-center my-4">
                            <hr class="flex-grow-1">
                            <span class="px-3 text-muted">OR</span>
                            <hr class="flex-grow-1">
                        </div>

                        {{-- Google Login --}}
                        <a href="{{ route('google.login') }}" class="btn btn-outline-dark w-100 py-3 fw-bold mb-3" 
                            style="border-radius: 15px; border: 2px solid #1E293B;">
                            <i class="bi bi-google me-2"></i>
                            Continue with Google
                        </a>

                        {{-- Register Link --}}
                        <p class="text-center mb-0 mt-4" style="color: #64748B;">
                            Don't have an account? 
                            <a href="{{ route('register') }}" class="fw-bold text-decoration-none" style="color: #F97352;">
                                Sign Up
                            </a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection