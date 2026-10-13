<section>
    <p class="text-muted small mb-3">
        Once your account is deleted, all of its resources and clinical records association will be permanently deleted.
        Before deleting your account, please ensure you have saved any necessary information.
    </p>

    <!-- Trigger Button -->
    <button type="button" class="btn btn-danger fw-semibold px-4 py-2 shadow-sm" data-bs-toggle="modal" data-bs-target="#confirmUserDeletionModal">
        <i class="bi bi-trash3-fill me-2"></i> Delete Account
    </button>

    <!-- Bootstrap 5 Deletion Confirmation Modal -->
    <div class="modal fade" id="confirmUserDeletionModal" tabindex="-1" aria-labelledby="confirmUserDeletionModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content shadow border-0">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title fw-bold" id="confirmUserDeletionModalLabel">
                        <i class="bi bi-exclamation-triangle-fill me-2"></i> Confirm Account Deletion
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="post" action="{{ route('profile.destroy') }}">
                    @csrf
                    @method('delete')

                    <div class="modal-body p-4">
                        <p class="fw-semibold text-dark mb-1">
                            Are you sure you want to permanently delete your account?
                        </p>
                        <p class="text-muted small mb-3">
                            This action cannot be undone. Please enter your account password below to confirm this permanent action.
                        </p>

                        <div class="mb-3">
                            <label for="deletion_password" class="form-label fw-semibold small text-dark">Your Password <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text bg-light text-secondary"><i class="bi bi-lock-fill"></i></span>
                                <input id="deletion_password" name="password" type="password"
                                    class="form-control @error('password', 'userDeletion') is-invalid @enderror"
                                    placeholder="Enter your account password" required>
                                @error('password', 'userDeletion')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer bg-light">
                        <button type="button" class="btn btn-secondary px-3" data-bs-dismiss="modal">
                            Cancel
                        </button>
                        <button type="submit" class="btn btn-danger fw-semibold px-4 shadow-sm">
                            <i class="bi bi-trash3-fill me-1"></i> Confirm Delete
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @if ($errors->userDeletion->isNotEmpty())
        @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const modalElem = document.getElementById('confirmUserDeletionModal');
                if (modalElem) {
                    const modal = new bootstrap.Modal(modalElem);
                    modal.show();
                }
            });
        </script>
        @endpush
    @endif
</section>
