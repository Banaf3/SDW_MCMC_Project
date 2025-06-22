@extends('layouts.app')

@section('title', 'MCMC Reports & Analytics')

@section('content')
<style>
    .reports-container {
        max-width: 1400px;
        margin: 0 auto;
        padding: 0;
    }
    
    .reports-header {
        background: white;
        border-radius: 8px;
        border: 1px solid #e2e8f0;
        padding: 24px;
        margin-bottom: 24px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    }
    
    .reports-title {
        color: #1a202c;
        font-size: 1.8rem;
        font-weight: 600;
        margin-bottom: 8px;
    }
    
    .reports-subtitle {
        color: #6b7280;
        margin-bottom: 20px;
    }
    
    .date-filter {
        display: flex;
        gap: 16px;
        align-items: end;
        flex-wrap: wrap;
    }
    
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 20px;
        margin-bottom: 24px;
    }
    
    .stat-card {
        background: white;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        padding: 20px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        text-align: center;
    }
    
    .stat-number {
        font-size: 2rem;
        font-weight: bold;
        margin-bottom: 4px;
    }
    
    .stat-label {
        color: #6b7280;
        font-size: 14px;
    }
    
    .stat-pending { color: #f59e0b; }
    .stat-review { color: #3b82f6; }
    .stat-resolved { color: #10b981; }
    .stat-flagged { color: #ef4444; }
    .stat-total { color: #6b7280; }
    
    .charts-section {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 24px;
        margin-bottom: 24px;
    }
    
    .chart-card {
        background: white;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        padding: 20px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    }
    
    .chart-title {
        font-size: 1.1rem;
        font-weight: 600;
        margin-bottom: 16px;
        color: #1a202c;
    }
    
    .data-tables {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 24px;
    }
    
    .table-card {
        background: white;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        padding: 20px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    }
    
    .data-table {
        width: 100%;
        border-collapse: collapse;
    }
    
    .data-table th,
    .data-table td {
        padding: 8px 12px;
        text-align: left;
        border-bottom: 1px solid #e2e8f0;
    }
    
    .data-table th {
        background-color: #f8fafc;
        font-weight: 600;
        color: #374151;
    }
    
    .btn {
        padding: 8px 16px;
        border-radius: 4px;
        font-size: 14px;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.2s;
        border: none;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 4px;
    }
    
    .btn-primary {
        background-color: #3b82f6;
        color: white;
    }
    
    .btn-primary:hover {
        background-color: #2563eb;
    }
    
    .trend-chart {
        display: flex;
        align-items: end;
        gap: 8px;
        height: 100px;
        margin-top: 10px;
    }
    
    .trend-bar {
        background-color: #3b82f6;
        border-radius: 2px 2px 0 0;
        min-width: 20px;
        flex: 1;
        position: relative;
    }
    
    .trend-bar:hover {
        background-color: #2563eb;
    }
    
    .trend-label {
        font-size: 10px;
        text-align: center;
        margin-top: 4px;
        color: #6b7280;
    }
    
    @media (max-width: 768px) {
        .charts-section,
        .data-tables {
            grid-template-columns: 1fr;
        }
        
        .date-filter {
            flex-direction: column;
            align-items: stretch;
        }
    }
</style>

<div class="reports-container">
    <!-- Header with Date Filter -->
    <div class="reports-header">
        <h1 class="reports-title">MCMC Reports & Analytics</h1>
        <p class="reports-subtitle">Comprehensive inquiry management analytics and reporting</p>
        
        <form method="GET" action="{{ route('admin.reports') }}" class="date-filter">
            <div style="flex: 1; min-width: 150px;">
                <label style="display: block; margin-bottom: 4px; font-weight: 500; color: #374151;">Start Date</label>
                <input type="date" name="start_date" value="{{ $startDate }}" 
                       style="width: 100%; padding: 8px 12px; border: 1px solid #d1d5db; border-radius: 4px;">
            </div>
            
            <div style="flex: 1; min-width: 150px;">
                <label style="display: block; margin-bottom: 4px; font-weight: 500; color: #374151;">End Date</label>
                <input type="date" name="end_date" value="{{ $endDate }}" 
                       style="width: 100%; padding: 8px 12px; border: 1px solid #d1d5db; border-radius: 4px;">
            </div>
              <button type="submit" class="btn btn-primary">
                <svg width="16" height="16" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
                Generate Report
            </button>
            
            <button type="button" onclick="exportToPDF()" class="btn btn-secondary" style="background-color: #dc2626; color: white;">
                <svg width="16" height="16" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M14,2H6A2,2 0 0,0 4,4V20A2,2 0 0,0 6,22H18A2,2 0 0,0 20,20V8L14,2M18,20H6V4H13V9H18V20Z" />
                </svg>
                Export PDF
            </button>
            
            <button type="button" onclick="exportToExcel()" class="btn btn-secondary" style="background-color: #16a34a; color: white;">
                <svg width="16" height="16" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M14,2H6A2,2 0 0,0 4,4V20A2,2 0 0,0 6,22H18A2,2 0 0,0 20,20V8L14,2M18,20H6V4H13V9H18V20Z" />
                </svg>
                Export Excel
            </button>
        </form>
    </div>

    <!-- Statistics Overview -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-number stat-total">{{ $totalInquiries }}</div>
            <div class="stat-label">Total Inquiries</div>
        </div>
        <div class="stat-card">
            <div class="stat-number stat-pending">{{ $pendingInquiries }}</div>
            <div class="stat-label">Pending Review</div>
        </div>
        <div class="stat-card">
            <div class="stat-number stat-review">{{ $underReviewInquiries }}</div>
            <div class="stat-label">Under Review</div>
        </div>
        <div class="stat-card">
            <div class="stat-number stat-resolved">{{ $resolvedInquiries }}</div>
            <div class="stat-label">Resolved</div>
        </div>
        <div class="stat-card">
            <div class="stat-number stat-flagged">{{ $flaggedInquiries }}</div>
            <div class="stat-label">Flagged</div>
        </div>
    </div>

    <!-- Charts Section -->
    <div class="charts-section">
        <!-- Status Distribution -->
        <div class="chart-card">
            <h3 class="chart-title">Status Distribution</h3>            @if($statusDistribution->count() > 0)
                @foreach($statusDistribution as $status)
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px;">
                        <span>{{ $status->InquiryStatus }}</span>
                        <div style="display: flex; align-items: center; gap: 8px;">
                            <div style="width: 100px; height: 8px; background-color: #e2e8f0; border-radius: 4px;">
                                <div style="width: {{ $totalInquiries > 0 ? ($status->count / $totalInquiries) * 100 : 0 }}%; height: 100%; background-color: #3b82f6; border-radius: 4px;"></div>
                            </div>
                            <span style="font-weight: 600;">{{ $status->count }}</span>
                        </div>
                    </div>
                @endforeach
            @else
                <p style="color: #6b7280; text-align: center; padding: 20px;">No data available for the selected period.</p>
            @endif
        </div>        <!-- Daily Trend -->
        <div class="chart-card">
            <h3 class="chart-title">Daily Inquiry Trend (Last 7 Days)</h3>
            <div class="trend-chart">
                @php
                    $maxCount = max(array_column($dailyTrend, 'count'));
                    $maxCount = $maxCount > 0 ? $maxCount : 1; // Prevent division by zero
                @endphp
                @foreach($dailyTrend as $day)
                    <div style="flex: 1; text-align: center;">
                        <div class="trend-bar" style="height: {{ $day['count'] > 0 ? max(10, ($day['count'] / $maxCount) * 100) : 10 }}px;"></div>
                        <div class="trend-label">{{ $day['date'] }}</div>
                        <div style="font-size: 10px; font-weight: 600; color: #3b82f6;">{{ $day['count'] }}</div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Data Tables -->
    <div class="data-tables">
        <!-- Top Users -->
        <div class="table-card">
            <h3 class="chart-title">Most Active Users</h3>
            @if($topUsers->count() > 0)
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>User</th>
                            <th>Inquiries</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($topUsers as $user)
                            <tr>
                                <td>{{ $user->user->UserName ?? 'Unknown User' }}</td>
                                <td><strong>{{ $user->inquiry_count }}</strong></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <p style="color: #6b7280; text-align: center; padding: 20px;">No user data available.</p>
            @endif
        </div>

        <!-- Recent Audit Activities -->
        <div class="table-card">
            <h3 class="chart-title">Recent Audit Activities</h3>
            @if($recentAudits->count() > 0)
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Action</th>
                            <th>Admin</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($recentAudits as $audit)
                            <tr>
                                <td>{{ $audit->Action }}</td>
                                <td>{{ $audit->administrator->AdminName ?? 'Unknown' }}</td>
                                <td>{{ \Carbon\Carbon::parse($audit->ActionDate)->format('M d, H:i') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <p style="color: #6b7280; text-align: center; padding: 20px;">No audit activities found.</p>
            @endif
        </div>
    </div>
</div>

@if(session('success'))
    <script>
        alert('{{ session('success') }}');
    </script>
@endif

<!-- Include libraries for PDF and Excel export -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>

<script>
function exportToPDF() {
    const { jsPDF } = window.jspdf;
    const doc = new jsPDF();
    
    // Add title
    doc.setFontSize(20);
    doc.text('MCMC Reports & Analytics', 20, 20);
    
    // Add date range
    doc.setFontSize(12);
    doc.text(`Report Period: {{ $startDate }} to {{ $endDate }}`, 20, 35);
    
    // Add statistics
    doc.setFontSize(14);
    doc.text('Summary Statistics:', 20, 50);
    doc.setFontSize(10);
    doc.text(`Total Inquiries: {{ $totalInquiries }}`, 20, 60);
    doc.text(`Pending Review: {{ $pendingInquiries }}`, 20, 70);
    doc.text(`Under Review: {{ $underReviewInquiries }}`, 20, 80);
    doc.text(`Resolved: {{ $resolvedInquiries }}`, 20, 90);
    doc.text(`Flagged: {{ $flaggedInquiries }}`, 20, 100);
    
    // Add status distribution
    doc.setFontSize(14);
    doc.text('Status Distribution:', 20, 120);
    doc.setFontSize(10);
    let yPos = 130;
    @if($statusDistribution->count() > 0)
        @foreach($statusDistribution as $status)
            doc.text('{{ $status->InquiryStatus }}: {{ $status->count }}', 20, yPos);
            yPos += 10;
        @endforeach
    @endif
    
    // Add top users
    doc.setFontSize(14);
    doc.text('Most Active Users:', 20, yPos + 10);
    doc.setFontSize(10);
    yPos += 20;
    @if($topUsers->count() > 0)
        @foreach($topUsers as $user)
            doc.text('{{ $user->user->UserName ?? "Unknown User" }}: {{ $user->inquiry_count }} inquiries', 20, yPos);
            yPos += 10;
        @endforeach
    @endif
    
    // Save PDF
    doc.save('MCMC_Report_{{ $startDate }}_to_{{ $endDate }}.pdf');
}

function exportToExcel() {
    // Create workbook and worksheet
    const wb = XLSX.utils.book_new();
    
    // Summary Statistics Sheet
    const summaryData = [
        ['MCMC Reports & Analytics'],
        ['Report Period', '{{ $startDate }} to {{ $endDate }}'],
        [''],
        ['Summary Statistics'],
        ['Total Inquiries', {{ $totalInquiries }}],
        ['Pending Review', {{ $pendingInquiries }}],
        ['Under Review', {{ $underReviewInquiries }}],
        ['Resolved', {{ $resolvedInquiries }}],
        ['Flagged', {{ $flaggedInquiries }}]
    ];
    
    const summaryWS = XLSX.utils.aoa_to_sheet(summaryData);
    XLSX.utils.book_append_sheet(wb, summaryWS, 'Summary');
    
    // Status Distribution Sheet
    const statusData = [['Status', 'Count']];
    @if($statusDistribution->count() > 0)
        @foreach($statusDistribution as $status)
            statusData.push(['{{ $status->InquiryStatus }}', {{ $status->count }}]);
        @endforeach
    @endif
    
    const statusWS = XLSX.utils.aoa_to_sheet(statusData);
    XLSX.utils.book_append_sheet(wb, statusWS, 'Status Distribution');
    
    // Top Users Sheet
    const usersData = [['User Name', 'Inquiry Count']];
    @if($topUsers->count() > 0)
        @foreach($topUsers as $user)
            usersData.push(['{{ $user->user->UserName ?? "Unknown User" }}', {{ $user->inquiry_count }}]);
        @endforeach
    @endif
    
    const usersWS = XLSX.utils.aoa_to_sheet(usersData);
    XLSX.utils.book_append_sheet(wb, usersWS, 'Top Users');
    
    // Daily Trend Sheet
    const trendData = [['Date', 'Count']];
    @foreach($dailyTrend as $day)
        trendData.push(['{{ $day["date"] }}', {{ $day["count"] }}]);
    @endforeach
    
    const trendWS = XLSX.utils.aoa_to_sheet(trendData);
    XLSX.utils.book_append_sheet(wb, trendWS, 'Daily Trend');
    
    // Recent Audit Activities Sheet
    const auditData = [['Action', 'Admin', 'Date']];
    @if($recentAudits->count() > 0)
        @foreach($recentAudits as $audit)
            auditData.push([
                '{{ $audit->Action }}', 
                '{{ $audit->PerformedBy }}', 
                '{{ \Carbon\Carbon::parse($audit->ActionDate)->format("M d, H:i") }}'
            ]);
        @endforeach
    @endif
    
    const auditWS = XLSX.utils.aoa_to_sheet(auditData);
    XLSX.utils.book_append_sheet(wb, auditWS, 'Recent Audits');
    
    // Save Excel file
    XLSX.writeFile(wb, 'MCMC_Report_{{ $startDate }}_to_{{ $endDate }}.xlsx');
}
</script>
@endsection
