@extends('layouts.app')

@section('title', 'Agency Performance Reports - MCMC Portal')

@section('styles')
<style>
        /* Page-specific styles for MCMC Reports */
        .page-title {
            font-size: 2rem;
            margin-bottom: 30px;
            color: #1e3c72;
            border-bottom: 3px solid #2a5298;
            padding-bottom: 10px;
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .back-button {
            background: rgba(255, 255, 255, 0.2);
            border: 2px solid rgba(255, 255, 255, 0.3);
            color: white;
            padding: 10px 15px;
            border-radius: 8px;
            text-decoration: none;
            font-size: 0.9rem;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .back-button:hover {
            background: rgba(255, 255, 255, 0.3);
            border-color: rgba(255, 255, 255, 0.5);
            transform: translateY(-2px);
        }

        /* Form Controls */
        .form-group {
            display: flex;
            flex-direction: column;
        }

        .form-group label {
            font-weight: 600;
            color: #1e3c72;
            margin-bottom: 8px;
            font-size: 0.9rem;
        }

        .form-control {
            padding: 12px 15px;
            border: 2px solid #e9ecef;
            border-radius: 10px;
            font-size: 1rem;
            transition: all 0.3s ease;
            background: white;
        }

        .form-control:focus {
            outline: none;
            border-color: #2a5298;
            box-shadow: 0 0 0 3px rgba(42, 82, 152, 0.1);
        }

        .btn {
            padding: 12px 25px;
            border: none;
            border-radius: 10px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-block;
            text-align: center;
        }

        .btn-primary {
            background: linear-gradient(135deg, #2a5298 0%, #1e3c72 100%);
            color: white;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(30, 60, 114, 0.3);
        }

        .btn-success {
            background: #28a745;
            color: white;
        }

        .btn-success:hover {
            background: #218838;
            transform: translateY(-2px);
        }        .btn-info {
            background: #17a2b8;
            color: white;
        }

        .btn-info:hover {
            background: #138496;
            transform: translateY(-2px);
        }

        .btn-secondary {
            background: #6c757d;
            color: white;
        }

        .btn-secondary:hover {
            background: #5a6268;
            transform: translateY(-2px);
        }

        /* Reports Section Styles */
        .reports-section {
            background: white;
            padding: 25px;
            border-radius: 15px;
            margin-bottom: 30px;
            box-shadow: 0 8px 32px rgba(30, 60, 114, 0.1);
            border: 1px solid #e9ecef;
        }

        .section-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
            border-bottom: 2px solid #f8f9fa;
            padding-bottom: 15px;
        }

        .section-title {
            font-size: 1.5rem;
            color: #1e3c72;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .section-icon {
            font-size: 1.2rem;
        }

        .reports-filters {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 25px;
            padding: 20px;
            background: #f8f9fa;
            border-radius: 10px;
        }

        .agency-performance-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 20px;
            margin-bottom: 25px;
        }

        .performance-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            transition: all 0.3s ease;
        }

        .performance-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.2);
        }

        .agency-name {
            font-size: 1.1rem;
            font-weight: 600;
            margin-bottom: 15px;
            border-bottom: 1px solid rgba(255,255,255,0.3);
            padding-bottom: 8px;
        }

        .performance-metrics {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 15px;
        }

        .metric {
            text-align: center;
        }

        .metric-value {
            font-size: 1.8rem;
            font-weight: bold;
            display: block;
            margin-bottom: 5px;
        }

        .metric-label {
            font-size: 0.8rem;
            opacity: 0.9;
        }

        .reports-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            background: white;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }

        .reports-table th {
            background: linear-gradient(135deg, #2a5298 0%, #1e3c72 100%);
            color: white;
            padding: 15px;
            text-align: left;
            font-weight: 600;
            font-size: 0.9rem;
        }

        .reports-table td {
            padding: 12px 15px;
            border-bottom: 1px solid #e9ecef;
            font-size: 0.9rem;
        }

        .reports-table tr:hover {
            background: #f8f9fa;
        }

        .progress-bar {
            width: 100%;
            height: 8px;
            background: #e9ecef;
            border-radius: 4px;
            overflow: hidden;
            margin-top: 5px;
        }

        .progress-fill {
            height: 100%;
            background: linear-gradient(90deg, #28a745 0%, #20c997 100%);
            transition: width 0.3s ease;
        }

        .delay-indicator {
            padding: 4px 8px;
            border-radius: 12px;
            font-size: 0.75rem;
            font-weight: 600;
        }

        .delay-low {
            background: #d4edda;
            color: #155724;
        }

        .delay-medium {
            background: #fff3cd;
            color: #856404;
        }

        .delay-high {
            background: #f8d7da;
            color: #721c24;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .reports-filters {
                grid-template-columns: 1fr;
            }

            .agency-performance-grid {
                grid-template-columns: 1fr;
            }
        }
</style>
@endsection

@section('content')
<div class="container">
    <h1 class="page-title">
        <a href="{{ route('mcmc.progress.monitor') }}" class="back-button">
            <span>←</span> Back to Inquiries
        </a>
        📊 Agency Performance Reports
    </h1>

    <!-- Agency Performance Reports Section -->
    <div class="reports-section">
                    <div class="section-header">
                        <h2 class="section-title">
                            <span class="section-icon">📈</span>
                            Performance Analytics Dashboard
                        </h2>
                    </div>

                    <!-- Report Filters -->
                    <div class="reports-filters">
                        <div class="form-group">
                            <label for="reportDateFrom">Date From</label>
                            <input type="date" class="form-control" id="reportDateFrom">
                        </div>
                        <div class="form-group">
                            <label for="reportDateTo">Date To</label>
                            <input type="date" class="form-control" id="reportDateTo">
                        </div>
                        <div class="form-group">                            <label for="reportAgency">Agency</label>                            <select class="form-control" id="reportAgency">
                                <option value="">All Agencies</option>
                                @if(isset($agencies) && count($agencies) > 0)
                                    @foreach($agencies as $agency)
                                        <option value="{{ $agency->AgencyName }}">{{ $agency->AgencyName }}</option>
                                    @endforeach
                                @elseif(isset($agencyPerformance))
                                    @foreach(array_keys($agencyPerformance) as $agencyName)
                                        <option value="{{ $agencyName }}">{{ $agencyName }}</option>
                                    @endforeach
                                @else
                                    <option value="CyberSecurity Malaysia">CyberSecurity Malaysia</option>
                                    <option value="Ministry of Health Malaysia (MOH)">Ministry of Health Malaysia (MOH)</option>
                                    <option value="Royal Malaysia Police (PDRM)">Royal Malaysia Police (PDRM)</option>
                                    <option value="Ministry of Domestic Trade and Consumer Affairs (KPDN)">Ministry of Domestic Trade and Consumer Affairs (KPDN)</option>
                                    <option value="Ministry of Education (MOE)">Ministry of Education (MOE)</option>
                                    <option value="Ministry of Communications and Digital (KKD)">Ministry of Communications and Digital (KKD)</option>
                                    <option value="Department of Islamic Development Malaysia (JAKIM)">Department of Islamic Development Malaysia (JAKIM)</option>
                                    <option value="Election Commission of Malaysia (SPR)">Election Commission of Malaysia (SPR)</option>
                                    <option value="Malaysian Anti-Corruption Commission (MACC / SPRM)">Malaysian Anti-Corruption Commission (MACC / SPRM)</option>
                                    <option value="Department of Environment Malaysia (DOE)">Department of Environment Malaysia (DOE)</option>
                                @endif
                            </select>
                        </div>                        <div style="display: flex; gap: 10px; align-items: end;">
                            <button class="btn btn-primary" onclick="generateReports()">Generate Report</button>
                            <button class="btn btn-success" onclick="exportReport()">Export CSV</button>
                            <button class="btn btn-secondary" onclick="clearFilters()">Clear Filters</button>
                        </div>
                    </div>

                    <!-- Agency Performance Cards -->
                    <div class="agency-performance-grid" id="performanceCards">
                        <!-- Performance cards will be populated by JavaScript -->
                    </div>

                    <!-- Detailed Reports Table -->
                    <div style="margin-top: 30px;">
                        <h3 style="color: #1e3c72; margin-bottom: 20px; display: flex; align-items: center; gap: 10px;">
                            <span>📋</span>
                            Detailed Agency Performance
                        </h3>
                        <table class="reports-table" id="detailedReportsTable">
                            <thead>
                                <tr>
                                    <th>Agency</th>
                                    <th>Assigned</th>
                                    <th>Resolved</th>
                                    <th>Resolution Rate</th>
                                    <th>Avg. Time (Days)</th>
                                    <th>Pending</th>
                                    <th>Delays</th>
                                    <th>Status</th>
                                </tr>
                            </thead>                            <tbody id="reportsTableBody">
                                <!-- Table content will be populated by JavaScript -->
                                <!-- Fallback content in case JavaScript fails -->
                                @php
                                    $fallbackAgencies = [
                                        'CyberSecurity Malaysia',
                                        'Ministry of Health Malaysia (MOH)',
                                        'Royal Malaysia Police (PDRM)',
                                        'Ministry of Domestic Trade and Consumer Affairs (KPDN)',
                                        'Ministry of Education (MOE)',
                                        'Ministry of Communications and Digital (KKD)',
                                        'Department of Islamic Development Malaysia (JAKIM)',
                                        'Election Commission of Malaysia (SPR)',
                                        'Malaysian Anti-Corruption Commission (MACC / SPRM)',
                                        'Department of Environment Malaysia (DOE)'
                                    ];
                                @endphp
                                @foreach($fallbackAgencies as $agencyName)
                                    @php
                                        $agency = $agencyPerformance[$agencyName] ?? [
                                            'assigned' => 0,
                                            'resolved' => 0,
                                            'pending' => 0,
                                            'avgTime' => 0,
                                            'resolutionRate' => 0,
                                            'delays' => 0
                                        ];
                                        $delayClass = $agency['delays'] > 2 ? 'delay-high' : ($agency['delays'] > 0 ? 'delay-medium' : 'delay-low');
                                        $delayText = $agency['delays'] > 2 ? 'High' : ($agency['delays'] > 0 ? 'Medium' : 'Low');
                                    @endphp
                                    <tr class="server-side-row">
                                        <td><strong>{{ $agencyName }}</strong></td>
                                        <td>{{ $agency['assigned'] }}</td>
                                        <td>{{ $agency['resolved'] }}</td>
                                        <td>
                                            {{ $agency['resolutionRate'] }}%
                                            <div class="progress-bar">
                                                <div class="progress-fill" style="width: {{ $agency['resolutionRate'] }}%"></div>
                                            </div>
                                        </td>
                                        <td>{{ $agency['avgTime'] }} days</td>
                                        <td>{{ $agency['pending'] }}</td>
                                        <td>
                                            <span class="delay-indicator {{ $delayClass }}">{{ $delayText }} ({{ $agency['delays'] }})</span>
                                        </td>
                                        <td>
                                            <button class="btn btn-info" onclick="viewAgencyDetails('{{ $agencyName }}')" style="padding: 4px 8px; font-size: 0.8rem;">
                                                View Details
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Summary Insights -->
                    <div style="margin-top: 30px; display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px;">
                        <div style="background: linear-gradient(135deg, #28a745 0%, #20c997 100%); color: white; padding: 25px; border-radius: 12px; text-align: center;">
                            <div style="font-size: 2rem; font-weight: bold; margin-bottom: 10px;" id="bestPerformingAgency">-</div>
                            <div style="font-size: 0.9rem; opacity: 0.9;">Best Performing Agency</div>
                        </div>
                        <div style="background: linear-gradient(135deg, #dc3545 0%, #c82333 100%); color: white; padding: 25px; border-radius: 12px; text-align: center;">
                            <div style="font-size: 2rem; font-weight: bold; margin-bottom: 10px;" id="mostDelayedAgency">-</div>
                            <div style="font-size: 0.9rem; opacity: 0.9;">Most Delayed Agency</div>
                        </div>
                        <div style="background: linear-gradient(135deg, #6f42c1 0%, #5a32a3 100%); color: white; padding: 25px; border-radius: 12px; text-align: center;">
                            <div style="font-size: 2rem; font-weight: bold; margin-bottom: 10px;" id="avgResolutionTime">-</div>
                            <div style="font-size: 0.9rem; opacity: 0.9;">Overall Avg. Resolution (Days)</div>
                        </div>
                        <div style="background: linear-gradient(135deg, #fd7e14 0%, #e55a00 100%); color: white; padding: 25px; border-radius: 12px; text-align: center;">
                            <div style="font-size: 2rem; font-weight: bold; margin-bottom: 10px;" id="totalPendingInquiries">-</div>
                            <div style="font-size: 0.9rem; opacity: 0.9;">Total Pending Inquiries</div>
                        </div>
                    </div>
                </div>
            </div>
    
    <script>
        // Reports functionality
        function generateReports() {
            console.log('=== generateReports() called ===');
            
            // Get filter values
            const dateFrom = document.getElementById('reportDateFrom').value;
            const dateTo = document.getElementById('reportDateTo').value;
            const agency = document.getElementById('reportAgency').value;

            console.log('Filter values:', { dateFrom, dateTo, agency });
            
            // Use real data from Laravel controller
            const agencyData = @json($agencyPerformance ?? []);

            console.log('Agency Performance Data from Database:', agencyData);
            console.log('Number of agencies in data:', Object.keys(agencyData).length);
            
            try {
                // Generate reports with real data from database
                console.log('Calling generatePerformanceCards...');
                generatePerformanceCards(agencyData);
                
                console.log('Calling generateReportsTable...');
                generateReportsTable(agencyData);
                
                console.log('Calling generateSummaryInsights...');
                generateSummaryInsights(agencyData);
                
                console.log('All report generation functions completed successfully');
            } catch (error) {
                console.error('Error in generateReports:', error);
            }
        }        function generatePerformanceCards(data) {
            const container = document.getElementById('performanceCards');
            if (!container) {
                console.error('Performance cards container not found!');
                return;
            }
            
            // Get selected agency filter
            const selectedAgency = document.getElementById('reportAgency').value;
            console.log('Performance cards - Selected agency filter:', selectedAgency);
            
            container.innerHTML = '';
            
            // Get agency names from the actual data passed from the controller
            const allAgencies = Object.keys(data);
            console.log('Available agencies from database:', allAgencies);

            // Filter agencies based on selection
            let agenciesToShow = allAgencies;
            if (selectedAgency && selectedAgency !== '') {
                agenciesToShow = allAgencies.filter(agency => agency === selectedAgency);
                console.log('Performance cards - Filtering to show only:', selectedAgency);
            } else {
                console.log('Performance cards - Showing all agencies');
            }

            agenciesToShow.forEach(agencyName => {
                // Get agency data or use defaults
                const agency = data[agencyName] || {
                    assigned: 0,
                    resolved: 0,
                    pending: 0,
                    avgTime: 0,
                    resolutionRate: 0,
                    delays: 0
                };
                
                const resolutionRate = agency.resolutionRate || 0;
                
                const card = document.createElement('div');
                card.className = 'performance-card';
                card.innerHTML = `
                    <div class="agency-name">${agencyName}</div>
                    <div class="performance-metrics">
                        <div class="metric">
                            <span class="metric-value">${agency.assigned}</span>
                            <span class="metric-label">Assigned</span>
                        </div>
                        <div class="metric">
                            <span class="metric-value">${agency.resolved}</span>
                            <span class="metric-label">Resolved</span>
                        </div>
                        <div class="metric">
                            <span class="metric-value">${agency.avgTime}</span>
                            <span class="metric-label">Avg Days</span>
                        </div>
                        <div class="metric">
                            <span class="metric-value">${agency.pending}</span>
                            <span class="metric-label">Pending</span>
                        </div>
                    </div>
                `;
                container.appendChild(card);
            });
        }        function generateReportsTable(data) {
            console.log('=== generateReportsTable called ===');
            console.log('Data received:', data);
            
            const tbody = document.getElementById('reportsTableBody');
            if (!tbody) {
                console.error('Table body element not found!');
                return;
            }
            
            // Get selected agency filter
            const selectedAgency = document.getElementById('reportAgency').value;
            console.log('Selected agency filter:', selectedAgency);
            
            console.log('Table body found, current children:', tbody.children.length);
            
            // Clear existing content (including server-side fallback)
            tbody.innerHTML = '';
            console.log('Table body cleared');
            
            // Get agency names from the actual data passed from the controller
            const allAgencies = Object.keys(data);
            console.log('Available agencies from database:', allAgencies);
            
            // Filter agencies based on selection
            let agenciesToShow = allAgencies;
            if (selectedAgency && selectedAgency !== '') {
                agenciesToShow = allAgencies.filter(agency => agency === selectedAgency);
                console.log('Filtering to show only:', selectedAgency);
            } else {
                console.log('Showing all agencies');
            }
            
            console.log('Processing', agenciesToShow.length, 'agencies');
            
            // Process each agency
            agenciesToShow.forEach((agencyName, index) => {
                console.log(`Processing agency ${index + 1}: ${agencyName}`);
                
                // Get agency data or use defaults
                const agency = data[agencyName] || {
                    assigned: 0,
                    resolved: 0,
                    pending: 0,
                    avgTime: 0,
                    resolutionRate: 0,
                    delays: 0
                };
                
                console.log(`Agency data for ${agencyName}:`, agency);
                
                const resolutionRate = agency.resolutionRate || 0;
                
                let delayClass = 'delay-low';
                let delayText = 'Low';
                if (agency.delays > 2) {
                    delayClass = 'delay-high';
                    delayText = 'High';
                } else if (agency.delays > 0) {
                    delayClass = 'delay-medium';
                    delayText = 'Medium';
                }

                const row = document.createElement('tr');
                row.className = 'js-generated-row';
                row.innerHTML = `
                    <td><strong>${agencyName}</strong></td>
                    <td>${agency.assigned}</td>
                    <td>${agency.resolved}</td>
                    <td>
                        ${resolutionRate}%
                        <div class="progress-bar">
                            <div class="progress-fill" style="width: ${resolutionRate}%"></div>
                        </div>
                    </td>
                    <td>${agency.avgTime} days</td>
                    <td>${agency.pending}</td>
                    <td>
                        <span class="delay-indicator ${delayClass}">${delayText} (${agency.delays})</span>
                    </td>
                    <td>
                        <button class="btn btn-info" onclick="viewAgencyDetails('${agencyName}')" style="padding: 4px 8px; font-size: 0.8rem;">
                            View Details
                        </button>
                    </td>
                `;
                
                console.log(`Adding row ${index + 1} for ${agencyName}`);
                tbody.appendChild(row);
            });
            
            console.log('Table generation completed. Total rows in tbody:', tbody.children.length);
            
            // Verify rows were added
            const addedRows = tbody.querySelectorAll('.js-generated-row');
            console.log('JS-generated rows count:', addedRows.length);
        }        function generateSummaryInsights(data) {
            // Get selected agency filter
            const selectedAgency = document.getElementById('reportAgency').value;
            console.log('Summary insights - Selected agency filter:', selectedAgency);
            
            let bestAgency = '';
            let worstAgency = '';
            let bestRate = 0;
            let worstTime = 0;
            let totalResolved = 0;
            let totalAssigned = 0;
            let totalPending = 0;
            let totalTime = 0;
            let agencyCount = 0;

            // Filter data based on selected agency
            let agenciesToAnalyze = Object.keys(data);
            if (selectedAgency && selectedAgency !== '') {
                agenciesToAnalyze = agenciesToAnalyze.filter(agencyName => agencyName === selectedAgency);
                console.log('Summary insights - Analyzing only:', selectedAgency);
            } else {
                console.log('Summary insights - Analyzing all agencies');
            }

            agenciesToAnalyze.forEach(agencyName => {
                const agency = data[agencyName];
                const resolutionRate = agency.resolutionRate || (agency.assigned > 0 ? (agency.resolved / agency.assigned) * 100 : 0);
                
                if (resolutionRate > bestRate) {
                    bestRate = resolutionRate;
                    bestAgency = agencyName;
                }
                
                if (agency.avgTime > worstTime) {
                    worstTime = agency.avgTime;
                    worstAgency = agencyName;
                }
                
                totalResolved += agency.resolved;
                totalAssigned += agency.assigned;
                totalPending += agency.pending;
                totalTime += agency.avgTime;
                agencyCount++;
            });

            const avgTime = agencyCount > 0 ? (totalTime / agencyCount).toFixed(1) : '0';

            // Update summary cards
            document.getElementById('bestPerformingAgency').textContent = bestAgency || '-';
            document.getElementById('mostDelayedAgency').textContent = worstAgency || '-';
            document.getElementById('avgResolutionTime').textContent = avgTime;
            document.getElementById('totalPendingInquiries').textContent = totalPending;
        }function viewAgencyDetails(agencyName) {
            alert(`Viewing detailed performance for ${agencyName}.\n\nThis would open a detailed view with:\n- Individual inquiry tracking\n- Timeline analysis\n- Performance trends\n- Staff assignments`);
        }

        function clearFilters() {
            console.log('Clearing all filters');
            
            // Reset date filters to current month
            const today = new Date();
            const firstDay = new Date(today.getFullYear(), today.getMonth(), 1);
            
            const dateFromInput = document.getElementById('reportDateFrom');
            const dateToInput = document.getElementById('reportDateTo');
            const agencySelect = document.getElementById('reportAgency');
            
            if (dateFromInput) {
                dateFromInput.value = firstDay.toISOString().split('T')[0];
                console.log('Date From reset to:', dateFromInput.value);
            }
            
            if (dateToInput) {
                dateToInput.value = today.toISOString().split('T')[0];
                console.log('Date To reset to:', dateToInput.value);
            }
            
            if (agencySelect) {
                agencySelect.value = '';
                console.log('Agency filter reset to: All Agencies');
            }
            
            // Automatically regenerate reports with cleared filters
            console.log('Regenerating reports with cleared filters');
            generateReports();
        }

        function exportReport() {
            // Generate CSV content
            let csvContent = "Agency,Assigned,Resolved,Resolution Rate,Avg Time (Days),Pending,Delays\n";
            
            // Use real data from database
            const agencyData = @json($agencyPerformance ?? []);
            
            Object.keys(agencyData).forEach(agencyName => {
                const agency = agencyData[agencyName];
                const row = [
                    agencyName,
                    agency.assigned,
                    agency.resolved,
                    agency.resolutionRate + '%',
                    agency.avgTime,
                    agency.pending,
                    agency.delays
                ];
                csvContent += row.join(',') + '\n';
            });            // Create and download CSV file
            const blob = new Blob([csvContent], { type: 'text/csv' });
            const url = window.URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.href = url;
            a.download = `agency_performance_report_${new Date().toISOString().split('T')[0]}.csv`;
            document.body.appendChild(a);
            a.click();
            document.body.removeChild(a);
            window.URL.revokeObjectURL(url);
        }        // Initialize date filters with current month
        document.addEventListener('DOMContentLoaded', function() {
            console.log('=== DOM Content Loaded ===');
            console.log('DOM Content Loaded - initializing reports page');
            
            // Test if elements exist
            const dateFrom = document.getElementById('reportDateFrom');
            const dateTo = document.getElementById('reportDateTo');
            const tbody = document.getElementById('reportsTableBody');
            
            console.log('Elements found:', {
                dateFrom: !!dateFrom,
                dateTo: !!dateTo,
                tbody: !!tbody
            });
            
            const today = new Date();
            const firstDay = new Date(today.getFullYear(), today.getMonth(), 1);
            
            if (dateFrom) dateFrom.value = firstDay.toISOString().split('T')[0];
            if (dateTo) dateTo.value = today.toISOString().split('T')[0];
            
            // Test data availability
            const agencyData = @json($agencyPerformance ?? []);
            console.log('Agency data available:', agencyData);
            console.log('Agency data keys:', Object.keys(agencyData));
            
            // Force add test row to verify table access
            if (tbody) {
                console.log('Adding test row to verify table works');
                const testRow = document.createElement('tr');
                testRow.innerHTML = '<td colspan="8" style="text-align: center; padding: 20px; background: #ffffcc;">TEST ROW - JavaScript is working!</td>';
                tbody.appendChild(testRow);
            }
              // Auto-generate reports on page load
            console.log('Calling generateReports from DOMContentLoaded');
            generateReports();
            
            // Add event listener for agency dropdown changes
            const agencySelect = document.getElementById('reportAgency');
            if (agencySelect) {
                agencySelect.addEventListener('change', function() {
                    console.log('Agency dropdown changed to:', this.value);
                    generateReports();
                });
                console.log('Agency dropdown change listener added');
            }
        });
        
        // Backup mechanism - try again after a short delay
        setTimeout(function() {
            console.log('Backup mechanism - ensuring reports are generated');
            generateReports();
        }, 500);
    </script>
</div>
@endsection

@section('scripts')
<script>
// Additional scripts if needed
</script>
@endsection
