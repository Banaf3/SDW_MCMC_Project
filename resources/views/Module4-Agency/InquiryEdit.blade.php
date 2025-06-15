<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>VeriTrack - Edit Inquiry</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
            min-height: 100vh;
            color: #333;
        }

        /* Main Container */
        .main-container { 
            display: flex; 
            min-height: 100vh;
            padding-top: 80px; /* Account for fixed header */
        }

        /* Content Area */
        .content-area {
            flex: 1;
            margin-left: 250px; /* Account for sidebar width */
            padding: 20px;
        }

        /* Header Styles */
        .header { 
            background: linear-gradient(135deg, #4c6ef5 0%, #45c649 100%);
            color: white; 
            padding: 0.75rem 0; 
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1000;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        
        .header-container { 
            display: flex; 
            justify-content: space-between; 
            align-items: center; 
            max-width: 100%; 
            margin: 0 auto; 
            padding: 0 2rem; 
        }
        
        .logo h1 { 
            font-size: 1.5rem; 
            font-weight: 600;
            letter-spacing: -0.5px;
        }
        
        /* Header User Info */
        .header-user-info {
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }
        
        .header-user-info .user-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background-color: rgba(255, 255, 255, 0.2);
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            font-size: 1rem;
            color: white;
            border: 2px solid rgba(255, 255, 255, 0.3);
        }
        
        .user-details {
            display: flex;
            flex-direction: column;
            align-items: flex-end;
        }
        
        .user-details .user-name {
            font-weight: 600;
            margin-bottom: 0.125rem;
            color: white;
            font-size: 0.9rem;
        }
        
        .user-details .user-role {
            font-size: 0.75rem;
            color: rgba(255, 255, 255, 0.8);
        }

        /* Agency Sidebar Styles */        
        .sidebar { 
            width: 250px; 
            background: linear-gradient(180deg, #495057 0%, #343a40 100%);
            color: white; 
            padding: 1.5rem 0;
            position: fixed;
            left: 0;
            top: 60px;
            bottom: 0;
            overflow-y: auto;
            z-index: 999;
            box-shadow: 2px 0 10px rgba(0,0,0,0.1);
        }
        
        .sidebar-nav ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }
        
        .nav-section h3 {
            padding: 0.75rem 1.5rem;
            font-size: 0.875rem;
            font-weight: 600;
            color: #adb5bd;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 0.5rem;
        }
        
        .nav-item .nav-toggle {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0.75rem 1.5rem;
            color: #e9ecef;
            text-decoration: none;
            transition: all 0.2s ease;
            cursor: pointer;
        }
        
        .nav-item .nav-toggle:hover {
            background-color: rgba(255, 255, 255, 0.1);
            color: white;
        }

        /* Main Content */
        .main-content {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 15px;
            padding: 30px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
            backdrop-filter: blur(10px);
        }

        .page-title {
            font-size: 2rem;
            margin-bottom: 10px;
            color: #1e3c72;
            border-bottom: 3px solid #2a5298;
            padding-bottom: 10px;
        }

        .inquiry-id-display {
            font-family: 'Courier New', monospace;
            background: #1e3c72;
            color: white;
            padding: 8px 16px;
            border-radius: 6px;
            font-size: 1rem;
            font-weight: bold;
            display: inline-block;
            margin-bottom: 30px;
        }

        /* Form Styles */
        .edit-form {
            background: #f8f9fa;
            padding: 30px;
            border-radius: 15px;
            margin-bottom: 30px;
        }

        .form-section {
            margin-bottom: 30px;
        }

        .section-title {
            font-size: 1.3rem;
            color: #1e3c72;
            margin-bottom: 15px;
            font-weight: 600;
            border-bottom: 2px solid #e9ecef;
            padding-bottom: 8px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #495057;
        }

        .form-control {
            width: 100%;
            padding: 12px;
            border: 2px solid #e9ecef;
            border-radius: 8px;
            font-size: 1rem;
            transition: border-color 0.3s ease, box-shadow 0.3s ease;
        }

        .form-control:focus {
            outline: none;
            border-color: #2a5298;
            box-shadow: 0 0 0 3px rgba(42, 82, 152, 0.1);
        }

        select.form-control {
            cursor: pointer;
        }

        textarea.form-control {
            min-height: 120px;
            resize: vertical;
        }

        /* Status Options */
        .status-options {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
        }

        .status-option {
            position: relative;
        }

        .status-option input[type="radio"] {
            position: absolute;
            opacity: 0;
            cursor: pointer;
        }

        .status-option label {
            display: block;
            padding: 15px;
            border: 2px solid #e9ecef;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.3s ease;
            text-align: center;
            font-weight: 600;
        }

        .status-option input[type="radio"]:checked + label {
            border-color: #2a5298;
            background: #2a5298;
            color: white;
        }

        .status-option label:hover {
            border-color: #2a5298;
            background: rgba(42, 82, 152, 0.1);
        }

        /* File Upload */
        .file-upload-area {
            border: 2px dashed #e9ecef;
            border-radius: 8px;
            padding: 30px;
            text-align: center;
            transition: all 0.3s ease;
        }

        .file-upload-area:hover {
            border-color: #2a5298;
            background: rgba(42, 82, 152, 0.05);
        }

        .file-upload-area.dragover {
            border-color: #2a5298;
            background: rgba(42, 82, 152, 0.1);
        }

        .upload-icon {
            font-size: 3rem;
            color: #6c757d;
            margin-bottom: 15px;
        }

        .upload-text {
            font-size: 1.1rem;
            color: #6c757d;
            margin-bottom: 10px;
        }

        .upload-subtext {
            font-size: 0.9rem;
            color: #adb5bd;
        }

        /* Button Styles */
        .btn {
            padding: 12px 25px;
            border: none;
            border-radius: 8px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-block;
            text-align: center;
        }

        .btn-primary {
            background: linear-gradient(45deg, #1e3c72, #2a5298);
            color: white;
        }

        .btn-primary:hover {
            background: linear-gradient(45deg, #2a5298, #1e3c72);
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(30, 60, 114, 0.25);
        }

        .btn-secondary {
            background: #6c757d;
            color: white;
        }

        .btn-secondary:hover {
            background: #5a6268;
            transform: translateY(-2px);
        }

        .btn-success {
            background: linear-gradient(45deg, #28a745, #20c997);
            color: white;
        }

        .btn-success:hover {
            background: linear-gradient(45deg, #20c997, #28a745);
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(40, 167, 69, 0.25);
        }

        .action-buttons {
            display: flex;
            gap: 15px;
            margin-top: 30px;
        }        /* Alert Styles */
        .alert {
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
        }

        .alert-success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .alert-error {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        /* Evidence Display Styles */
        .evidence-display {
            background: #f8f9fa;
            border: 2px solid #e9ecef;
            border-radius: 8px;
            padding: 20px;
            min-height: 100px;
        }

        .file-list {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .file-item {
            display: flex;
            align-items: center;
            gap: 12px;
            background: white;
            padding: 12px;
            border-radius: 6px;
            border: 1px solid #dee2e6;
            transition: box-shadow 0.2s ease;
        }

        .file-item:hover {
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        .file-icon {
            font-size: 1.2rem;
            color: #6c757d;
        }

        .file-name {
            flex: 1;
            font-weight: 500;
            color: #495057;
            word-break: break-all;
        }

        .btn-sm {
            padding: 6px 12px;
            font-size: 0.875rem;
            line-height: 1.5;
            border-radius: 4px;
        }

        .btn-outline-primary {
            color: #2a5298;
            border: 1px solid #2a5298;
            background: transparent;
            text-decoration: none;
            transition: all 0.2s ease;
        }

        .btn-outline-primary:hover {
            background: #2a5298;
            color: white;
        }

        .file-status {
            color: #dc3545;
            font-size: 0.875rem;
            font-style: italic;
        }

        .empty-evidence {
            text-align: center;
            color: #6c757d;
            font-style: italic;
            padding: 30px;
        }

        .empty-evidence p {
            margin: 0;
            font-size: 1rem;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .content-area {
                margin-left: 0;
                padding: 10px;
            }

            .sidebar {
                transform: translateX(-100%);
                transition: transform 0.3s ease;
            }

            .sidebar.show {
                transform: translateX(0);
            }

            .status-options {
                grid-template-columns: 1fr;
            }

            .action-buttons {
                flex-direction: column;
            }

            .header-container {
                padding: 0 1rem;
            }
        }
    </style>
</head>
<body>
    <!-- Include Header -->
    @include('layouts.partials.header')
    
    <div class="main-container">
        <!-- Agency Sidebar -->
        <div class="sidebar">
            <nav class="sidebar-nav">
                <ul>
                    <li class="nav-section">
                        <h3>Agency Dashboard</h3>
                    </li>
                    <li class="nav-item">
                        <div class="nav-toggle">
                            <span>📊 Overview</span>
                        </div>
                    </li>
                    <li class="nav-item">
                        <div class="nav-toggle">
                            <span>📋 Inquiry Management</span>
                        </div>
                    </li>
                    <li class="nav-item">
                        <div class="nav-toggle">
                            <span>📈 Reports</span>
                        </div>
                    </li>
                    <li class="nav-item">
                        <div class="nav-toggle">
                            <span>⚙️ Settings</span>
                        </div>
                    </li>
                </ul>
            </nav>
        </div>
        
        <div class="content-area">
            <!-- Main Content -->
            <div class="main-content">
                <h1 class="page-title">Edit Inquiry</h1>
                <div class="inquiry-id-display">ID: {{ $inquiry['InquiryID'] ?? 'N/A' }}</div>

                @if(session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-error">
                        {{ session('error') }}
                    </div>
                @endif

                <!-- Edit Form -->
                <form action="/agency/inquiry-update/{{ $inquiry['InquiryID'] ?? '' }}" method="POST" enctype="multipart/form-data" class="edit-form">
                    @csrf
                    @method('PUT')

                    <!-- Investigation Status Section -->
                    <div class="form-section">
                        <h3 class="section-title">🔍 Investigation Status</h3>
                        <div class="form-group">
                            <label>Current Status: <strong>{{ $inquiry['InquiryStatus'] ?? 'Unknown' }}</strong></label>
                            <div class="status-options">
                                <div class="status-option">
                                    <input type="radio" id="under-investigation" name="InquiryStatus" value="Under Investigation" 
                                           {{ ($inquiry['InquiryStatus'] ?? '') == 'Under Investigation' ? 'checked' : '' }}>
                                    <label for="under-investigation">Under Investigation</label>
                                </div>
                                <div class="status-option">
                                    <input type="radio" id="verified-true" name="InquiryStatus" value="Verified as True" 
                                           {{ ($inquiry['InquiryStatus'] ?? '') == 'Verified as True' ? 'checked' : '' }}>
                                    <label for="verified-true">Verified as True</label>
                                </div>
                                <div class="status-option">
                                    <input type="radio" id="identified-fake" name="InquiryStatus" value="Identified as Fake" 
                                           {{ ($inquiry['InquiryStatus'] ?? '') == 'Identified as Fake' ? 'checked' : '' }}>
                                    <label for="identified-fake">Identified as Fake</label>
                                </div>
                                <div class="status-option">
                                    <input type="radio" id="rejected" name="InquiryStatus" value="Rejected" 
                                           {{ ($inquiry['InquiryStatus'] ?? '') == 'Rejected' ? 'checked' : '' }}>
                                    <label for="rejected">Rejected</label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Resolved Explanation Section -->
                    <div class="form-section">
                        <h3 class="section-title">📝 Resolution Details</h3>
                        <div class="form-group">
                            <label for="ResolvedExplanation">Resolved Explanation</label>
                            <textarea name="ResolvedExplanation" id="ResolvedExplanation" class="form-control" 
                                      placeholder="Provide detailed explanation of the investigation findings and resolution...">{{ $inquiry['ResolvedExplanation'] ?? '' }}</textarea>
                        </div>
                    </div>

                    <!-- Supporting Documents Section -->
                    <div class="form-section">
                        <h3 class="section-title">📎 Supporting Documents</h3>
                        <div class="form-group">
                            <label for="ResolvedSupportingDocs">Upload Supporting Documents</label>
                            <div class="file-upload-area" id="fileUploadArea">
                                <div class="upload-icon">📄</div>
                                <div class="upload-text">Click to upload or drag and drop</div>
                                <div class="upload-subtext">PDF, DOC, DOCX, JPG, PNG (Max 10MB)</div>
                                <input type="file" name="ResolvedSupportingDocs[]" id="ResolvedSupportingDocs" 
                                       class="form-control" multiple accept=".pdf,.doc,.docx,.jpg,.jpeg,.png" style="display: none;">
                            </div>
                            @if(isset($inquiry['ResolvedSupportingDocs']) && $inquiry['ResolvedSupportingDocs'])
                                <div style="margin-top: 10px;">
                                    <strong>Current Documents:</strong>
                                    <p>{{ $inquiry['ResolvedSupportingDocs'] }}</p>
                                </div>
                            @endif
                        </div>
                    </div>                    <!-- Inquiry Details (Read-only) -->
                    <div class="form-section">
                        <h3 class="section-title">📋 Inquiry Details</h3>
                        <div class="form-group">
                            <label>Title</label>
                            <input type="text" class="form-control" value="{{ $inquiry['InquiryTitle'] ?? 'N/A' }}" readonly>
                        </div>
                        <div class="form-group">
                            <label>Description</label>
                            <textarea class="form-control" readonly>{{ $inquiry['InquiryDescription'] ?? 'No description available' }}</textarea>
                        </div>
                        <div class="form-group">
                            <label>Submitted Date</label>
                            <input type="text" class="form-control" value="{{ $inquiry['SubmitionDate'] ?? 'N/A' }}" readonly>
                        </div>
                    </div>

                    <!-- Supporting Documents from User -->
                    <div class="form-section">
                        <h3 class="section-title">📎 Supporting Documents from User</h3>
                        <div class="form-group">
                            <label>User Submitted Evidence</label>
                            @if(!empty($inquiry['InquiryEvidence']))
                                <div class="evidence-display">
                                    @php
                                        // Split the evidence string by comma if multiple files
                                        $evidenceFiles = array_filter(array_map('trim', explode(',', $inquiry['InquiryEvidence'])));
                                    @endphp
                                    @if(count($evidenceFiles) > 0)
                                        <div class="file-list">
                                            @foreach($evidenceFiles as $file)
                                                <div class="file-item">
                                                    <span class="file-icon">📄</span>
                                                    <span class="file-name">{{ $file }}</span>
                                                    @if(file_exists(storage_path('app/public/inquiry_evidence/' . $file)))
                                                        <a href="{{ asset('storage/inquiry_evidence/' . $file) }}" 
                                                           target="_blank" 
                                                           class="btn btn-sm btn-outline-primary">View</a>
                                                    @else
                                                        <span class="file-status">File not found</span>
                                                    @endif
                                                </div>
                                            @endforeach
                                        </div>
                                    @else
                                        <div class="empty-evidence">
                                            <p>No supporting documents provided by the user.</p>
                                        </div>
                                    @endif
                                </div>
                            @else
                                <div class="empty-evidence">
                                    <p>No supporting documents provided by the user.</p>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="action-buttons">
                        <button type="submit" class="btn btn-success">Update Inquiry</button>
                        <a href="/agency/inquiries/assigned" class="btn btn-secondary">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        // File upload functionality
        document.addEventListener('DOMContentLoaded', function() {
            const fileUploadArea = document.getElementById('fileUploadArea');
            const fileInput = document.getElementById('ResolvedSupportingDocs');

            // Click to upload
            fileUploadArea.addEventListener('click', function() {
                fileInput.click();
            });

            // Drag and drop functionality
            fileUploadArea.addEventListener('dragover', function(e) {
                e.preventDefault();
                fileUploadArea.classList.add('dragover');
            });

            fileUploadArea.addEventListener('dragleave', function(e) {
                e.preventDefault();
                fileUploadArea.classList.remove('dragover');
            });

            fileUploadArea.addEventListener('drop', function(e) {
                e.preventDefault();
                fileUploadArea.classList.remove('dragover');
                
                const files = e.dataTransfer.files;
                fileInput.files = files;
                updateFileDisplay(files);
            });

            // File input change
            fileInput.addEventListener('change', function() {
                updateFileDisplay(this.files);
            });            function updateFileDisplay(files) {
                if (files.length > 0) {
                    const fileNames = Array.from(files).map(file => file.name).join(', ');
                    document.querySelector('.upload-text').textContent = `Selected: ${fileNames}`;
                } else {
                    document.querySelector('.upload-text').textContent = 'Click to upload or drag and drop';
                }
            }

            // Status update functionality
            const statusRadios = document.querySelectorAll('input[name="InquiryStatus"]');
            statusRadios.forEach(radio => {
                radio.addEventListener('change', function() {
                    if (this.checked) {
                        updateInquiryStatus(this.value);
                    }
                });
            });

            function updateInquiryStatus(status) {
                const inquiryId = {{ $inquiry['InquiryID'] ?? 0 }};
                
                // Show loading indicator
                const currentStatusLabel = document.querySelector('.form-group label strong');
                const originalText = currentStatusLabel.textContent;
                currentStatusLabel.textContent = 'Updating...';
                currentStatusLabel.style.color = '#007bff';

                // Make AJAX request
                fetch(`/agency/inquiry-status-update/${inquiryId}`, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || 
                                       document.querySelector('input[name="_token"]')?.value
                    },
                    body: JSON.stringify({
                        InquiryStatus: status
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Update the current status display
                        currentStatusLabel.textContent = status;
                        currentStatusLabel.style.color = '#28a745';
                        
                        // Show success message
                        showStatusMessage('Status updated successfully!', 'success');
                        
                        // Reset color after 2 seconds
                        setTimeout(() => {
                            currentStatusLabel.style.color = '';
                        }, 2000);
                    } else {
                        // Revert the radio button selection
                        currentStatusLabel.textContent = originalText;
                        currentStatusLabel.style.color = '#dc3545';
                        showStatusMessage('Error updating status: ' + (data.message || 'Unknown error'), 'error');
                        
                        // Reset color after 3 seconds
                        setTimeout(() => {
                            currentStatusLabel.style.color = '';
                        }, 3000);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    currentStatusLabel.textContent = originalText;
                    currentStatusLabel.style.color = '#dc3545';
                    showStatusMessage('Network error. Please try again.', 'error');
                    
                    // Reset color after 3 seconds
                    setTimeout(() => {
                        currentStatusLabel.style.color = '';
                    }, 3000);
                });
            }

            function showStatusMessage(message, type) {
                // Remove existing status messages
                const existingMessages = document.querySelectorAll('.status-message');
                existingMessages.forEach(msg => msg.remove());

                // Create new message
                const messageDiv = document.createElement('div');
                messageDiv.className = `alert alert-${type === 'success' ? 'success' : 'error'} status-message`;
                messageDiv.textContent = message;
                messageDiv.style.marginBottom = '20px';
                
                // Insert after the inquiry ID display
                const inquiryIdDisplay = document.querySelector('.inquiry-id-display');
                inquiryIdDisplay.parentNode.insertBefore(messageDiv, inquiryIdDisplay.nextSibling);
                
                // Auto-remove after 5 seconds
                setTimeout(() => {
                    messageDiv.remove();
                }, 5000);
            }
        });
    </script>
</body>
</html>