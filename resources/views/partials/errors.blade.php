@if ($errors->any())
<div class="alert alert-danger alert-dismissible fade show shadow-sm mb-4" role="alert">
    <div class="d-flex align-items-center mb-1">
        <i class="bi bi-exclamation-triangle-fill fs-5 me-2"></i>
        <strong>Please correct the following errors:</strong>
    </div>
    <ul class="mb-0 ps-4">
        @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif
