@extends('layouts.app')

@section('content')
{{-- Hero Section --}}
<section class="text-center text-white d-flex align-items-center justify-content-center"
    style="background: linear-gradient(135deg, #F97352 0%, #ff8c6b 100%); min-height: 30vh;">
    <div>
        <h1 class="mb-2" style="font-family: 'Potta One', sans-serif; font-size: 2.5rem;">My Profile</h1>
        <p class="lead">Manage your account settings and preferences</p>
    </div>
</section>

<div class="container py-5" style="background-color: #FEF3F0; min-height: 70vh;">
    <div class="row justify-content-center">
        <div class="col-lg-10">

            {{-- Profile Information Card --}}
            <div class="card border-0 shadow-sm mb-4" style="border-radius: 20px; overflow: hidden;">
                <div class="card-header d-flex align-items-center" style="background-color: #F97352; color: white; padding: 1.5rem;">
                    <i class="bi bi-person-circle fs-3 me-3"></i>
                    <div>
                        <h5 class="fw-bold mb-0">Profile Information</h5>
                        <small class="opacity-75">Update your account details</small>
                    </div>
                </div>
                <div class="card-body p-4" style="background-color: white;">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>

            {{-- Update Password Card --}}
            <div class="card border-0 shadow-sm mb-4" style="border-radius: 20px; overflow: hidden;">
                <div class="card-header d-flex align-items-center" style="background-color: #F97352; color: white; padding: 1.5rem;">
                    <i class="bi bi-shield-lock fs-3 me-3"></i>
                    <div>
                        <h5 class="fw-bold mb-0">Update Password</h5>
                        <small class="opacity-75">Keep your account secure</small>
                    </div>
                </div>
                <div class="card-body p-4" style="background-color: white;">
                    @include('profile.partials.update-password-form')
                </div>
            </div>

            {{-- Delete Account Card --}}
            <div class="card border-0 shadow-sm" style="border-radius: 20px; overflow: hidden;">
                <div class="card-header d-flex align-items-center" style="background-color: #dc3545; color: white; padding: 1.5rem;">
                    <i class="bi bi-exclamation-triangle fs-3 me-3"></i>
                    <div>
                        <h5 class="fw-bold mb-0">Danger Zone</h5>
                        <small class="opacity-75">Permanently delete your account</small>
                    </div>
                </div>
                <div class="card-body p-4" style="background-color: white;">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>

        </div>
    </div>
</div>
@endsection