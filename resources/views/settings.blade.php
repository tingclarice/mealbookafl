@extends('layouts.app')

@section('content')
<style>
    /* Custom Theme Settings */
    :root {
        --theme-primary: #F97352;
        --theme-hover: #e06040; /* Slightly darker for hover states */
    }

    body {
        font-family: system-ui, -apple-system, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif !important;
    }

    /* Custom Button */
    .btn-theme {
        background-color: var(--theme-primary);
        border-color: var(--theme-primary);
        color: white;
        font-weight: 500;
    }

    .btn-theme:hover {
        background-color: var(--theme-hover);
        border-color: var(--theme-hover);
        color: white;
    }

    /* Custom Input Focus Ring */
    .form-control:focus {
        border-color: var(--theme-primary);
        box-shadow: 0 0 0 0.25rem rgba(249, 115, 82, 0.25); /* #F97352 with opacity */
    }

    /* Sidebar Menu Styling */
    .settings-menu .list-group-item {
        border: none;
        color: #6c757d; /* Muted gray for inactive */
        padding: 1rem 1.5rem;
        transition: all 0.2s;
    }

    .settings-menu .list-group-item:hover {
        color: var(--theme-primary);
        background-color: #f8f9fa;
    }

    /* Active State Styling - Left Border Accent */
    .settings-menu .list-group-item.active {
        background-color: white; /* Keep background white */
        color: var(--theme-primary);
        font-weight: bold;
        border-left: 4px solid var(--theme-primary) !important;
        border-radius: 0;
    }
</style>

<div class="container py-5">
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
                                
                                <a class="list-group-item list-group-item-action" 
                                   id="v-pills-security-tab" 
                                   data-bs-toggle="pill" 
                                   href="#v-pills-security" 
                                   role="tab" 
                                   aria-controls="v-pills-security" 
                                   aria-selected="false">
                                    <i class="bi bi-shield-lock me-2"></i> Security
                                </a>

                            </div>
                        </div>

                        <div class="col-md-9 bg-white">
                            <div class="tab-content p-4 p-md-5" id="v-pills-tabContent">
                                
                                <div class="tab-pane fade show active" id="v-pills-profile" role="tabpanel" aria-labelledby="v-pills-profile-tab">
                                    <h5 class="mb-4 fw-bold" style="color: #333;">Profile Information</h5>
                                    <hr class="mb-4 text-muted">

                                    <form>
                                        <div class="mb-3">
                                            <label for="name" class="form-label text-muted small text-uppercase fw-bold">Full Name</label>
                                            <input type="text" class="form-control form-control-lg fs-6" id="name" value="John Doe">
                                        </div>
                                        <div class="mb-3">
                                            <label for="email" class="form-label text-muted small text-uppercase fw-bold">Email Address</label>
                                            <input type="email" class="form-control form-control-lg fs-6" id="email" value="john@example.com">
                                        </div>
                                        <div class="mb-3">
                                            <label for="bio" class="form-label text-muted small text-uppercase fw-bold">Bio</label>
                                            <textarea class="form-control fs-6" id="bio" rows="4"></textarea>
                                        </div>
                                        <div class="d-flex justify-content-end mt-4">
                                            <button type="submit" class="btn btn-theme px-4 py-2">Save Changes</button>
                                        </div>
                                    </form>
                                </div>

                                <div class="tab-pane fade" id="v-pills-security" role="tabpanel" aria-labelledby="v-pills-security-tab">
                                    <h5 class="mb-4 fw-bold" style="color: #333;">Security Settings</h5>
                                    <hr class="mb-4 text-muted">
                                    
                                    <form>
                                        <div class="mb-3">
                                            <label for="current_password" class="form-label text-muted small text-uppercase fw-bold">Current Password</label>
                                            <input type="password" class="form-control form-control-lg fs-6" id="current_password">
                                        </div>
                                        <div class="mb-3">
                                            <label for="new_password" class="form-label text-muted small text-uppercase fw-bold">New Password</label>
                                            <input type="password" class="form-control form-control-lg fs-6" id="new_password">
                                        </div>
                                        
                                        <div class="form-check mb-4 mt-3">
                                            <input class="form-check-input" type="checkbox" id="2fa" style="accent-color: #F97352;">
                                            <label class="form-check-label" for="2fa">
                                                Enable Two-Factor Authentication
                                            </label>
                                        </div>

                                        <div class="d-flex justify-content-end mt-4">
                                            <button type="submit" class="btn btn-theme px-4 py-2">Update Password</button>
                                        </div>
                                    </form>
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