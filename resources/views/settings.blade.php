@extends('layouts.app')

@section('content')
<link rel="stylesheet" href="css/settings.css">

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