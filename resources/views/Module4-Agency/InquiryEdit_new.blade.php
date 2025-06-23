@extends('layouts.app')

@section('title', 'Edit Inquiry - Agency Portal')

@section('styles')
<style>
    .page-title {
        font-size: 2rem;
        margin-bottom: 30px;
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
        margin-bottom: 20px;
    }

    .edit-form {
        background: white;
        border-radius: 15px;
        padding: 30px;
        box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
        border: 2px solid #e9ecef;
    }

    .form-section {
        margin-bottom: 30px;
        padding-bottom: 20px;
        border-bottom: 1px solid #e9ecef;
    }

    .form-section:last-child {
        border-bottom: none;
    }

    .section-title {
        font-size: 1.25rem;
        color: #1e3c72;
        margin-bottom: 20px;
        font-weight: 600;
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

    .form-control[readonly] {
        background-color: #f8f9fa;
        cursor: not-allowed;
    }

    textarea.form-control {
        min-height: 120px;
        resize: vertical;
    }

    .status-options {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 15px;
        margin-top: 10px;
    }

    .status-option {
        display: flex;
        align-items: center;
        padding: 15px;
        border: 2px solid #e9ecef;
        border-radius: 8px;
        transition: all 0.3s ease;
        cursor: pointer;
    }

    .status-option:hover {
        border-color: #2a5298;
        background-color: #f8f9fa;
    }

    .status-option input[type="radio"] {
        margin-right: 10px;
        transform: scale(1.2);
    }

    .status-option input[type="radio"]:checked + label {
        color: #2a5298;
        font-weight: 600;
    }

    .file-upload-area {
        border: 2px dashed #e9ecef;
        border-radius: 8px;
        padding: 40px;
        text-align: center;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .file-upload-area:hover {
        border-color: #2a5298;
        background-color: #f8f9fa;
    }

    .upload-icon {
        font-size: 3rem;
        margin-bottom: 10px;
    }

    .upload-text {
        font-size: 1.1rem;
        margin-bottom: 5px;
        color: #495057;
    }

    .upload-subtext {
        font-size: 0.9rem;
        color: #6c757d;
    }

    .evidence-display {
        background: #f8f9fa;
        border-radius: 8px;
        padding: 20px;
    }

    .file-list {
        display: grid;
        gap: 10px;
    }

    .file-item {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 10px;
        background: white;
        border-radius: 6px;
        border: 1px solid #e9ecef;
    }

    .file-icon {
        font-size: 1.5rem;
    }

    .file-name {
        flex: 1;
        font-weight: 500;
    }

    .file-status {
        color: #dc3545;
        font-size: 0.9rem;
    }

    .empty-evidence {
        text-align: center;
        color: #6c757d;
        font-style: italic;
        padding: 20px;
    }

    .action-buttons {
        margin-top: 30px;
        display: flex;
        gap: 15px;
        justify-content: flex-end;
    }

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

    .btn-success {
        background: linear-gradient(45deg, #28a745, #20c997);
        color: white;
    }

    .btn-success:hover {
        background: linear-gradient(45deg, #20c997, #28a745);
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(40, 167, 69, 0.25);
    }

    .btn-secondary {
        background: #6c757d;
        color: white;
    }

    .btn-secondary:hover {
        background: #5a6268;
        transform: translateY(-2px);
    }

    .btn-sm {
        padding: 6px 12px;
        font-size: 0.875rem;
    }

    .btn-outline-primary {
        background: transparent;
        color: #2a5298;
        border: 2px solid #2a5298;
    }

    .btn-outline-primary:hover {
        background: #2a5298;
        color: white;
    }

    .alert {
        padding: 15px;
        margin-bottom: 20px;
        border-radius: 8px;
        border: 1px solid transparent;
    }

    .alert-success {
        background: #d4edda;
        color: #155724;
        border-color: #c3e6cb;
    }

    .alert-error {
        background: #f8d7da;
        color: #721c24;
        border-color: #f5c6cb;
    }

    @media (max-width: 768px) {
        .status-options {
            grid-template-columns: 1fr;
        }
        
        .action-buttons {
            flex-direction: column;
        }
        
        .file-item {
            flex-direction: column;
            align-items: flex-start;
            gap: 8px;
        }
    }
</style>
@endsection

@section('content')
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

@if($errors->any())
    <div class="alert alert-error">
        <ul style="margin: 0; padding-left: 20px;">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<!-- Edit Form -->
<form action="{{ route('agency.inquiry.update', $inquiry['InquiryID'] ?? 1) }}" method="POST" enctype="multipart/form-data" class="edit-form">
    @csrf
    @method('POST')

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
    </div>

    <!-- Inquiry Details (Read-only) -->
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
        <a href="{{ route('agency.progress.update') }}" class="btn btn-secondary">Cancel</a>
    </div>
</form>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // File upload area click handler
    const fileUploadArea = document.getElementById('fileUploadArea');
    const fileInput = document.getElementById('ResolvedSupportingDocs');
    
    if (fileUploadArea && fileInput) {
        fileUploadArea.addEventListener('click', function() {
            fileInput.click();
        });
        
        fileInput.addEventListener('change', function() {
            if (this.files.length > 0) {
                const fileNames = Array.from(this.files).map(file => file.name).join(', ');
                fileUploadArea.querySelector('.upload-text').textContent = `Selected: ${fileNames}`;
            }
        });
    }
});
</script>
@endsection
