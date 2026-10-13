@extends('layouts.app')

@section('title', 'Profile Settings')
@section('subtitle', 'Manage your personal account credentials, security, and preferences')

@section('content')
<div class="row g-4">
    <!-- Right Column: Settings Forms -->
    <div class="col-12">
        <div class="d-flex flex-column gap-4">

            <!-- Card 1: Profile Information -->
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3 border-bottom">
                    <div class="d-flex align-items-center gap-2">
                        <div class="stats-icon-box bg-primary bg-opacity-10 text-primary" style="width: 38px; height: 38px; font-size: 1.15rem;">
                            <i class="bi bi-person-gear"></i>
                        </div>
                        <div>
                            <h5 class="card-title mb-0 fw-bold text-dark">Profile Information</h5>
                            <small class="text-muted">Update your account name and email address</small>
                        </div>
                    </div>
                </div>
                <div class="card-body p-4">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>

            <!-- Card 2: Update Password -->
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3 border-bottom">
                    <div class="d-flex align-items-center gap-2">
                        <div class="stats-icon-box bg-info bg-opacity-10 text-info" style="width: 38px; height: 38px; font-size: 1.15rem;">
                            <i class="bi bi-shield-lock-fill"></i>
                        </div>
                        <div>
                            <h5 class="card-title mb-0 fw-bold text-dark">Update Password</h5>
                            <small class="text-muted">Ensure your account is protected with a secure password</small>
                        </div>
                    </div>
                </div>
                <div class="card-body p-4">
                    @include('profile.partials.update-password-form')
                </div>
            </div>

            <!-- Card 3: Delete Account -->
            <div class="card border-0 shadow-sm border-start border-4 border-danger">
                <div class="card-header bg-white py-3 border-bottom">
                    <div class="d-flex align-items-center gap-2">
                        <div class="stats-icon-box bg-danger bg-opacity-10 text-danger" style="width: 38px; height: 38px; font-size: 1.15rem;">
                            <i class="bi bi-exclamation-triangle-fill"></i>
                        </div>
                        <div>
                            <h5 class="card-title mb-0 fw-bold text-danger">Delete Account</h5>
                            <small class="text-muted">Permanently remove your account and all associated access</small>
                        </div>
                    </div>
                </div>
                <div class="card-body p-4">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>

        </div>
    </div>
</div>
@endsection
