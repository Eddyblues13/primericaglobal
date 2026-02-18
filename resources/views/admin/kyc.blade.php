@extends('layouts.admin')

@section('title', 'KYC Submissions - Admin')
@section('topTitle', 'KYC Submissions')

@section('content')
<div class="wrap">
    <!-- Filters -->
    <div class="whitePanel" style="margin-bottom: 12px;">
        <form method="GET" action="{{ route('admin.kyc') }}" class="p-4">
            <div style="display: flex; gap: 12px; align-items: end;">
                <div style="min-width: 150px;">
                    <label style="display: block; font-size: 11px; font-weight: 900; color: #6b7280; margin-bottom: 6px;">Status</label>
                    <select name="status" style="width: 100%; height: 40px; padding: 0 12px; border-radius: 8px; border: 1px solid rgba(0,0,0,.1); font-size: 13px;">
                        <option value="">All Statuses</option>
                        <option value="Pending" {{ request('status') == 'Pending' ? 'selected' : '' }}>Pending</option>
                        <option value="Approved" {{ request('status') == 'Approved' ? 'selected' : '' }}>Approved</option>
                        <option value="Rejected" {{ request('status') == 'Rejected' ? 'selected' : '' }}>Rejected</option>
                    </select>
                </div>
                <button type="submit" style="height: 40px; padding: 0 20px; border-radius: 10px; background: #111827; color: white; font-size: 12px; font-weight: 900; cursor: pointer;">
                    Filter
                </button>
                @if(request('status'))
                    <a href="{{ route('admin.kyc') }}" style="height: 40px; padding: 0 20px; border-radius: 10px; background: #ef4444; color: white; font-size: 12px; font-weight: 900; cursor: pointer; display: inline-flex; align-items: center; text-decoration: none;">
                        Clear
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- KYC Submissions Table -->
    <div class="whitePanel">
        <div class="panelHead">
            <div>
                <h5>KYC Submissions</h5>
                <small>Total: {{ $submissions->total() }} submissions</small>
            </div>
        </div>

        @if(session('success'))
            <div style="margin: 12px 14px; padding: 12px; background: #d1fae5; border: 1px solid #10b981; border-radius: 8px; color: #065f46; font-size: 12px; font-weight: 700;">
                {{ session('success') }}
            </div>
        @endif

        <div style="overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr style="background: #f9fafb; border-bottom: 1px solid rgba(0,0,0,.06);">
                        <th style="padding: 12px 14px; text-align: left; font-size: 11px; font-weight: 900; color: #6b7280;">User</th>
                        <th style="padding: 12px 14px; text-align: left; font-size: 11px; font-weight: 900; color: #6b7280;">Documents</th>
                        <th style="padding: 12px 14px; text-align: left; font-size: 11px; font-weight: 900; color: #6b7280;">Status</th>
                        <th style="padding: 12px 14px; text-align: left; font-size: 11px; font-weight: 900; color: #6b7280;">Submitted</th>
                        <th style="padding: 12px 14px; text-align: left; font-size: 11px; font-weight: 900; color: #6b7280;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($submissions as $submission)
                        <tr style="border-bottom: 1px solid rgba(0,0,0,.06);">
                            <td style="padding: 12px 14px;">
                                <div style="font-size: 12px; font-weight: 900; color: #111827;">{{ $submission->user->name }}</div>
                                <div style="font-size: 11px; color: #6b7280; margin-top: 2px;">{{ $submission->user->email }}</div>
                            </td>
                            <td style="padding: 12px 14px;">
                                <div style="display: flex; gap: 8px; flex-wrap: wrap;">
                                    @if($submission->id_document_path)
                                        <a href="{{ asset('storage/' . $submission->id_document_path) }}" target="_blank" style="font-size: 10px; padding: 4px 8px; border-radius: 6px; background: #eff6ff; color: #2563eb; text-decoration: none; font-weight: 700;">ID</a>
                                    @endif
                                    @if($submission->proof_of_address_path)
                                        <a href="{{ asset('storage/' . $submission->proof_of_address_path) }}" target="_blank" style="font-size: 10px; padding: 4px 8px; border-radius: 6px; background: #f0fdf4; color: #16a34a; text-decoration: none; font-weight: 700;">Address</a>
                                    @endif
                                    @if($submission->selfie_path)
                                        <a href="{{ asset('storage/' . $submission->selfie_path) }}" target="_blank" style="font-size: 10px; padding: 4px 8px; border-radius: 6px; background: #fef3c7; color: #d97706; text-decoration: none; font-weight: 700;">Selfie</a>
                                    @endif
                                </div>
                            </td>
                            <td style="padding: 12px 14px;">
                                @if($submission->status === 'Approved')
                                    <span class="status ok">{{ $submission->status }}</span>
                                @elseif($submission->status === 'Pending')
                                    <span style="font-size: 10px; font-weight: 900; padding: 4px 8px; border-radius: 999px; background: rgba(251, 191, 36, .14); color: rgba(217, 119, 6, .95); border: 1px solid rgba(217, 119, 6, .25);">{{ $submission->status }}</span>
                                @else
                                    <span style="font-size: 10px; font-weight: 900; padding: 4px 8px; border-radius: 999px; background: rgba(239, 68, 68, .14); color: rgba(220, 38, 38, .95); border: 1px solid rgba(220, 38, 38, .25);">{{ $submission->status }}</span>
                                @endif
                            </td>
                            <td style="padding: 12px 14px; font-size: 11px; color: #6b7280;">{{ $submission->created_at->format('M d, Y') }}</td>
                            <td style="padding: 12px 14px;">
                                @if($submission->status === 'Pending')
                                    <button onclick="openKycModal({{ $submission->id }}, '{{ $submission->status }}')" 
                                        style="padding: 6px 12px; border-radius: 8px; background: #2563eb; color: white; font-size: 11px; font-weight: 900; cursor: pointer; border: none;">
                                        Review
                                    </button>
                                @else
                                    <span style="font-size: 11px; color: #9ca3af;">Reviewed</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" style="padding: 40px; text-align: center; color: #6b7280; font-size: 13px;">
                                No KYC submissions found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($submissions->hasPages())
            <div style="padding: 14px; border-top: 1px solid rgba(0,0,0,.06);">
                {{ $submissions->links() }}
            </div>
        @endif
    </div>
</div>

<!-- KYC Status Modal -->
<div id="kycModal" style="display: none; position: fixed; inset: 0; background: rgba(0,0,0,.5); z-index: 100; align-items: center; justify-content: center;">
    <div style="background: white; border-radius: 14px; padding: 24px; max-width: 400px; width: 90%;">
        <h3 style="font-size: 16px; font-weight: 900; color: #111827; margin-bottom: 16px;">Review KYC Submission</h3>
        <form method="POST" id="kycForm">
            @csrf
            <div style="margin-bottom: 16px;">
                <label style="display: block; font-size: 11px; font-weight: 900; color: #6b7280; margin-bottom: 6px;">Status</label>
                <select name="status" id="kycStatus" required style="width: 100%; height: 40px; padding: 0 12px; border-radius: 8px; border: 1px solid rgba(0,0,0,.1); font-size: 13px; color: #111827; background: white;">
                    <option value="Pending">Pending</option>
                    <option value="Approved">Approved</option>
                    <option value="Rejected">Rejected</option>
                </select>
            </div>
            <div style="margin-bottom: 20px;">
                <label style="display: block; font-size: 11px; font-weight: 900; color: #6b7280; margin-bottom: 6px;">Notes (Optional)</label>
                <textarea name="notes" rows="3" style="width: 100%; padding: 8px 12px; border-radius: 8px; border: 1px solid rgba(0,0,0,.1); font-size: 13px; resize: vertical; color: #111827; background: white;"></textarea>
            </div>
            <div style="display: flex; gap: 10px;">
                <button type="submit" style="flex: 1; height: 40px; border-radius: 8px; background: #111827; color: white; font-size: 12px; font-weight: 900; cursor: pointer; border: none;">
                    Update
                </button>
                <button type="button" onclick="closeKycModal()" style="flex: 1; height: 40px; border-radius: 8px; background: #ef4444; color: white; font-size: 12px; font-weight: 900; cursor: pointer; border: none;">
                    Cancel
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function openKycModal(kycId, currentStatus) {
    document.getElementById('kycForm').action = `/admin/kyc/${kycId}/status`;
    document.getElementById('kycStatus').value = currentStatus;
    document.getElementById('kycModal').style.display = 'flex';
}

function closeKycModal() {
    document.getElementById('kycModal').style.display = 'none';
}

document.getElementById('kycModal')?.addEventListener('click', function(e) {
    if (e.target === this) {
        closeKycModal();
    }
});
</script>
@endsection
