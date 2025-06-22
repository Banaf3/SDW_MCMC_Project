# Module 3: Inquiry Assignment Controllers

This folder contains the controllers for Module 3 (Inquiry Assignment) of the VeriTrack system.

## Controllers

### MCMCController.php
**Purpose:** Handles MCMC (Malaysian Communications and Multimedia Commission) interface functionality

**Namespace:** `App\Http\Controllers\InquiryAssignment\MCMCController`

**Responsibilities:**
- Display unassigned inquiries to MCMC staff
- Assign inquiries to appropriate agencies
- Retrieve inquiry details for MCMC operations

**Methods:**
- `unassignedInquiries()` - Display MCMC UI with unassigned inquiries
- `assignInquiry()` - Assign inquiries to agencies (with MCMC comments)
- `getInquiryDetails()` - Retrieve inquiry details for MCMC operations

**Routes:**
- `GET /mcmc/unassigned-inquiries` → MCMC UI
- `POST /mcmc/assign-inquiry/{id}` → Assignment functionality
- `GET /mcmc/inquiry-details/{id}` → Inquiry details

---

### AgencyController.php
**Purpose:** Handles Agency interface functionality

**Namespace:** `App\Http\Controllers\InquiryAssignment\AgencyController`

**Responsibilities:**
- Display assigned inquiries to agency staff
- Update inquiry status by agency staff
- Reject inquiry assignments (returning them to MCMC)

**Methods:**
- `assignedInquiries()` - Display Agency UI with assigned inquiries
- `updateInquiryStatus()` - Update inquiry status from Agency UI
- `rejectInquiry()` - Reject assignments (return to MCMC)

**Routes:**
- `GET /agency/assigned-inquiries` → Agency UI
- `PUT /agency/inquiry-status-update/{id}` → Status updates
- `POST /agency/reject-inquiry/{id}` → Rejection functionality

---

## University Project Structure

This structure follows the package diagram requirements for the university project:

- **Module 3: Inquiry Assignment**
  - MCMCController - MCMC interface only
  - AgencyController - Agency interface only
  - No cross-dependencies between controllers
  - Clean separation of concerns

## Notes

- Each controller serves only its respective UI
- Controllers are located in: `app/Http/Controllers/InquiryAssignment/`
- Routes are updated to use the new namespaced controllers
- Both UIs work independently with their respective controllers
