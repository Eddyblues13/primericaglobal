@extends('layouts.admin')

@section('title', 'Support Tickets - Admin')
@section('topTitle', 'Support Tickets')

@section('content')
<div class="wrap">
    <!-- Filters -->
    <div class="whitePanel" style="margin-bottom: 12px;">
        <form method="GET" action="{{ route('admin.support') }}" class="p-4">
            <div style="display: flex; gap: 12px; align-items: end;">
                <div style="min-width: 150px;">
                    <label style="display: block; font-size: 11px; font-weight: 900; color: #6b7280; margin-bottom: 6px;">Status</label>
                    <select name="status" style="width: 100%; height: 40px; padding: 0 12px; border-radius: 8px; border: 1px solid rgba(0,0,0,.1); font-size: 13px;">
                        <option value="">All Statuses</option>
                        <option value="open" {{ request('status') == 'open' ? 'selected' : '' }}>Open</option>
                        <option value="in_progress" {{ request('status') == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                        <option value="resolved" {{ request('status') == 'resolved' ? 'selected' : '' }}>Resolved</option>
                        <option value="closed" {{ request('status') == 'closed' ? 'selected' : '' }}>Closed</option>
                    </select>
                </div>
                <button type="submit" style="height: 40px; padding: 0 20px; border-radius: 10px; background: #111827; color: white; font-size: 12px; font-weight: 900; cursor: pointer;">
                    Filter
                </button>
                @if(request('status'))
                    <a href="{{ route('admin.support') }}" style="height: 40px; padding: 0 20px; border-radius: 10px; background: #ef4444; color: white; font-size: 12px; font-weight: 900; cursor: pointer; display: inline-flex; align-items: center; text-decoration: none;">
                        Clear
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Support Tickets Table -->
    <div class="whitePanel">
        <div class="panelHead">
            <div>
                <h5>Support Tickets</h5>
                <small>Total: {{ $tickets->total() }} tickets</small>
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
                        <th style="padding: 12px 14px; text-align: left; font-size: 11px; font-weight: 900; color: #6b7280;">Ticket #</th>
                        <th style="padding: 12px 14px; text-align: left; font-size: 11px; font-weight: 900; color: #6b7280;">User</th>
                        <th style="padding: 12px 14px; text-align: left; font-size: 11px; font-weight: 900; color: #6b7280;">Subject</th>
                        <th style="padding: 12px 14px; text-align: left; font-size: 11px; font-weight: 900; color: #6b7280;">Category</th>
                        <th style="padding: 12px 14px; text-align: left; font-size: 11px; font-weight: 900; color: #6b7280;">Status</th>
                        <th style="padding: 12px 14px; text-align: left; font-size: 11px; font-weight: 900; color: #6b7280;">Created</th>
                        <th style="padding: 12px 14px; text-align: left; font-size: 11px; font-weight: 900; color: #6b7280;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($tickets as $ticket)
                        <tr style="border-bottom: 1px solid rgba(0,0,0,.06);">
                            <td style="padding: 12px 14px; font-size: 12px; font-weight: 900; color: #111827;">{{ $ticket->ticket_number }}</td>
                            <td style="padding: 12px 14px;">
                                <div style="font-size: 12px; font-weight: 900; color: #111827;">{{ $ticket->user->name }}</div>
                                <div style="font-size: 11px; color: #6b7280; margin-top: 2px;">{{ $ticket->user->email }}</div>
                            </td>
                            <td style="padding: 12px 14px;">
                                <div style="font-size: 12px; font-weight: 900; color: #111827;">{{ $ticket->subject }}</div>
                                <div style="font-size: 11px; color: #6b7280; margin-top: 2px; max-width: 300px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">{{ Str::limit($ticket->message, 50) }}</div>
                            </td>
                            <td style="padding: 12px 14px; font-size: 12px; color: #111827;">{{ ucfirst($ticket->category ?? 'General') }}</td>
                            <td style="padding: 12px 14px;">
                                @if($ticket->status === 'resolved' || $ticket->status === 'closed')
                                    <span class="status ok">{{ ucfirst(str_replace('_', ' ', $ticket->status)) }}</span>
                                @elseif($ticket->status === 'in_progress')
                                    <span style="font-size: 10px; font-weight: 900; padding: 4px 8px; border-radius: 999px; background: rgba(59, 130, 246, .14); color: rgba(37, 99, 235, .95); border: 1px solid rgba(37, 99, 235, .25);">{{ ucfirst(str_replace('_', ' ', $ticket->status)) }}</span>
                                @else
                                    <span style="font-size: 10px; font-weight: 900; padding: 4px 8px; border-radius: 999px; background: rgba(251, 191, 36, .14); color: rgba(217, 119, 6, .95); border: 1px solid rgba(217, 119, 6, .25);">{{ ucfirst($ticket->status) }}</span>
                                @endif
                            </td>
                            <td style="padding: 12px 14px; font-size: 11px; color: #6b7280;">{{ $ticket->created_at->format('M d, Y') }}</td>
                            <td style="padding: 12px 14px;">
                                <button onclick="openTicketModal({{ $ticket->id }}, '{{ $ticket->subject }}', `{{ addslashes($ticket->message) }}`)" 
                                    style="padding: 6px 12px; border-radius: 8px; background: #2563eb; color: white; font-size: 11px; font-weight: 900; cursor: pointer; border: none;">
                                    View/Reply
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" style="padding: 40px; text-align: center; color: #6b7280; font-size: 13px;">
                                No support tickets found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($tickets->hasPages())
            <div style="padding: 14px; border-top: 1px solid rgba(0,0,0,.06);">
                {{ $tickets->links() }}
            </div>
        @endif
    </div>
</div>

<!-- Ticket Reply Modal -->
<div id="ticketModal" style="display: none; position: fixed; inset: 0; background: rgba(0,0,0,.5); z-index: 100; align-items: center; justify-content: center; padding: 20px;">
    <div style="background: white; border-radius: 14px; padding: 24px; max-width: 600px; width: 100%; max-height: 90vh; overflow-y: auto;">
        <h3 style="font-size: 16px; font-weight: 900; color: #111827; margin-bottom: 16px;" id="ticketSubject"></h3>
        <div style="margin-bottom: 16px; padding: 12px; background: #f9fafb; border-radius: 8px;">
            <div style="font-size: 11px; font-weight: 900; color: #6b7280; margin-bottom: 6px;">User Message:</div>
            <div style="font-size: 13px; color: #111827; line-height: 1.6;" id="ticketMessage"></div>
        </div>
        <form method="POST" id="ticketForm">
            @csrf
            <div style="margin-bottom: 20px;">
                <label style="display: block; font-size: 11px; font-weight: 900; color: #6b7280; margin-bottom: 6px;">Admin Reply</label>
                <textarea name="message" rows="5" required style="width: 100%; padding: 8px 12px; border-radius: 8px; border: 1px solid rgba(0,0,0,.1); font-size: 13px; resize: vertical; color: #111827; background: white;"></textarea>
            </div>
            <div style="display: flex; gap: 10px;">
                <button type="submit" style="flex: 1; height: 40px; border-radius: 8px; background: #111827; color: white; font-size: 12px; font-weight: 900; cursor: pointer; border: none;">
                    Send Reply
                </button>
                <button type="button" onclick="closeTicketModal()" style="flex: 1; height: 40px; border-radius: 8px; background: #ef4444; color: white; font-size: 12px; font-weight: 900; cursor: pointer; border: none;">
                    Cancel
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function openTicketModal(ticketId, subject, message) {
    document.getElementById('ticketForm').action = `/admin/support/${ticketId}/reply`;
    document.getElementById('ticketSubject').textContent = subject;
    document.getElementById('ticketMessage').textContent = message;
    document.getElementById('ticketModal').style.display = 'flex';
}

function closeTicketModal() {
    document.getElementById('ticketModal').style.display = 'none';
}

document.getElementById('ticketModal')?.addEventListener('click', function(e) {
    if (e.target === this) {
        closeTicketModal();
    }
});
</script>
@endsection
