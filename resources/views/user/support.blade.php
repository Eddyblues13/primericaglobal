@extends('layouts.dashboard')

@section('title', 'TESLA Support')

@section('topTitle', 'Support')

@section('content')
<div class="wrap" id="support">
    <!-- Support Header -->
    <div class="surface">
        <div class="heroCard">
            <div class="heroText">
                <h3>Support Center</h3>
                <p>Get help with your account, investments, and transactions.</p>
            </div>
        </div>
    </div>

    <!-- Recent Tickets -->
    @if(isset($tickets) && $tickets->count() > 0)
    <div class="whitePanel" style="margin-top: 12px;">
        <div class="panelHead">
            <div>
                <h5>Recent Support Tickets</h5>
                <small>Your support ticket history</small>
            </div>
        </div>
        <div style="padding: 14px;">
            @foreach($tickets as $ticket)
                <div style="padding: 12px; border-bottom: 1px solid rgba(0,0,0,.06); display: flex; justify-content: space-between; align-items: start;">
                    <div style="flex: 1;">
                        <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 4px;">
                            <strong style="font-size: 12px; color: #111827;">#{{ $ticket->ticket_number }}</strong>
                            <span style="padding: 2px 8px; border-radius: 6px; font-size: 10px; font-weight: 900; 
                                background: {{ $ticket->status === 'Open' ? '#dbeafe' : ($ticket->status === 'Resolved' ? '#d1fae5' : ($ticket->status === 'In Progress' ? '#fef3c7' : '#f3f4f6')) }};
                                color: {{ $ticket->status === 'Open' ? '#2563eb' : ($ticket->status === 'Resolved' ? '#047857' : ($ticket->status === 'In Progress' ? '#92400e' : '#6b7280')) }};">
                                {{ $ticket->status }}
                            </span>
                        </div>
                        <div style="font-size: 12px; font-weight: 700; color: #6b7280; margin-bottom: 2px;">{{ $ticket->subject }}</div>
                        <div style="font-size: 11px; color: #9ca3af;">{{ $ticket->created_at->format('M d, Y g:i A') }}</div>
                        @if($ticket->admin_response)
                            <div style="margin-top: 8px; padding: 8px; background: #f0f9ff; border-left: 3px solid #2563eb; border-radius: 4px; font-size: 11px; color: #1e40af;">
                                <strong>Response:</strong> {{ $ticket->admin_response }}
                            </div>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    </div>
    @endif

    <!-- Support Content Grid -->
    <div class="lowerGrid" style="grid-template-columns: 2fr 1fr; margin-top: 12px;">
        <!-- Contact Form -->
        <div class="whitePanel">
            <div class="panelHead">
                <div>
                    <h5>Contact Support</h5>
                    <small>Send us a message and we'll get back to you</small>
                </div>
            </div>

            <div style="padding: 14px;">
                @if ($errors->any())
                    <div style="padding: 12px; margin-bottom: 12px; border-radius: 10px; background: #fee2e2; border: 1px solid #fecaca;">
                        <ul style="margin: 0; padding-left: 20px; font-size: 12px; color: #991b1b;">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @if (session('success'))
                    <div style="padding: 12px; margin-bottom: 12px; border-radius: 10px; background: #d1fae5; border: 1px solid #86efac; font-size: 12px; color: #065f46;">
                        {{ session('success') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('dashboard.support.submit') }}" style="display: flex; flex-direction: column; gap: 14px;">
                    @csrf
                    
                    <div>
                        <label style="display: block; font-size: 11px; font-weight: 900; color: #6b7280; margin-bottom: 6px;">Category</label>
                        <select name="category" required style="width: 100%; height: 38px; padding: 0 12px; border-radius: 10px; border: 1px solid rgba(0,0,0,.10); background: #fff; font-size: 13px; color: #111827; cursor: pointer;">
                            <option value="">Select a category</option>
                            <option value="account">Account Issues</option>
                            <option value="investment">Investment Questions</option>
                            <option value="stock">Stock Trading</option>
                            <option value="inventory">Vehicle Inventory</option>
                            <option value="payment">Payment & Billing</option>
                            <option value="technical">Technical Support</option>
                            <option value="other">Other</option>
                        </select>
                    </div>

                    <div>
                        <label style="display: block; font-size: 11px; font-weight: 900; color: #6b7280; margin-bottom: 6px;">Subject</label>
                        <input type="text" name="subject" required placeholder="Brief description of your issue" style="width: 100%; height: 38px; padding: 0 12px; border-radius: 10px; border: 1px solid rgba(0,0,0,.10); background: #fff; font-size: 13px; color: #111827;" />
                    </div>

                    <div>
                        <label style="display: block; font-size: 11px; font-weight: 900; color: #6b7280; margin-bottom: 6px;">Email</label>
                        <input type="email" name="email" value="{{ auth()->user()->email ?? '' }}" required style="width: 100%; height: 38px; padding: 0 12px; border-radius: 10px; border: 1px solid rgba(0,0,0,.10); background: #fff; font-size: 13px; color: #111827;" />
                    </div>

                    <div>
                        <label style="display: block; font-size: 11px; font-weight: 900; color: #6b7280; margin-bottom: 6px;">Message</label>
                        <textarea name="message" rows="8" required placeholder="Describe your issue or question..." style="width: 100%; padding: 12px; border-radius: 10px; border: 1px solid rgba(0,0,0,.10); background: #fff; font-size: 13px; color: #111827; font-family: inherit; resize: vertical; min-height: 150px;"></textarea>
                    </div>

                    <button type="submit" style="height: 38px; padding: 0 20px; border-radius: 10px; background: #E31937; color: #fff; font-size: 12px; font-weight: 900; border: none; cursor: pointer; transition: opacity 0.15s;">
                        Send Message
                    </button>
                </form>
            </div>
        </div>

        <!-- Quick Help -->
        <div class="whitePanel">
            <div class="panelHead">
                <div>
                    <h5>Quick Help</h5>
                    <small>Resources and contact info</small>
                </div>
            </div>

            <div style="padding: 14px; display: flex; flex-direction: column; gap: 12px;">
                <div style="padding: 12px; border-radius: 10px; border: 1px solid rgba(0,0,0,.06); background: #f9fafb;">
                    <h4 style="margin: 0 0 4px 0; font-size: 12px; font-weight: 900; color: #111827;">FAQs</h4>
                    <p style="margin: 0 0 8px 0; font-size: 11px; color: #6b7280; font-weight: 700;">Find answers to common questions</p>
                    <a href="{{ route('help') }}" style="font-size: 11px; font-weight: 900; color: #2563eb; text-decoration: none;">View FAQs →</a>
                </div>

                <div style="padding: 12px; border-radius: 10px; border: 1px solid rgba(0,0,0,.06); background: #f9fafb;">
                    <h4 style="margin: 0 0 4px 0; font-size: 12px; font-weight: 900; color: #111827;">Email Support</h4>
                    <p style="margin: 0; font-size: 11px; color: #6b7280; font-weight: 700;">support@tesla.com</p>
                    <p style="margin: 4px 0 0 0; font-size: 10px; color: #9ca3af;">Response within 24 hours</p>
                </div>

                <div style="padding: 12px; border-radius: 10px; border: 1px solid rgba(0,0,0,.06); background: #f9fafb;">
                    <h4 style="margin: 0 0 4px 0; font-size: 12px; font-weight: 900; color: #111827;">Phone Support</h4>
                    <p style="margin: 0; font-size: 11px; color: #6b7280; font-weight: 700;">+13024197620</p>
                    <p style="margin: 4px 0 0 0; font-size: 10px; color: #9ca3af;">Mon-Fri, 9 AM - 6 PM EST</p>
                </div>

                <div style="padding: 12px; border-radius: 10px; border: 1px solid rgba(0,0,0,.06); background: #f9fafb;">
                    <h4 style="margin: 0 0 4px 0; font-size: 12px; font-weight: 900; color: #111827;">Live Chat</h4>
                    <p style="margin: 0 0 8px 0; font-size: 11px; color: #6b7280; font-weight: 700;">Available 24/7</p>
                    <button style="font-size: 11px; font-weight: 900; color: #111827; background: none; border: none; cursor: pointer; padding: 0; text-align: left;">Start Chat →</button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
