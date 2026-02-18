@extends('layouts.dashboard')

@section('title', 'TESLA KYC Verification')
@section('topTitle', 'KYC Verification')

@push('styles')
<style>
    .kycStatusCard {
        padding: 20px;
        border-radius: 12px;
        margin-bottom: 20px;
        border: 1px solid rgba(0,0,0,.06);
    }
    .kycStatusCard.approved {
        background: linear-gradient(135deg, #d1fae5 0%, #a7f3d0 100%);
        border-color: #86efac;
    }
    .kycStatusCard.pending {
        background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);
        border-color: #fcd34d;
    }
    .kycStatusCard.rejected {
        background: linear-gradient(135deg, #fee2e2 0%, #fecaca 100%);
        border-color: #fca5a5;
    }
    .statusIcon {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 12px;
    }
    .statusIcon.approved {
        background: rgba(16, 185, 129, 0.2);
        color: #047857;
    }
    .statusIcon.pending {
        background: rgba(251, 191, 36, 0.2);
        color: #92400e;
    }
    .statusIcon.rejected {
        background: rgba(239, 68, 68, 0.2);
        color: #991b1b;
    }
    .fileUploadArea {
        border: 2px dashed rgba(0,0,0,.15);
        border-radius: 12px;
        padding: 24px;
        text-align: center;
        background: #f9fafb;
        transition: all 0.2s;
        cursor: pointer;
        position: relative;
    }
    .fileUploadArea:hover {
        border-color: #2563eb;
        background: #f0f9ff;
    }
    .fileUploadArea.hasFile {
        border-color: #10b981;
        background: #f0fdf4;
    }
    .fileInput {
        position: absolute;
        opacity: 0;
        width: 100%;
        height: 100%;
        cursor: pointer;
        top: 0;
        left: 0;
    }
    .filePreview {
        margin-top: 12px;
        padding: 12px;
        background: #fff;
        border-radius: 8px;
        border: 1px solid rgba(0,0,0,.06);
        display: flex;
        align-items: center;
        gap: 12px;
        font-size: 12px;
        color: #6b7280;
    }
    .filePreviewIcon {
        width: 32px;
        height: 32px;
        border-radius: 6px;
        background: #f3f4f6;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }
    .formSection {
        margin-bottom: 24px;
    }
    .sectionTitle {
        font-size: 13px;
        font-weight: 900;
        color: #111827;
        margin: 0 0 8px 0;
    }
    .sectionDescription {
        font-size: 11px;
        color: #6b7280;
        margin-bottom: 12px;
        line-height: 1.5;
    }
    .infoBox {
        padding: 12px;
        border-radius: 10px;
        background: #eff6ff;
        border: 1px solid #bfdbfe;
        font-size: 11px;
        color: #1e40af;
        line-height: 1.5;
        margin-bottom: 16px;
    }
    .infoBox strong {
        display: block;
        margin-bottom: 4px;
        font-size: 12px;
    }
</style>
@endpush

@section('content')
<div class="wrap" id="kyc">
    <!-- Hero / Welcome -->
    <div class="surface">
        <div class="heroCard">
            <div class="heroText">
                <h3>KYC Verification</h3>
                <p>Complete your identity verification to access all platform features and unlock higher transaction limits.</p>
            </div>
        </div>
    </div>

    <!-- KYC Status Card -->
    @if($latestSubmission)
        <div class="kycStatusCard {{ strtolower($latestSubmission->status) }}" style="margin-top: 12px;">
            <div class="statusIcon {{ strtolower($latestSubmission->status) }}">
                @if($latestSubmission->status === 'Approved')
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M20 6L9 17l-5-5" />
                    </svg>
                @elseif($latestSubmission->status === 'Pending')
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="12" cy="12" r="10" />
                        <path d="M12 6v6l4 2" />
                    </svg>
                @else
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="12" cy="12" r="10" />
                        <line x1="15" y1="9" x2="9" y2="15" />
                        <line x1="9" y1="9" x2="15" y2="15" />
                    </svg>
                @endif
            </div>
            <h4 style="margin: 0 0 8px 0; font-size: 16px; font-weight: 900; color: #111827;">
                @if($latestSubmission->status === 'Approved')
                    Verification Approved
                @elseif($latestSubmission->status === 'Pending')
                    Verification Pending
                @else
                    Verification Rejected
                @endif
            </h4>
            <p style="margin: 0 0 12px 0; font-size: 12px; color: #6b7280; line-height: 1.5;">
                @if($latestSubmission->status === 'Approved')
                    Your identity has been successfully verified. You now have full access to all platform features.
                @elseif($latestSubmission->status === 'Pending')
                    Your documents are currently under review. Our team will process your verification within 24-48 hours.
                @else
                    @if($latestSubmission->rejection_reason)
                        {{ $latestSubmission->rejection_reason }}
                    @else
                        Your verification was rejected. Please submit new documents.
                    @endif
                @endif
            </p>
            @if($latestSubmission->status === 'Pending')
                <p style="margin: 0; font-size: 11px; color: #6b7280;">
                    Submitted on {{ $latestSubmission->created_at->format('M d, Y \a\t g:i A') }}
                </p>
            @endif
        </div>
    @endif

    <!-- KYC Upload Form -->
    @if(!$latestSubmission || $latestSubmission->status !== 'Approved')
    <div class="lowerGrid" style="grid-template-columns: 1fr; margin-top: 12px;">
        <div class="whitePanel">
            <div class="panelHead">
                <div>
                    <h5>Upload Documents</h5>
                    <small>Please provide the required documents for verification</small>
                </div>
            </div>

            <div style="padding: 20px;">
                @if ($errors->any())
                    <div style="padding: 12px; margin-bottom: 16px; border-radius: 10px; background: #fee2e2; border: 1px solid #fecaca;">
                        <ul style="margin: 0; padding-left: 20px; font-size: 12px; color: #991b1b;">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @if (session('success'))
                    <div style="padding: 12px; margin-bottom: 16px; border-radius: 10px; background: #d1fae5; border: 1px solid #86efac; font-size: 12px; color: #065f46;">
                        {{ session('success') }}
                    </div>
                @endif

                <div class="infoBox">
                    <strong>Important Information:</strong>
                    <ul style="margin: 4px 0 0 0; padding-left: 20px;">
                        <li>All documents must be clear and legible</li>
                        <li>Accepted formats: JPG, PNG, PDF (max 5MB each)</li>
                        <li>Documents will be reviewed within 24-48 hours</li>
                        <li>Your information is encrypted and securely stored</li>
                    </ul>
                </div>

                <form method="POST" action="{{ route('dashboard.kyc.submit') }}" enctype="multipart/form-data" style="display: flex; flex-direction: column; gap: 24px;">
                    @csrf
                    
                    <!-- ID Document -->
                    <div class="formSection">
                        <h5 class="sectionTitle">Government ID Document</h5>
                        <p class="sectionDescription">Upload a clear photo or scan of your Passport, Driver's License, or National ID card. Both sides may be required.</p>
                        
                        <div class="fileUploadArea" id="idDocumentArea" onclick="document.getElementById('id_document').click()">
                            <input type="file" name="id_document" id="id_document" accept="image/*,.pdf" required class="fileInput" onchange="handleFileSelect(this, 'idDocumentArea', 'idDocumentPreview')" />
                            <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="#6b7280" stroke-width="2" style="margin: 0 auto 8px;">
                                <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4" />
                                <polyline points="17 8 12 3 7 8" />
                                <line x1="12" y1="3" x2="12" y2="15" />
                            </svg>
                            <p style="margin: 0; font-size: 12px; font-weight: 700; color: #6b7280;">Click to upload ID document</p>
                            <p style="margin: 4px 0 0 0; font-size: 11px; color: #9ca3af;">JPG, PNG, or PDF (max 5MB)</p>
                            <div id="idDocumentPreview" style="display: none;"></div>
                        </div>
                    </div>

                    <!-- Proof of Address -->
                    <div class="formSection">
                        <h5 class="sectionTitle">Proof of Address</h5>
                        <p class="sectionDescription">Upload a utility bill, bank statement, or government document showing your current address. Document must be less than 3 months old.</p>
                        
                        <div class="fileUploadArea" id="addressArea" onclick="document.getElementById('proof_of_address').click()">
                            <input type="file" name="proof_of_address" id="proof_of_address" accept="image/*,.pdf" required class="fileInput" onchange="handleFileSelect(this, 'addressArea', 'addressPreview')" />
                            <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="#6b7280" stroke-width="2" style="margin: 0 auto 8px;">
                                <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4" />
                                <polyline points="17 8 12 3 7 8" />
                                <line x1="12" y1="3" x2="12" y2="15" />
                            </svg>
                            <p style="margin: 0; font-size: 12px; font-weight: 700; color: #6b7280;">Click to upload proof of address</p>
                            <p style="margin: 4px 0 0 0; font-size: 11px; color: #9ca3af;">JPG, PNG, or PDF (max 5MB)</p>
                            <div id="addressPreview" style="display: none;"></div>
                        </div>
                    </div>

                    <!-- Selfie -->
                    <div class="formSection">
                        <h5 class="sectionTitle">Selfie with ID</h5>
                        <p class="sectionDescription">Take a clear photo of yourself holding your ID document. Make sure your face and the ID are clearly visible.</p>
                        
                        <div class="fileUploadArea" id="selfieArea" onclick="document.getElementById('selfie').click()">
                            <input type="file" name="selfie" id="selfie" accept="image/*" required class="fileInput" onchange="handleFileSelect(this, 'selfieArea', 'selfiePreview')" />
                            <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="#6b7280" stroke-width="2" style="margin: 0 auto 8px;">
                                <path d="M23 19a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h4l2-3h6l2 3h4a2 2 0 0 1 2 2z" />
                                <circle cx="12" cy="13" r="4" />
                            </svg>
                            <p style="margin: 0; font-size: 12px; font-weight: 700; color: #6b7280;">Click to upload selfie</p>
                            <p style="margin: 4px 0 0 0; font-size: 11px; color: #9ca3af;">JPG or PNG (max 5MB)</p>
                            <div id="selfiePreview" style="display: none;"></div>
                        </div>
                    </div>

                    <div style="padding-top: 12px; border-top: 1px solid rgba(0,0,0,.06);">
                        <button type="submit" style="height: 42px; padding: 0 24px; border-radius: 10px; background: #E31937; color: #fff; font-size: 12px; font-weight: 900; border: none; cursor: pointer; transition: all 0.2s; display: inline-flex; align-items: center; gap: 8px;">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M20 6L9 17l-5-5" />
                            </svg>
                            Submit for Verification
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif
</div>

@push('scripts')
<script>
    function handleFileSelect(input, areaId, previewId) {
        const area = document.getElementById(areaId);
        const preview = document.getElementById(previewId);
        
        if (input.files && input.files[0]) {
            const file = input.files[0];
            const fileName = file.name;
            const fileSize = (file.size / 1024 / 1024).toFixed(2);
            
            area.classList.add('hasFile');
            
            preview.style.display = 'block';
            preview.innerHTML = `
                <div class="filePreview">
                    <div class="filePreviewIcon">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#6b7280" stroke-width="2">
                            <path d="M13 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V9z" />
                            <polyline points="13 2 13 9 20 9" />
                        </svg>
                    </div>
                    <div style="flex: 1; min-width: 0;">
                        <div style="font-weight: 700; color: #111827; margin-bottom: 2px;">${fileName}</div>
                        <div style="font-size: 11px; color: #9ca3af;">${fileSize} MB</div>
                    </div>
                </div>
            `;
        }
    }
</script>
@endpush
@endsection
