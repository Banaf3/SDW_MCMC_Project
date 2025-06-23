<?php

namespace App\Http\Controllers\ManageInquiryFormSubmission\PublicUser;

use App\Http\Controllers\Controller;
use App\Models\Inquiry;
use App\Models\PublicUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log; // Added for logging
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class InquirySubmissionController extends Controller
{    public function create()
    {
        return view('ManageInquiryFormSubmission.PublicUser.submit-inquiry');
    }

    public function store(Request $request)
    {
        Log::info('Store method called.');
        Log::info('Request data:', $request->all());        $validator = Validator::make($request->all(), [
            'inquiry_title' => 'required|string|max:255',
            'inquiry_description' => 'required|string|min:50',
            'supporting_files' => 'nullable|array', // Expect an array of files
            'supporting_files.*' => 'nullable|file|max:10240|mimes:pdf,doc,docx,jpg,jpeg,png,gif,mp4,avi,mov,wmv,txt,rtf', // Max 10MB, specific mimes
            'inquiry_evidence' => 'nullable|array', // Support new field name
            'inquiry_evidence.*' => 'nullable|file|max:10240|mimes:pdf,doc,docx,jpg,jpeg,png,gif,mp4,avi,mov,wmv,txt,rtf' // Max 10MB, specific mimes
        ], [
            'inquiry_title.required' => 'The inquiry title is required.',
            'inquiry_description.required' => 'The inquiry description is required.',
            'inquiry_description.min' => 'The inquiry description must be at least :min characters.',
            'urls_data.json' => 'The related URLs data is not in a valid format.',
            'supporting_files.*.max' => 'Each file must not exceed 10MB.',
            'supporting_files.*.mimes' => 'Invalid file type. Allowed types: PDF, DOC, DOCX, JPG, PNG, GIF, MP4, AVI, MOV, WMV, TXT, RTF.',
        ]);

        if ($validator->fails()) {
            Log::error('Validation failed:', $validator->errors()->toArray());
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }        Log::info('Validation passed.');
        try {
            // Get the current authenticated user's ID from session
            $userId = session('user_id');
            
            // If no user is logged in, redirect to login
            if (!$userId) {
                return redirect()->route('login')->with('error', 'Please log in to submit an inquiry.');
            }
            
            $evidence = [
                'files' => [],
            ];
            
            // Handle either supporting_files (old name) or inquiry_evidence (new name)
            $fileField = $request->hasFile('inquiry_evidence') ? 'inquiry_evidence' : 'supporting_files';
            
            if ($request->hasFile($fileField)) {
                Log::info('Processing uploaded files from: ' . $fileField);
                foreach ($request->file($fileField) as $file) {
                    if ($file->isValid()) {
                        $originalName = $file->getClientOriginalName();
                        $sanitizedName = preg_replace('/[^A-Za-z0-9.\-_]/', '_', $originalName);
                        $path = $file->storeAs('inquiries/files', $sanitizedName, 'public');
                        
                        $evidence['files'][] = [
                            'path' => $path,
                            'original_name' => $originalName,
                            'size' => $file->getSize(),
                            'mime_type' => $file->getMimeType(),
                            'type' => $this->getFileType($file->getMimeType(), $file->getClientOriginalExtension()),
                        ];
                        Log::info('File stored:', ['path' => $path, 'original_name' => $originalName]);
                    }
                }
            }

            // Create inquiry using correct column names
            $inquiry = Inquiry::create([
                'InquiryTitle' => $request->input('inquiry_title'),
                'InquiryDescription' => $request->input('inquiry_description'),
                'InquiryStatus' => 'Submitted',
                'SubmitionDate' => now()->toDateString(), // Use date format for date column
                'InquiryEvidence' => json_encode($evidence),
                'UserID' => $userId,
                'AdminID' => 1, // Default admin ID
                'AgencyID' => null, // Unassigned - MCMC will assign later
            ]);
            
            Log::info('Inquiry saved successfully:', ['id' => $inquiry->InquiryID]);

            return redirect()->back()
                ->with('success', 'Your inquiry has been submitted successfully! Inquiry ID: ' . $inquiry->InquiryID);

        } catch (\Exception $e) {
            Log::error('Error storing inquiry:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            return redirect()->back()
                ->with('error', 'An unexpected error occurred while submitting your inquiry. Please try again later. Error: ' . $e->getMessage())
                ->withInput();
        }
    }

    private function getFileType($mimeType, $extension)
    {
        $extension = strtolower($extension);
        if (str_starts_with($mimeType, 'image/')) {
            return 'image';
        }
        if (str_starts_with($mimeType, 'video/')) {
            return 'video';
        }
        if (str_starts_with($mimeType, 'audio/')) {
            return 'audio';
        }
        if ($mimeType === 'application/pdf') {
            return 'pdf';
        }
        $docTypes = ['doc', 'docx', 'odt', 'rtf', 'txt'];
        if (in_array($extension, $docTypes) || $mimeType === 'application/msword' || $mimeType === 'application/vnd.openxmlformats-officedocument.wordprocessingml.document') {
            return 'document';
        }
        $spreadsheetTypes = ['xls', 'xlsx', 'ods', 'csv'];
        if (in_array($extension, $spreadsheetTypes) || $mimeType === 'application/vnd.ms-excel' || $mimeType === 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet') {
            return 'spreadsheet';
        }
        $presentationTypes = ['ppt', 'pptx', 'odp'];
        if (in_array($extension, $presentationTypes) || $mimeType === 'application/vnd.ms-powerpoint' || $mimeType === 'application/vnd.openxmlformats-officedocument.presentationml.presentation') {
            return 'presentation';
        }
        if ($mimeType === 'application/zip' || $mimeType === 'application/x-rar-compressed') {
            return 'archive';
        }
        return 'file'; // Default type
    }
}