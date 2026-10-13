<section>
    <form method="post" action="{{ route('password.update') }}">
        @csrf
        @method('put')

        @if (session('status') === 'password-updated')
            <div class="alert alert-success alert-dismissible fade show small d-flex align-items-center mb-3" role="alert">
                <i class="bi bi-check-circle-fill me-2 fs-6"></i>
                <div class="fw-semibold">Password updated successfully!</div>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="mb-3">
            <label for="update_password_current_password" class="form-label fw-semibold text-dark">
                Current Password <span class="text-danger">*</span>
            </label>
            <div class="input-group">
                <span class="input-group-text bg-light text-secondary"><i class="bi bi-shield-lock"></i></span>
                <input id="update_password_current_password" name="current_password" type="password"
                    class="form-control @error('current_password', 'updatePassword') is-invalid @enderror"
                    autocomplete="current-password" placeholder="Enter current password" required>
                @error('current_password', 'updatePassword')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div class="row g-3 mb-3">
            <div class="col-md-6">
                <label for="update_password_password" class="form-label fw-semibold text-dark">
                    New Password <span class="text-danger">*</span>
                </label>
                <div class="input-group">
                    <span class="input-group-text bg-light text-secondary"><i class="bi bi-lock"></i></span>
                    <input id="update_password_password" name="password" type="password"
                        class="form-control @error('password', 'updatePassword') is-invalid @enderror"
                        autocomplete="new-password" placeholder="Enter new password" required>
                    @error('password', 'updatePassword')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-text small text-muted">Minimum 8 characters.</div>
            </div>

            <div class="col-md-6">
                <label for="update_password_password_confirmation" class="form-label fw-semibold text-dark">
                    Confirm New Password <span class="text-danger">*</span>
                </label>
                <div class="input-group">
                    <span class="input-group-text bg-light text-secondary"><i class="bi bi-lock-fill"></i></span>
                    <input id="update_password_password_confirmation" name="password_confirmation" type="password"
                        class="form-control @error('password_confirmation', 'updatePassword') is-invalid @enderror"
                        autocomplete="new-password" placeholder="Repeat new password" required>
                    @error('password_confirmation', 'updatePassword')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>

        <div class="d-flex align-items-center gap-3 pt-2">
            <button type="submit" class="btn btn-chc-primary fw-semibold px-4 py-2 shadow-sm">
                <i class="bi bi-shield-check me-1"></i> Update Password
            </button>
        </div>
    </form>
</section>
