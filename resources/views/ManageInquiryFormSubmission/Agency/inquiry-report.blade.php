@extends('layouts.app')

@section('title', 'Agency Investigation Report')

@section('content')
<style>
    .report-container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 20px;
    }
    
    .report-header {
        background: white;
        border-radius: 8px;
        border: 1px solid #e2e8f0;
        padding: 24px;
        margin-bottom: 24px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    }
    
    .report-title {
        color: #1a202c;
        font-size: 1.8rem;
        font-weight: 600;
        margin-bottom: 8px;
    }
    
    .report-subtitle {
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
        color: #3b82f6;
    }
    
    .stat-label {
        color: #6b7280;
        font-size: 14px;
    }
    
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
    
    @media (max-width: 768px) {
        .charts-section {
            grid-template-columns: 1fr;
        }
        
        .date-filter {
            flex-direction: column;
            align-items: stretch;
        }
    }
</style>

<div class="report-container">
    <!-- Header with Date Filter -->
    <div class="report-header">
        <h1 class="report-title">Agency Investigation Report</h1>
        <p class="report-subtitle">{{ $agency->AgencyName ?? 'Your Agency' }} - Investigation Performance Report</p>
        
        <form method="GET" action="{{ route('agency.reports') }}" class="date-filter">
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
        </form>
    </div>

    <!-- Statistics Overview -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-number">{{ $totalInquiries }}</div>
            <div class="stat-label">Total Inquiries Assigned</div>
        </div>
    </div>

    <!-- Charts Section -->
    <div class="charts-section">
        <!-- Status Breakdown -->
        <div class="chart-card">
            <h3 class="chart-title">Investigation Status Breakdown</h3>
            @if($statusBreakdown->count() > 0)
                @foreach($statusBreakdown as $status)
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px;">
                        <span>{{ $status->InquiryStatus }}</span>
                        <div style="display: flex; align-items: center; gap: 8px;">
                            <div style="width: 100px; height: 8px; background-color: #e2e8f0; border-radius: 4px;">
                                <div style="width: {{ ($status->count / $totalInquiries) * 100 }}%; height: 100%; background-color: #3b82f6; border-radius: 4px;"></div>
                            </div>
                            <span style="font-weight: 600;">{{ $status->count }}</span>
                        </div>
                    </div>
                @endforeach
            @else
                <p style="color: #6b7280; text-align: center; padding: 20px;">No data available for the selected period.</p>
            @endif
        </div>

        <!-- Monthly Trend -->
        <div class="chart-card">
            <h3 class="chart-title">Monthly Assignment Trend</h3>
            @if(count($monthlyTrend) > 0)
                @foreach($monthlyTrend as $month)
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px;">
                        <span>{{ $month['month'] }}</span>
                        <div style="display: flex; align-items: center; gap: 8px;">
                            <div style="width: 100px; height: 8px; background-color: #e2e8f0; border-radius: 4px;">
                                <div style="width: {{ $month['count'] > 0 ? min(100, ($month['count'] / max(array_column($monthlyTrend, 'count'), 1)) * 100) : 0 }}%; height: 100%; background-color: #10b981; border-radius: 4px;"></div>
                            </div>
                            <span style="font-weight: 600;">{{ $month['count'] }}</span>
                        </div>
                    </div>
                @endforeach
            @else
                <p style="color: #6b7280; text-align: center; padding: 20px;">No monthly data available.</p>
            @endif
        </div>
    </div>
</div>

@if(session('success'))
    <script>
        alert('{{ session('success') }}');
    </script>
@endif
@endsection
