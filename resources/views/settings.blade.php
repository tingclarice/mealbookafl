@extends('layouts.app')

@section('content')
<link rel="stylesheet" href="css/settings.css">

<div class="container py-5">
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    
    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if (session('status') === 'profile-updated')
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ __('Profile information updated successfully !!') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row justify-content-center">
        <div class="col-lg-10">
            
            <div class="card shadow-sm border-0 overflow-hidden">
                
                <div class="card-header bg-white border-bottom">
                    <h4 class="mb-0 py-2 fw-bold" style="color: #333;">Account Settings</h4>
                </div>
                


                <div class="card-body p-0">
                    <div class="row g-0" style="min-height: 400px;">
                        
                        <div class="col-md-3 border-end bg-light">
                            <div class="list-group list-group-flush pt-2 settings-menu" id="settings-tab" role="tablist">
                                
                                <a class="list-group-item list-group-item-action active" 
                                   id="v-pills-profile-tab" 
                                   data-bs-toggle="pill" 
                                   href="#v-pills-profile" 
                                   role="tab" 
                                   aria-controls="v-pills-profile" 
                                   aria-selected="true">
                                    <i class="bi bi-person me-2"></i> Profile Information
                                </a>

                                {{-- If user has active shop --}}
                                @if ($activeOwnedShop)
                                    <a class="list-group-item list-group-item-action" 
                                        id="v-pills-seller-tab" 
                                        data-bs-toggle="pill" 
                                        href="#v-pills-seller" 
                                        role="tab" 
                                        aria-controls="v-pills-seller" 
                                        aria-selected="false">
                                            <i class="bi bi-shop me-2"></i> 
                                            Edit Shop Information
                                    </a>
                                @endif
                                
                                <a class="list-group-item list-group-item-action" 
                                   id="v-pills-security-tab" 
                                   data-bs-toggle="pill" 
                                   href="#v-pills-security" 
                                   role="tab" 
                                   aria-controls="v-pills-security" 
                                   aria-selected="false">
                                    <i class="bi bi-shield-lock me-2"></i> Security
                                </a>

                                {{-- If user has pending owned shop or user is not owner or staff --}}
                                @if ($pendingOwnedShop || !Auth::user()->isOwnerOrStaff())
                                    <a class="list-group-item list-group-item-action" 
                                        id="v-pills-seller-tab" 
                                        data-bs-toggle="pill" 
                                        href="#v-pills-seller" 
                                        role="tab" 
                                        aria-controls="v-pills-seller" 
                                        aria-selected="false">
                                            <i class="bi bi-shop me-2"></i> 
                                            Register as Seller
                                    </a>
                                @endif
                                

                                <a class="list-group-item list-group-item-action" 
                                   id="v-pills-danger-tab" 
                                   data-bs-toggle="pill" 
                                   href="#v-pills-danger" 
                                   role="tab" 
                                   aria-controls="v-pills-danger" 
                                   aria-selected="false">
                                    <i class="bi bi-exclamation-triangle me-2"></i> Danger Zone
                                </a>

                            </div>
                        </div>

                        <div class="col-md-9 bg-white">
                            <div class="tab-content p-4 p-md-5" id="v-pills-tabContent">
                                
                                {{-- Profile Information Tab --}}
                                <div class="tab-pane fade show active" id="v-pills-profile" role="tabpanel" aria-labelledby="v-pills-profile-tab">
                                    <h5 class="mb-4 fw-bold" style="color: #333;">Profile Information</h5>
                                    <hr class="mb-4 text-muted">

                                    @include('profile.partials.update-profile-information-form')
                                </div>

                                {{-- Security Tab --}}
                                <div class="tab-pane fade" id="v-pills-security" role="tabpanel" aria-labelledby="v-pills-security-tab">
                                    <h5 class="mb-4 fw-bold" style="color: #333;">Security Settings</h5>
                                    <hr class="mb-4 text-muted">
                                    
                                    @include('profile.partials.update-password-form')
                                </div>

                                <div class="tab-pane fade" id="v-pills-seller" role="tabpanel">
                                    {{-- Show Message info Shops Approvals --}}
                                    @if ($pendingOwnedShop && $pendingOwnedShop->status == "PENDING")
                                        {{-- PENDING STATE UI --}}
                                        <div class="text-center p-5 rounded-4 mt-3" style="background-color: #fff9f7; border: 1px dashed #F97352;">
                                            
                                            {{-- Icon (Sandwich/Clock/Shop Icon) --}}
                                            <div class="mb-3">
                                                <svg width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="#F97352" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                                                    <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
                                                    <circle cx="12" cy="11" r="3"/>
                                                    <path d="M12 11v-2"/>
                                                    <path d="M12 11h2"/>
                                                </svg>
                                            </div>

                                            {{-- Message --}}
                                            <h4 class="fw-bold mb-2" style="color: #333;">Application Under Review</h4>
                                            <p class="text-muted mb-4" style="max-width: 400px; margin: 0 auto;">
                                                We have received your request to open <strong>{{ $pendingOwnedShop->name }}</strong>. 
                                                <br>
                                                Your request is currently under review. We will let you know as soon as your shop is approved!
                                            </p>

                                            {{-- Optional: Cancel Button --}}
                                            {{-- 
                                            <button class="btn btn-outline-danger btn-sm rounded-pill px-4">
                                                Cancel Request
                                            </button> 
                                            --}}
                                        </div>
                                    @elseif ($pendingOwnedShop && $pendingOwnedShop->status == "REJECTED")
                                        <div class="text-center p-5 rounded-4 mt-3" style="background-color: #fff5f5; border: 1px dashed #dc3545;">
        
                                            {{-- Icon (X Circle) --}}
                                            <div class="mb-3">
                                                <svg width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="#dc3545" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                                                    <circle cx="12" cy="12" r="10"></circle>
                                                    <line x1="15" y1="9" x2="9" y2="15"></line>
                                                    <line x1="9" y1="9" x2="15" y2="15"></line>
                                                </svg>
                                            </div>

                                            {{-- Message --}}
                                            <h4 class="fw-bold mb-2" style="color: #dc3545;">Application Rejected</h4>
                                            <p class="text-muted mb-4" style="max-width: 450px; margin: 0 auto;">
                                                Unfortunately, your request to open <strong>{{ $pendingOwnedShop->name }}</strong> was not approved.
                                                <br>
                                                Please contact the administrator if you believe this is a mistake or to discuss further steps.
                                            </p>

                                        </div>
                                    @endif
                                
                                    {{-- Register as Seller; if user is not owner or staff --}}
                                    @unless(Auth::user()->isOwnerOrStaff())
                                        {{-- REGISTRATION FORM (Only shows if NO pending shop) --}}
                                        <h5 class="mb-4 fw-bold" style="color: #333;">Register as Seller</h5>
                                        <hr class="mb-4 text-muted" style="opacity: 0.1;">

                                        <form action="{{ route('shops.request') }}" method="POST" enctype="multipart/form-data">
                                            @csrf

                                            {{-- Shop Image --}}
                                            <div class="mb-3">
                                                <label class="form-label fw-semibold small text-muted text-uppercase">Shop Image</label>
                                                <input type="file" class="form-control" name="profileImage" required>
                                            </div>

                                            {{-- Shop Name --}}
                                            <div class="mb-3">
                                                <label class="form-label fw-semibold small text-muted text-uppercase">Shop Name</label>
                                                <input type="text" class="form-control" name="name" placeholder="Enter shop name" required>
                                            </div>

                                            {{-- Address --}}
                                            <div class="mb-3">
                                                <label class="form-label fw-semibold small text-muted text-uppercase">Address</label>
                                                <textarea class="form-control" rows="2" name="address" placeholder="Enter shop address" required></textarea>
                                            </div>

                                            {{-- Phone --}}
                                            <div class="mb-3">
                                                <label class="form-label fw-semibold small text-muted text-uppercase">Phone</label>
                                                <input type="text" class="form-control" name="phone" placeholder="08xxxxx" required>
                                            </div>

                                            {{-- Description --}}
                                            <div class="mb-4">
                                                <label class="form-label fw-semibold small text-muted text-uppercase">Description</label>
                                                <textarea class="form-control" rows="3" name="description" placeholder="Tell us about your shop..." required></textarea>
                                            </div>

                                            {{-- Submit --}}
                                            <div class="d-grid">
                                                <button type="submit" 
                                                        class="btn text-white fw-bold py-2" 
                                                        style="background-color: #F97352; border-radius: 8px;">
                                                    Submit Request
                                                </button>
                                            </div>
                                        </form>
                                        
                                    @endunless

                                    {{-- Edit Shop Information; if user is shops owner --}}
                                    @if ($activeOwnedShop)
                                        <h5 class="mb-4 fw-bold" style="color: #333;">Shop Information</h5>
                                        <hr class="mb-4 text-muted" style="opacity: 0.1;">
                                        @include('profile.partials.edit-shop')
                                    @endif

                                </div>
                            

                                {{-- Danger Zone Tab --}}
                                <div class="tab-pane fade" id="v-pills-danger" role="tabpanel" aria-labelledby="v-pills-danger-tab">
                                    <h5 class="mb-4 fw-bold text-danger" style="color: #dc3545;">Danger Zone</h5>
                                    <hr class="mb-4 text-muted">
                                    
                                    @include('profile.partials.delete-user-form')
                                </div>

                            </div>
                        </div> 
                        
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection