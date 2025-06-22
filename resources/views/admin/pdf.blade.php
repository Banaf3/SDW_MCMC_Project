<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Inquiry Report</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            line-height: 1.4;
            color: #333;
            margin: 20px;
        }
        
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #4e73df;
            padding-bottom: 15px;
        }
        
        .header h1 {
            color: #4e73df;
            margin: 0;
            font-size: 24px;
        }
        
        .header p {
            margin: 5px 0;
            color: #666;
        }
        
        .stats-section {
            margin-bottom: 30px;
        }
        
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 15px;
            margin-bottom: 20px;
        }
        
        .stat-card {
            border: 1px solid #ddd;
            padding: 15px;
            border-radius: 5px;
            text-align: center;
        }
        
        .stat-card h3 {
            margin: 0 0 5px 0;
            font-size: 24px;
            color: #4e73df;
        }
        
        .stat-card p {
            margin: 0;
            color: #666;
            font-size: 11px;
            text-transform: uppercase;
        }
        
        .table-section {
            margin-top: 30px;
        }
        
        .section-title {
            font-size: 16px;
            font-weight: bold;
            color: #4e73df;
            margin-bottom: 15px;
            padding-bottom: 5px;
            border-bottom: 1px solid #ddd;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
            font-size: 10px;
        }
        
        th {
            background-color: #f8f9fc;
            font-weight: bold;
            color: #5a5c69;
        }
        
        .status-badge {
            padding: 2px 6px;
            border-radius: 3px;
            font-size: 9px;
            font-weight: bold;
            text-transform: uppercase;
        }
        
        .status-pending {
            background-color: #f6c23e;
            color: #1f2937;
        }
        
        .status-progress {
            background-color: #36b9cc;
            color: white;
        }
        
        .status-resolved {
            background-color: #1cc88a;
            color: white;
        }
        
        .status-rejected {
            background-color: #e74a3b;
            color: white;
        }
        
        .footer {
            position: fixed;
            bottom: 20px;
            left: 20px;
            right: 20px;
            text-align: center;
            font-size: 10px;
            color: #666;
            border-top: 1px solid #ddd;
            padding-top: 10px;
        }
        
        .page-break {
            page-break-before: always;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>MCMC Inquiry Management Report</h1>
        <p>Generated on {{ date('F j, Y \a\t g:i A') }}</p>
        <p>Malaysian Communications and Multimedia Commission</p>
    </div>

    <div class="stats-section">
        <div class="section-title">Summary Statistics</div>
        
        <div class="stats-grid">
            <div class="stat-card">
                <h3>{{ number_format($stats['total_inquiries']) }}</h3>
                <p>Total Inquiries</p>
            </div>
            
            <div class="stat-card">
                <h3>{{ number_format($stats['pending_inquiries']) }}</h3>
                <p>Pending</p>
            </div>
            
            <div class="stat-card">
                <h3>{{ number_format($stats['in_progress_inquiries']) }}</h3>
                <p>In Progress</p>
            </div>
            
            <div class="stat-card">
                <h3>{{ number_format($stats['resolved_inquiries']) }}</h3>
                <p>Resolved</p>
            </div>
        </div>
        
        <table>
            <tr>
                <th style="width: 50%;">Metric</th>
                <th style="width: 50%;">Value</th>
            </tr>
            <tr>
                <td>Average Resolution Time</td>
                <td>{{ $stats['avg_resolution_time'] }} days</td>
            </tr>
            <tr>
                <td>Most Active Agency</td>
                <td>{{ $stats['agency_counts']->keys()->first() ?? 'N/A' }} ({{ $stats['agency_counts']->first() ?? 0 }} inquiries)</td>
            </tr>
            <tr>
                <td>Most Common Status</td>
                <td>{{ $stats['status_counts']->keys()->first() ?? 'N/A' }} ({{ $stats['status_counts']->first() ?? 0 }} inquiries)</td>
            </tr>
            <tr>
                <td>Peak Submission Month</td>
                <td>{{ $stats['monthly_submissions']->keys()->last() ?? 'N/A' }} ({{ $stats['monthly_submissions']->max() ?? 0 }} inquiries)</td>
            </tr>
        </table>
    </div>

    @if($stats['status_counts']->count() > 0)
    <div class="stats-section">
        <div class="section-title">Status Distribution</div>
        <table>
            <thead>
                <tr>
                    <th>Status</th>
                    <th>Count</th>
                    <th>Percentage</th>
                </tr>
            </thead>
            <tbody>
                @foreach($stats['status_counts'] as $status => $count)
                <tr>
                    <td>{{ $status }}</td>
                    <td>{{ $count }}</td>
                    <td>{{ round(($count / $stats['total_inquiries']) * 100, 1) }}%</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif

    @if($stats['agency_counts']->count() > 0)
    <div class="stats-section">
        <div class="section-title">Agency Distribution</div>
        <table>
            <thead>
                <tr>
                    <th>Agency</th>
                    <th>Inquiries Assigned</th>
                    <th>Percentage of Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach($stats['agency_counts'] as $agency => $count)
                <tr>
                    <td>{{ $agency }}</td>
                    <td>{{ $count }}</td>
                    <td>{{ round(($count / $stats['total_inquiries']) * 100, 1) }}%</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif

    <div class="page-break"></div>

    <div class="table-section">
        <div class="section-title">Inquiry Details ({{ $inquiries->count() }} records)</div>
        
        <table>
            <thead>
                <tr>
                    <th style="width: 8%;">ID</th>
                    <th style="width: 25%;">Title</th>
                    <th style="width: 12%;">Submission</th>
                    <th style="width: 12%;">Status</th>
                    <th style="width: 20%;">Agency</th>
                    <th style="width: 23%;">Description</th>
                </tr>
            </thead>
            <tbody>
                @foreach($inquiries as $inquiry)
                <tr>
                    <td>{{ $inquiry->InquiryID }}</td>
                    <td>{{ Str::limit($inquiry->InquiryTitle, 30) }}</td>
                    <td>{{ $inquiry->SubmitionDate->format('Y-m-d') }}</td>
                    <td>
                        <span class="status-badge 
                            @if($inquiry->InquiryStatus == 'Pending') status-pending
                            @elseif($inquiry->InquiryStatus == 'In Progress') status-progress
                            @elseif($inquiry->InquiryStatus == 'Resolved') status-resolved
                            @elseif($inquiry->InquiryStatus == 'Rejected') status-rejected
                            @endif">
                            {{ $inquiry->InquiryStatus }}
                        </span>
                    </td>
                    <td>{{ $inquiry->agency ? Str::limit($inquiry->agency->AgencyName, 20) : 'Unassigned' }}</td>
                    <td>{{ Str::limit($inquiry->InquiryDescription, 40) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="footer">
        <p>This report was generated by the MCMC Inquiry Management System</p>
        <p>For questions or support, please contact the MCMC IT Department</p>
    </div>
</body>
</html>
