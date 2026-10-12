@extends('layouts.app')

@section('title', 'Clinical Dashboard')
@section('subtitle', 'Welcome back, ' . auth()->user()->name . ' (' . ucfirst(auth()->user()->role) . ')')

@section('content')

<!-- Metric Overview Cards -->
<div class="row g-3 mb-4">
    <!-- Total Patients -->
    <div class="col-12 col-sm-6 col-xl-3">
        <div class="card h-100 border-0 shadow-sm">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="stats-icon-box bg-primary bg-opacity-10 text-primary">
                    <i class="bi bi-people-fill"></i>
                </div>
                <div>
                    <h3 class="fw-bold mb-0 text-dark">{{ $totalPatients }}</h3>
                    <span class="text-muted small">Total Registered Patients</span>
                </div>
            </div>
            <div class="card-footer bg-transparent border-0 pt-0 pb-3">
                <a href="{{ route('patients.index') }}" class="small text-decoration-none text-primary fw-medium">
                    View Registry <i class="bi bi-arrow-right"></i>
                </a>
            </div>
        </div>
    </div>

    <!-- Active Health Workers -->
    <div class="col-12 col-sm-6 col-xl-3">
        <div class="card h-100 border-0 shadow-sm">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="stats-icon-box bg-success bg-opacity-10 text-success">
                    <i class="bi bi-person-badge-fill"></i>
                </div>
                <div>
                    <h3 class="fw-bold mb-0 text-dark">{{ $totalHealthWorkers }}</h3>
                    <span class="text-muted small">Active Health Workers</span>
                </div>
            </div>
            <div class="card-footer bg-transparent border-0 pt-0 pb-3">
                <a href="{{ route('health-workers.index') }}" class="small text-decoration-none text-success fw-medium">
                    View Staff Directory <i class="bi bi-arrow-right"></i>
                </a>
            </div>
        </div>
    </div>

    <!-- Today's Appointments -->
    <div class="col-12 col-sm-6 col-xl-3">
        <div class="card h-100 border-0 shadow-sm">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="stats-icon-box bg-info bg-opacity-10 text-info">
                    <i class="bi bi-calendar-event-fill"></i>
                </div>
                <div>
                    <h3 class="fw-bold mb-0 text-dark">{{ $todayAppointments }}</h3>
                    <span class="text-muted small">Today's Appointments</span>
                </div>
            </div>
            <div class="card-footer bg-transparent border-0 pt-0 pb-3">
                <a href="{{ route('appointments.index') }}" class="small text-decoration-none text-info fw-medium">
                    View Schedule <i class="bi bi-arrow-right"></i>
                </a>
            </div>
        </div>
    </div>

    <!-- Pending Appointments -->
    <div class="col-12 col-sm-6 col-xl-3">
        <div class="card h-100 border-0 shadow-sm">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="stats-icon-box bg-warning bg-opacity-10 text-warning">
                    <i class="bi bi-hourglass-split"></i>
                </div>
                <div>
                    <h3 class="fw-bold mb-0 text-dark">{{ $pendingAppointments }}</h3>
                    <span class="text-muted small">Pending Requests</span>
                </div>
            </div>
            <div class="card-footer bg-transparent border-0 pt-0 pb-3">
                <a href="{{ route('appointments.index', ['status' => 'pending']) }}" class="small text-decoration-none text-warning fw-medium">
                    Review Pending <i class="bi bi-arrow-right"></i>
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Main Row: Daily Schedule & Pharmacy Inventory Alerts -->
<div class="row g-4">
    <!-- Today's Appointment Schedule Feed -->
    <div class="col-12 col-lg-8">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center gap-2">
                    <i class="bi bi-clock-history text-primary fs-5"></i>
                    <h5 class="card-title mb-0 fw-bold">Today's Appointment Schedule</h5>
                </div>
                <a href="{{ route('appointments.index') }}" class="btn btn-outline-primary btn-sm">
                    View All Appointments
                </a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Time</th>
                                <th>Patient Name</th>
                                <th>Assigned Worker</th>
                                <th>Reason</th>
                                <th>Status</th>
                                <th class="text-end">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($todayAppointmentList as $appt)
                            <tr>
                                <td class="fw-semibold text-primary">
                                    <i class="bi bi-clock me-1"></i>
                                    {{ \Carbon\Carbon::parse($appt->appointment_time)->format('g:i A') }}
                                </td>
                                <td>
                                    <a href="{{ route('patients.show', $appt->patient) }}" class="fw-medium text-dark text-decoration-none">
                                        {{ $appt->patient->full_name }}
                                    </a>
                                </td>
                                <td>{{ $appt->healthWorker->full_name }}</td>
                                <td class="text-muted small">{{ Str::limit($appt->reason, 25) }}</td>
                                <td>
                                    @php
                                        $statusBadges = [
                                            'pending' => 'bg-warning text-dark',
                                            'confirmed' => 'bg-primary',
                                            'completed' => 'bg-success',
                                            'cancelled' => 'bg-secondary',
                                        ];
                                    @endphp
                                    <span class="badge {{ $statusBadges[$appt->status] ?? 'bg-secondary' }} text-capitalize">
                                        {{ $appt->status }}
                                    </span>
                                </td>
                                <td class="text-end">
                                    <a href="{{ route('appointments.show', $appt) }}" class="btn btn-sm btn-light border" title="View Details">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    @if($appt->status === 'confirmed' && !$appt->consultation)
                                    <a href="{{ route('consultations.create', ['appointment_id' => $appt->id, 'patient_id' => $appt->patient_id, 'health_worker_id' => $appt->health_worker_id]) }}" class="btn btn-sm btn-success ms-1" title="Start Consultation">
                                        <i class="bi bi-clipboard2-pulse"></i> Consult
                                    </a>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center py-4 text-muted">
                                    <i class="bi bi-calendar-x fs-2 d-block mb-2 text-secondary"></i>
                                    No appointments scheduled for today.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Medicine Inventory Alerts -->
    <div class="col-12 col-lg-4">
        <!-- Low Stock Alerts -->
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center gap-2">
                    <span class="p-1 rounded-circle bg-danger d-inline-block"></span>
                    <h6 class="card-title mb-0 fw-bold">Low Stock Alert</h6>
                </div>
                <span class="badge bg-danger rounded-pill">{{ $lowStockMedicines->count() }}</span>
            </div>
            <div class="list-group list-group-flush" style="max-height: 220px; overflow-y: auto;">
                @forelse($lowStockMedicines as $med)
                <div class="list-group-item d-flex justify-content-between align-items-center py-2 px-3">
                    <div>
                        <div class="fw-semibold small text-dark">{{ $med->name }}</div>
                        <small class="text-muted">Reorder level: {{ $med->reorder_level }} units</small>
                    </div>
                    <span class="badge bg-danger bg-opacity-10 text-danger fw-bold border border-danger border-opacity-25">
                        {{ $med->stock_quantity }} left
                    </span>
                </div>
                @empty
                <div class="list-group-item text-center py-3 text-muted small">
                    <i class="bi bi-check-circle text-success me-1"></i> All stock levels are sufficient.
                </div>
                @endforelse
            </div>
        </div>

        <!-- Expiry Monitoring -->
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center gap-2">
                    <span class="p-1 rounded-circle bg-warning d-inline-block"></span>
                    <h6 class="card-title mb-0 fw-bold">Expiry Monitoring</h6>
                </div>
                <span class="badge bg-warning text-dark rounded-pill">
                    {{ $expiringSoonMedicines->count() + $expiredMedicines->count() }}
                </span>
            </div>
            <div class="list-group list-group-flush" style="max-height: 220px; overflow-y: auto;">
                @foreach($expiredMedicines as $med)
                <div class="list-group-item d-flex justify-content-between align-items-center py-2 px-3">
                    <div>
                        <div class="fw-semibold small text-danger">{{ $med->name }}</div>
                        <small class="text-danger opacity-75">Expired {{ $med->expiry_date->diffForHumans() }}</small>
                    </div>
                    <span class="badge bg-danger">EXPIRED</span>
                </div>
                @endforeach

                @foreach($expiringSoonMedicines as $med)
                <div class="list-group-item d-flex justify-content-between align-items-center py-2 px-3">
                    <div>
                        <div class="fw-semibold small text-dark">{{ $med->name }}</div>
                        <small class="text-warning-emphasis">Expires {{ $med->expiry_date->diffForHumans() }}</small>
                    </div>
                    <span class="badge bg-warning text-dark">EXPIRING SOON</span>
                </div>
                @endforeach

                @if($expiringSoonMedicines->isEmpty() && $expiredMedicines->isEmpty())
                <div class="list-group-item text-center py-3 text-muted small">
                    <i class="bi bi-shield-check text-success me-1"></i> No expiring or expired medicines.
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

@endsection
