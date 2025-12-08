@extends('layouts.app')

@section('content')
<link rel="stylesheet" href="css/settings.css">

{{-- Hero Section --}}
<section class="text-center text-white d-flex align-items-center justify-content-center"
    style="background: linear-gradient(135deg, #F97352 0%, #ff8c6b 100%); min-height: 30vh;">
    <div>
        <h1 class="mb-2" style="font-family: 'Potta One', sans-serif; font-size: 2.5rem;">Settings</h1>
        <p class="lead">Manage your account and preferences</p>
    </div>
</section>

<div class="container py-5" style="background-color: #FEF3F0; min-height: 70vh;">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            
            <div class="card shadow-sm border-0 overflow-hidden">
                
                <div class="card-header bg-white border-bottom">
                    <h4 class="mb-0 py-2 fw-bold" style="color: #333;">Account Settings</h4>
                </div>

                <div class="card-body p-0">
                    <div class="row g-0" style="min-height: 400px;">
                        
                        {{-- Sidebar Menu --}}
                        <div class="col-md-3 border-end bg-light">
                            <div class="list-group list-group-flush pt-2 settings-menu" id="settings-tab" role="tablist">
                                
                                {{-- Profile Information (All Users) --}}
                                <a class="list-group-item list-group-item-action active" 
                                   id="v-pills-profile-tab" 
                                   data-bs-toggle="pill" 
                                   href="#v-pills-profile" 
                                   role="tab" 
                                   aria-controls="v-pills-profile" 
                                   aria-selected="true">
                                    <i class="bi bi-person me-2"></i> Profile Information
                                </a>
                                
                                {{-- Security (All Users) --}}
                                <a class="list-group-item list-group-item-action" 
                                   id="v-pills-security-tab" 
                                   data-bs-toggle="pill" 
                                   href="#v-pills-security" 
                                   role="tab" 
                                   aria-controls="v-pills-security" 
                                   aria-selected="false">
                                    <i class="bi bi-shield-lock me-2"></i> Security
                                </a>

                                {{-- Register as Seller (Regular Users Only) --}}
                                @if(!Auth::user()->shops()->exists())
                                    <a class="list-group-item list-group-item-action" 
                                       id="v-pills-seller-tab" 
                                       data-bs-toggle="pill" 
                                       href="#v-pills-seller" 
                                       role="tab" 
                                       aria-controls="v-pills-seller" 
                                       aria-selected="false">
                                        <i class="bi bi-shop me-2"></i> Register as Seller
                                    </a>
                                @endif

                                {{-- Shop Information (Shop Owners Only) --}}
                                @php
                                    $userShops = Auth::user()->shops;
                                    $ownedShops = $userShops->filter(fn($shop) => $shop->pivot->role === 'OWNER');
                                @endphp

                                @if($ownedShops->isNotEmpty())
                                    <a class="list-group-item list-group-item-action" 
                                       id="v-pills-shop-tab" 
                                       data-bs-toggle="pill" 
                                       href="#v-pills-shop" 
                                       role="tab" 
                                       aria-controls="v-pills-shop" 
                                       aria-selected="false">
                                        <i class="bi bi-shop me-2"></i> Shop Information
                                    </a>
                                @endif

                                {{-- Notifications (Shop Owners & Staff) --}}
                                @if(Auth::user()->shops()->exists())
                                    <a class="list-group-item list-group-item-action" 
                                       id="v-pills-notifications-tab" 
                                       data-bs-toggle="pill" 
                                       href="#v-pills-notifications" 
                                       role="tab" 
                                       aria-controls="v-pills-notifications" 
                                       aria-selected="false">
                                        <i class="bi bi-bell me-2"></i> Notifications
                                    </a>
                                @endif

                                {{-- Danger Zone (All Users) --}}
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

                        {{-- Tab Content --}}
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

                                {{-- Register as Seller Tab (Regular Users Only) --}}
                                @if(!Auth::user()->shops()->exists())
                                    <div class="tab-pane fade" id="v-pills-seller" role="tabpanel" aria-labelledby="v-pills-seller-tab">
                                        <h5 class="mb-4 fw-bold" style="color: #333;">Register as Seller</h5>
                                        <hr class="mb-4 text-muted">
                                        
                                        <p class="text-muted mb-4">Create your shop and start selling on MealBook!</p>

                                        <form method="POST" action="{{ route('shop.register') }}" enctype="multipart/form-data">
                                            @csrf
                                            
                                            <div class="mb-3">
                                                <label for="shop_name" class="form-label">Shop Name</label>
                                                <input type="text" class="form-control" id="shop_name" name="name" required>
                                            </div>

                                            <div class="mb-3">
                                                <label for="shop_address" class="form-label">Address</label>
                                                <textarea class="form-control" id="shop_address" name="address" rows="3" required></textarea>
                                            </div>

                                            <div class="mb-3">
                                                <label for="shop_phone" class="form-label">Phone Number</label>
                                                <input type="tel" class="form-control" id="shop_phone" name="phone" required>
                                            </div>

                                            <div class="mb-3">
                                                <label for="shop_image" class="form-label">Shop Profile Image (Optional)</label>
                                                <input type="file" class="form-control" id="shop_image" name="profileImage" accept="image/*">
                                            </div>

                                            <button type="submit" class="btn btn-primary mt-3">
                                                <i class="bi bi-shop me-2"></i> Create Shop
                                            </button>
                                        </form>
                                    </div>
                                @endif

                                {{-- Shop Information Tab (Shop Owners Only) --}}
                                @if($ownedShops->isNotEmpty())
                                    <div class="tab-pane fade" id="v-pills-shop" role="tabpanel" aria-labelledby="v-pills-shop-tab">
                                        <h5 class="mb-4 fw-bold" style="color: #333;">Shop Information</h5>
                                        <hr class="mb-4 text-muted">
                                        
                                        @foreach($ownedShops as $shop)
                                            <div class="card mb-3">
                                                <div class="card-body">
                                                    <h6 class="card-title fw-bold">{{ $shop->name }}</h6>
                                                    
                                                    <form method="POST" action="{{ route('shop.update', $shop->id) }}" enctype="multipart/form-data">
                                                        @csrf
                                                        @method('PUT')
                                                        
                                                        <div class="mb-3">
                                                            <label class="form-label">Shop Name</label>
                                                            <input type="text" class="form-control" name="name" value="{{ $shop->name }}" required>
                                                        </div>

                                                        <div class="mb-3">
                                                            <label class="form-label">Address</label>
                                                            <textarea class="form-control" name="address" rows="3" required>{{ $shop->address }}</textarea>
                                                        </div>

                                                        <div class="mb-3">
                                                            <label class="form-label">Phone Number</label>
                                                            <input type="tel" class="form-control" name="phone" value="{{ $shop->phone }}" required>
                                                        </div>

                                                        <div class="mb-3">
                                                            <label class="form-label">Profile Image</label>
                                                            @if($shop->profileImage)
                                                                <div class="mb-2">
                                                                    <img src="{{ asset('storage/' . $shop->profileImage) }}" alt="Shop Image" class="img-thumbnail" style="max-width: 200px;">
                                                                </div>
                                                            @endif
                                                            <input type="file" class="form-control" name="profileImage" accept="image/*">
                                                        </div>

                                                        <button type="submit" class="btn btn-primary">
                                                            <i class="bi bi-save me-2"></i> Update Shop
                                                        </button>
                                                    </form>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @endif

                                {{-- Notifications Tab (Shop Owners & Staff) --}}
                                @if(Auth::user()->shops()->exists())
                                    <div class="tab-pane fade" id="v-pills-notifications" role="tabpanel" aria-labelledby="v-pills-notifications-tab">
                                        <h5 class="mb-4 fw-bold" style="color: #333;">Notification Preferences</h5>
                                        <hr class="mb-4 text-muted">
                                        
                                        <p class="text-muted mb-4">Manage how you receive notifications about orders.</p>

                                        @foreach(Auth::user()->shops as $shop)
                                            <div class="card mb-3">
                                                <div class="card-body">
                                                    <h6 class="card-title fw-bold">{{ $shop->name }}</h6>
                                                    <p class="text-muted small mb-3">Role: {{ $shop->pivot->role }}</p>
                                                    
                                                    <form method="POST" action="{{ route('shop.notifications.update', $shop->id) }}">
                                                        @csrf
                                                        @method('PATCH')
                                                        
                                                        <div class="form-check form-switch">
                                                            <input class="form-check-input" type="checkbox" name="getNotification" 
                                                                   id="notification-{{ $shop->id }}" 
                                                                   {{ $shop->pivot->getNotification ? 'checked' : '' }}>
                                                            <label class="form-check-label" for="notification-{{ $shop->id }}">
                                                                Receive order notifications for this shop
                                                            </label>
                                                        </div>

                                                        <button type="submit" class="btn btn-sm btn-primary mt-3">
                                                            Save Preferences
                                                        </button>
                                                    </form>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @endif

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