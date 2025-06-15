@extends('layouts.app')

@section('title', 'Submit New Inquiry (Test Mode)')

@push('meta')
<meta name="csrf-token" content="{{ csrf_token() }}">
@endpush

@section('content')
<style>
/* Custom styles for the inquiry form */
.inquiry-container {
    max-width: 800px;
    margin: 0 auto;
    padding: 20px;
}

.form-section {
    background: white;
    border-radius: 8px;
    border: 1px solid #e2e8f0;
    padding: 24px;
    margin-bottom: 24px;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
}

.form-section h2 {
    color: #1a202c;
    font-size: 1.25rem;
    font-weight: 600;
    margin-bottom: 16px;
}

.form-group {
    margin-bottom: 20px;
}

.form-label {
    display: block;
    font-weight: 500;
    color: #374151;
    margin-bottom: 6px;
    font-size: 14px;
}

.required {
    color: #ef4444;
}

.form-input {
    width: 100%;
    padding: 10px 12px;
    border: 1px solid #d1d5db;
    border-radius: 6px;
    font-size: 14px;
    transition: border-color 0.2s;
}

.form-input:focus {
    outline: none;
    border-color: #3b82f6;
    box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.1);
}

.form-input.error {
    border-color: #ef4444;
}

.form-textarea {
    resize: vertical;
    min-height: 120px;
}

.help-text {
    font-size: 12px;
    color: #6b7280;
    margin-top: 4px;
}

.error-text {
    font-size: 12px;
    color: #ef4444;
    margin-top: 4px;
}

.file-upload-area {
    border: 2px dashed #d1d5db;
    border-radius: 8px;
    padding: 40px 24px;
    text-align: center;
    transition: border-color 0.2s;
    cursor: pointer;
}

.file-upload-area:hover {
    border-color: #9ca3af;
    background-color: #f9fafb;
}

.file-upload-icon {
    width: 48px;
    height: 48px;
    margin: 0 auto 12px;
    color: #9ca3af;
}

.file-upload-text {
    color: #374151;
    font-size: 14px;
    margin-bottom: 4px;
}

.file-upload-subtext {
    color: #6b7280;
    font-size: 12px;
}

.btn {
    padding: 12px 24px;
    border-radius: 6px;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.2s;
    border: none;
    font-size: 14px;
}

.btn-primary {
    background-color: #3b82f6;
    color: white;
}

.btn-primary:hover {
    background-color: #2563eb;
}

.btn-secondary {
    background-color: white;
    color: #374151;
    border: 1px solid #d1d5db;
}

.btn-secondary:hover {
    background-color: #f9fafb;
}

.form-actions {
    display: flex;
    gap: 12px;
    justify-content: flex-end;
    flex-wrap: wrap;
}

.alert {
    padding: 16px;
    border-radius: 6px;
    margin-bottom: 24px;
    display: flex;
    align-items: flex-start;
}

.alert-success {
    background-color: #f0fdf4;
    border: 1px solid #bbf7d0;
    color: #166534;
}

.alert-error {
    background-color: #fef2f2;
    border: 1px solid #fecaca;
    color: #991b1b;
}

.alert-icon {
    width: 20px;
    height: 20px;
    margin-right: 12px;
    flex-shrink: 0;
}

.file-preview {
    margin-top: 12px;
    padding: 12px;
    background-color: #f8fafc;
    border-radius: 6px;
    border: 1px solid #e2e8f0;
}

.file-list {
    list-style: disc;
    margin-left: 20px;
    margin-top: 8px;
}

.file-list li {
    margin-bottom: 4px;
    font-size: 13px;
    color: #4b5563;
}

.file-list-container {
    margin-top: 10px;
}

.file-item {
    background-color: #f8fafc;
    border: 1px solid #e2e8f0;
    border-radius: 6px;
    padding: 10px 15px;
    margin-bottom: 8px;
    display: flex;
    align-items: center;
    justify-content: space-between;
}

.file-item-name {
    display: flex;
    align-items: center;
    flex: 1;
}

.file-icon {
    width: 20px;
    height: 20px;
    margin-right: 10px;
    color: #64748b;
}

.file-name {
    font-size: 13px;
    color: #334155;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    max-width: 250px;
}

.file-size {
    font-size: 12px;
    color: #64748b;
    margin-left: 8px;
}

.file-delete {
    background-color: #fee2e2;
    color: #ef4444;
    border: none;
    border-radius: 4px;
    width: 28px;
    height: 28px;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: all 0.2s;
}

.file-delete:hover {
    background-color: #fecaca;
}

.mt-3 {
    margin-top: 12px;
}

.mt-4 {
    margin-top: 16px;
}

/* Responsive design */
@media (max-width: 640px) {
    .inquiry-container {
        padding: 12px;
    }
    
    .form-section {
        padding: 16px;
    }
    
    .form-actions {
        flex-direction: column;
    }
    
    .btn {
        width: 100%;
    }
}

.test-mode-banner {
    background-color: #fef3c7;
    border: 1px solid #f59e0b;
    color: #92400e;
    padding: 10px;
    text-align: center;
    margin-bottom: 16px;
    border-radius: 6px;
}
</style>

<div class="inquiry-container">
    <!-- Test Mode Banner -->
    <div class="test-mode-banner">
        <strong>Test Mode</strong> - This form is for testing purposes. No login required.
    </div>

    <!-- Header -->
    <div class="form-section">
        <h1 style="color: #1a202c; font-size: 2rem; font-weight: bold; margin-bottom: 8px;">Submit New Inquiry</h1>
        <p style="color: #6b7280;">Help us verify the authenticity of news by providing detailed information and supporting evidence.</p>
    </div>

    <!-- Success/Error Messages -->
    @if(session('success'))
        <div class="alert alert-success">
            <svg class="alert-icon" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
            </svg>
            <div>{{ session('success') }}</div>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-error">
            <svg class="alert-icon" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
            </svg>
            <div>{{ session('error') }}</div>
        </div>
    @endif

    <!-- Inquiry Form -->
    <form id="inquiryForm" action="{{ route('test.inquiry.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        
        <!-- Basic Information Section -->
        <div class="form-section">
            <h2>Basic Information</h2>
            
            <!-- Inquiry Title -->
            <div class="form-group">
                <label for="inquiry_title" class="form-label">
                    Inquiry Title <span class="required">*</span>
                </label>
                <input type="text" 
                       id="inquiry_title" 
                       name="inquiry_title" 
                       value="{{ old('inquiry_title') }}"
                       class="form-input @error('inquiry_title') error @enderror"
                       placeholder="Enter a clear and descriptive title for your inquiry"
                       required>
                @error('inquiry_title')
                    <div class="error-text">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <!-- Detailed Information Section -->
        <div class="form-section">
            <h2>Detailed Information</h2>
            
            <!-- Inquiry Description -->
            <div class="form-group">
                <label for="inquiry_description" class="form-label">
                    Detailed Description <span class="required">*</span>
                </label>
                <textarea id="inquiry_description" 
                          name="inquiry_description" 
                          rows="6"
                          class="form-input form-textarea @error('inquiry_description') error @enderror"
                          placeholder="Please provide detailed information about the news you want to verify. Include:&#10;• What specific claims are being made?&#10;• Why do you suspect this might be false information?&#10;• When and where did you encounter this news?&#10;• Any background context that might be relevant"
                          required>{{ old('inquiry_description') }}</textarea>
                <div class="help-text">Minimum 50 characters required. Be as detailed as possible to help our verification process.</div>
                <div id="char-counter" class="help-text"></div>
                @error('inquiry_description')
                    <div class="error-text">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <!-- Supporting Evidence Section -->
        <div class="form-section">
            <h2>Supporting Evidence</h2>
            <p style="color: #6b7280; margin-bottom: 24px;">Upload documents, images, or other files that support your inquiry. This evidence will help our verification team assess the authenticity of the news.</p>              <!-- Supporting Evidence -->
            <div class="form-group">
                <label for="inquiry_evidence" class="form-label">Supporting Evidence</label>
                <div class="file-upload-area" onclick="document.getElementById('inquiry_evidence').click();">
                    <svg class="file-upload-icon" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                        <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                    <div class="file-upload-text">Click to upload files or drag and drop</div>
                    <div class="file-upload-subtext">Upload documents (PDF, DOC, DOCX) or images (JPG, PNG, GIF) up to 10MB each</div>
                    <input id="inquiry_evidence" name="supporting_files[]" type="file" style="display: none;" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png,.gif" multiple>
                </div>
                <div id="file-uploads-container" class="mt-4"></div>                <div id="file-list-container" class="mt-4">
                    <div id="file-list" class="file-list-container"></div>
                </div>
                @error('supporting_files.*')
                    <div class="error-text">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <!-- Form Actions -->
        <div class="form-actions">
            <button type="button" onclick="window.history.back()" class="btn btn-secondary">
                Cancel
            </button>
            <button type="submit" id="submit-btn" class="btn btn-primary">
                Submit Inquiry
            </button>
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('inquiryForm');
    const visibleFileInput = document.getElementById('inquiry_evidence');
    const fileListContainer = document.getElementById('file-list');
    const finalFilesInput = document.getElementById('final_files_for_submission'); // The hidden input for submission

    let selectedFiles = []; // Array to store File objects

    visibleFileInput.addEventListener('change', function(e) {
        const files = Array.from(e.target.files);
        if (files.length > 0) {
            addFilesToList(files);
            e.target.value = ''; // Clear the visible file input
        }
    });

    function getFileIcon(fileType) {
        const fileExtension = fileType.split('.').pop().toLowerCase();
        if (["pdf", "doc", "docx", "txt", "rtf"].includes(fileExtension)) {
            return `<svg class="file-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" /></svg>`;
        }
        if (["jpg", "jpeg", "png", "gif", "bmp"].includes(fileExtension)) {
            return `<svg class="file-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>`;
        }
        return `<svg class="file-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>`;
    }

    function addFilesToList(files) {
        files.forEach(file => {
            if (!selectedFiles.some(f => f.name === file.name && f.size === file.size)) {
                selectedFiles.push(file);
            }
        });
        renderFileList();
    }

    function renderFileList() {
        fileListContainer.innerHTML = '';
        selectedFiles.forEach((file, idx) => {
            const fileId = 'file_' + idx;
            const fileSize = file.size < 1024 * 1024 
                ? (file.size / 1024).toFixed(1) + ' KB' 
                : (file.size / (1024 * 1024)).toFixed(1) + ' MB';
            const fileItem = document.createElement('div');
            fileItem.className = 'file-item';
            fileItem.id = fileId;
            fileItem.innerHTML = `
                <div class="file-item-name">
                    ${getFileIcon(file.name)}
                    <span class="file-name">${file.name}</span>
                    <span class="file-size">(${fileSize})</span>
                </div>
                <button type="button" class="file-delete" data-id="${fileId}">
                    <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                </button>
            `;
            fileListContainer.appendChild(fileItem);
            fileItem.querySelector('.file-delete').addEventListener('click', function() {
                const idxToRemove = parseInt(this.getAttribute('data-id').replace('file_', ''));
                selectedFiles.splice(idxToRemove, 1);
                renderFileList();
            });
        });
        updateInputFiles();
    }

    function updateInputFiles() {
        // Create a new DataTransfer to update the input's files
        const dataTransfer = new DataTransfer();
        selectedFiles.forEach(file => dataTransfer.items.add(file));
        visibleFileInput.files = dataTransfer.files;
        finalFilesInput.files = dataTransfer.files; // Also update the hidden input
    }

    // Form submission loading state
    const submitButton = document.getElementById('submit-btn');
    form.addEventListener('submit', function() {
        submitButton.disabled = true;
        submitButton.textContent = 'Submitting...';
        submitButton.style.opacity = '0.7';
    });

    // Character counter for description
    const descriptionTextarea = document.getElementById('inquiry_description');
    const charCounter = document.getElementById('char-counter');
    function updateCharCounter() {
        const length = descriptionTextarea.value.length;
        const minLength = 50;
        if (length < minLength) {
            charCounter.textContent = `${length}/${minLength} characters (minimum required)`;
            charCounter.style.color = '#ef4444';
        } else {
            charCounter.textContent = `${length} characters`;
            charCounter.style.color = '#6b7280';
        }
    }
    descriptionTextarea.addEventListener('input', updateCharCounter);
    updateCharCounter(); // Initial call
});
</script>
@endsection
