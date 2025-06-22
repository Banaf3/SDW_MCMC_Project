@extends('layouts.app')

@section('title', 'Generate Reports - MCMC Admin')

@section('content')
<div class="container-fluid px-4">
    <div class="row">
        <div class="col-12">
            @if(session('error'))
                <div class="alert alert-warning alert-dismissible fade show" role="alert">
                    <strong>Notice:</strong> {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3 mb-0 text-gray-800">
                    <i class="fas fa-chart-bar me-2"></i>Generate Reports
                </h1>
                <div class="d-flex gap-2">
                    <button type="button" class="btn btn-primary" onclick="exportReport('pdf')">
                        <i class="fas fa-file-pdf me-1"></i>Export PDF
                    </button>
                    <button type="button" class="btn btn-success" onclick="exportReport('excel')">
                        <i class="fas fa-file-excel me-1"></i>Export Excel
                    </button>
                </div>
            </div>

            <!-- Filters Card -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-filter me-2"></i>Report Filters
                    </h5>
                </div>
                <div class="card-body">
                    <form method="GET" action="{{ route('admin.reports.index') }}" id="filterForm">
                        <div class="row g-3">
                            <div class="col-md-3">
                                <label for="year" class="form-label">Year</label>
                                <select name="year" id="year" class="form-select">
                                    <option value="">All Years</option>
                                    @foreach($years as $year)
                                        <option value="{{ $year }}" {{ $filterYear == $year ? 'selected' : '' }}>
                                            {{ $year }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            
                            <div class="col-md-3">
                                <label for="month" class="form-label">Month</label>
                                <select name="month" id="month" class="form-select">
                                    <option value="">All Months</option>
                                    @for($i = 1; $i <= 12; $i++)
                                        <option value="{{ $i }}" {{ $filterMonth == $i ? 'selected' : '' }}>
                                            {{ date('F', mktime(0, 0, 0, $i, 1)) }}
                                        </option>
                                    @endfor
                                </select>
                            </div>
                            
                            <div class="col-md-3">
                                <label for="status" class="form-label">Status</label>
                                <select name="status" id="status" class="form-select">
                                    <option value="">All Statuses</option>
                                    @foreach($statuses as $status)
                                        <option value="{{ $status }}" {{ $filterStatus == $status ? 'selected' : '' }}>
                                            {{ $status }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            
                            <div class="col-md-3">
                                <label for="agency" class="form-label">Agency</label>
                                <select name="agency" id="agency" class="form-select">
                                    <option value="">All Agencies</option>
                                    @foreach($agencies as $agency)
                                        <option value="{{ $agency->AgencyID }}" {{ $filterAgency == $agency->AgencyID ? 'selected' : '' }}>
                                            {{ $agency->AgencyName }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        
                        <div class="row mt-3">
                            <div class="col-12">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-search me-1"></i>Apply Filters
                                </button>
                                <a href="{{ route('admin.reports.index') }}" class="btn btn-outline-secondary ms-2">
                                    <i class="fas fa-times me-1"></i>Clear Filters
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Statistics Cards -->
            <div class="row mb-4">
                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card border-left-primary shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                        Total Inquiries
                                    </div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">
                                        {{ number_format($stats['total_inquiries']) }}
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-inbox fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card border-left-warning shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                        Pending Inquiries
                                    </div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">
                                        {{ number_format($stats['pending_inquiries']) }}
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-clock fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card border-left-info shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                        In Progress
                                    </div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">
                                        {{ number_format($stats['in_progress_inquiries']) }}
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-spinner fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card border-left-success shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                        Resolved
                                    </div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">
                                        {{ number_format($stats['resolved_inquiries']) }}
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-check-circle fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Charts Row -->
            <div class="row mb-4">
                <!-- Status Distribution Pie Chart -->
                <div class="col-xl-6 col-lg-6">
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Status Distribution</h6>
                        </div>
                        <div class="card-body">
                            <div class="chart-pie pt-4 pb-2">
                                <canvas id="statusChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Monthly Submissions Line Chart -->
                <div class="col-xl-6 col-lg-6">
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Monthly Submissions</h6>
                        </div>
                        <div class="card-body">
                            <div class="chart-area">
                                <canvas id="monthlyChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Agency Distribution Bar Chart -->
            <div class="row mb-4">
                <div class="col-12">
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Agency Distribution</h6>
                        </div>
                        <div class="card-body">
                            <div class="chart-bar">
                                <canvas id="agencyChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Additional Statistics -->
            <div class="row mb-4">
                <div class="col-12">
                    <div class="card shadow">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Additional Statistics</h6>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <strong>Average Resolution Time:</strong>
                                        <span class="text-muted">{{ $stats['avg_resolution_time'] }} days</span>
                                    </div>
                                    <div class="mb-3">
                                        <strong>Most Active Agency:</strong>
                                        <span class="text-muted">
                                            {{ $stats['agency_counts']->keys()->first() ?? 'N/A' }}
                                            ({{ $stats['agency_counts']->first() ?? 0 }} inquiries)
                                        </span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <strong>Most Common Status:</strong>
                                        <span class="text-muted">
                                            {{ $stats['status_counts']->keys()->first() ?? 'N/A' }}
                                            ({{ $stats['status_counts']->first() ?? 0 }} inquiries)
                                        </span>
                                    </div>
                                    <div class="mb-3">
                                        <strong>Peak Submission Month:</strong>
                                        <span class="text-muted">
                                            {{ $stats['monthly_submissions']->keys()->last() ?? 'N/A' }}
                                            ({{ $stats['monthly_submissions']->max() ?? 0 }} inquiries)
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Inquiry Data Table -->
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        Inquiry Details ({{ $inquiries->count() }} records)
                    </h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Title</th>
                                    <th>Submission Date</th>
                                    <th>Status</th>
                                    <th>Agency</th>
                                    <th>Last Updated</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($inquiries as $inquiry)
                                <tr>
                                    <td>{{ $inquiry->InquiryID }}</td>
                                    <td>{{ Str::limit($inquiry->InquiryTitle, 50) }}</td>
                                    <td>{{ $inquiry->SubmitionDate->format('Y-m-d') }}</td>
                                    <td>
                                        <span class="badge 
                                            @if($inquiry->InquiryStatus == 'Pending') badge-warning
                                            @elseif($inquiry->InquiryStatus == 'In Progress') badge-info
                                            @elseif($inquiry->InquiryStatus == 'Resolved') badge-success
                                            @elseif($inquiry->InquiryStatus == 'Rejected') badge-danger
                                            @else badge-secondary
                                            @endif">
                                            {{ $inquiry->InquiryStatus }}
                                        </span>
                                    </td>
                                    <td>{{ $inquiry->agency ? $inquiry->agency->AgencyName : 'Unassigned' }}</td>
                                    <td>{{ $inquiry->updated_at->format('Y-m-d H:i') }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
// Chart data from backend
const chartData = @json($chartData);

// Status Pie Chart
const statusCtx = document.getElementById('statusChart').getContext('2d');
new Chart(statusCtx, {
    type: 'pie',
    data: {
        labels: chartData.status_chart.labels,
        datasets: [{
            data: chartData.status_chart.data,
            backgroundColor: [
                '#f6c23e', // Warning (Pending)
                '#36b9cc', // Info (In Progress)
                '#1cc88a', // Success (Resolved)
                '#e74a3b', // Danger (Rejected)
                '#6c757d'  // Secondary (Other)
            ],
            borderColor: '#ffffff',
            borderWidth: 2
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: {
                position: 'bottom'
            }
        }
    }
});

// Monthly Line Chart
const monthlyCtx = document.getElementById('monthlyChart').getContext('2d');
new Chart(monthlyCtx, {
    type: 'line',
    data: {
        labels: chartData.monthly_chart.labels,
        datasets: [{
            label: 'Submissions',
            data: chartData.monthly_chart.data,
            borderColor: '#4e73df',
            backgroundColor: 'rgba(78, 115, 223, 0.05)',
            fill: true,
            tension: 0.3
        }]
    },
    options: {
        responsive: true,
        scales: {
            y: {
                beginAtZero: true
            }
        }
    }
});

// Agency Bar Chart
const agencyCtx = document.getElementById('agencyChart').getContext('2d');
new Chart(agencyCtx, {
    type: 'bar',
    data: {
        labels: chartData.agency_chart.labels,
        datasets: [{
            label: 'Inquiries',
            data: chartData.agency_chart.data,
            backgroundColor: '#1cc88a',
            borderColor: '#1cc88a',
            borderWidth: 1
        }]
    },
    options: {
        responsive: true,
        scales: {
            y: {
                beginAtZero: true
            }
        }
    }
});

// Export functions
function exportReport(format) {
    const form = document.getElementById('filterForm');
    const formData = new FormData(form);
    
    let exportUrl = '';
    if (format === 'pdf') {
        exportUrl = '{{ route("admin.reports.export.pdf") }}';
    } else if (format === 'excel') {
        exportUrl = '{{ route("admin.reports.export.excel") }}';
    }
    
    // Create hidden form for export
    const exportForm = document.createElement('form');
    exportForm.method = 'GET';
    exportForm.action = exportUrl;
    
    // Add filter parameters
    for (let [key, value] of formData.entries()) {
        if (value) {
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = key;
            input.value = value;
            exportForm.appendChild(input);
        }
    }
    
    document.body.appendChild(exportForm);
    exportForm.submit();
    document.body.removeChild(exportForm);
}
</script>

<style>
.border-left-primary {
    border-left: 0.25rem solid #4e73df !important;
}

.border-left-warning {
    border-left: 0.25rem solid #f6c23e !important;
}

.border-left-info {
    border-left: 0.25rem solid #36b9cc !important;
}

.border-left-success {
    border-left: 0.25rem solid #1cc88a !important;
}

.chart-pie, .chart-area, .chart-bar {
    position: relative;
    height: 15rem;
}

.table th {
    border-top: none;
}

.badge-warning {
    background-color: #f6c23e;
    color: #1f2937;
}

.badge-info {
    background-color: #36b9cc;
}

.badge-success {
    background-color: #1cc88a;
}

.badge-danger {
    background-color: #e74a3b;
}

.badge-secondary {
    background-color: #6c757d;
}
</style>
@endsection
