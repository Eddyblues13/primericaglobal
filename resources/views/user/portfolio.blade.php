@extends('layouts.dashboard')

@section('title', 'TESLA Portfolio')
@section('topTitle', 'Portfolio')

@section('content')
<div class="wrap" id="overlay">

                <!-- Portfolio Header -->
                <div class="surface">
                    <div class="heroCard">
                        <div class="heroText">
                            <h3>Portfolio Overview</h3>
                            <p>View your complete investment portfolio.</p>
                        </div>
                    </div>
                </div>

                                <!-- Portfolio Content -->
                <div class="statGrid" style="margin-top: 12px;">
                    <div class="stat">
                        <div>
                            <small>Total Portfolio Value</small>
                            <strong>$72,033</strong>
                            <div class="sub up">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M3 17l6-6 4 4 8-8" />
                                    <path d="M21 7v6h-6" />
                                </svg>
                                +11.1% this month
                            </div>
                        </div>
                        <div class="chip">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#10b981" stroke-width="2">
                                <path d="M3 17l6-6 4 4 8-8" />
                                <path d="M21 7v6h-6" />
                            </svg>
                        </div>
                    </div>
                    <div class="stat">
                        <div>
                            <small>Investments</small>
                            <strong>$5,669</strong>
                            <div class="sub" style="color:#2563eb;">
                                2 active investments
                            </div>
                        </div>
                        <div class="chip">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#2563eb" stroke-width="2">
                                <path d="M12 8v4l3 3" />
                                <path d="M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                            </svg>
                        </div>
                    </div>
                    <div class="stat">
                        <div>
                            <small>Stock Holdings</small>
                            <strong>$26,153</strong>
                            <div class="sub" style="color:#7c3aed;">
                                2 stock positions
                            </div>
                        </div>
                        <div class="chip">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#7c3aed" stroke-width="2">
                                <path d="M4 19V5" />
                                <path d="M4 19h16" />
                                <path d="M8 15V9" />
                                <path d="M12 15V7" />
                                <path d="M16 15v-5" />
                            </svg>
                        </div>
                    </div>
                    <div class="stat">
                        <div>
                            <small>Cash Balance</small>
                            <strong>$40,211</strong>
                            <div class="sub" style="color:#9ca3af;">
                                Available for trading
                            </div>
                        </div>
                        <div class="chip">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#9ca3af" stroke-width="2">
                                <path d="M21 12V7a2 2 0 0 0-2-2H5a2 2 0 0 0-2 2v10a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-2" />
                                <path d="M21 12h-7a2 2 0 0 0 0 4h7" />
                            </svg>
                        </div>
                    </div>
                </div>
            </div>                        </div>

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
                                    <label style="display: block; font-size: 11px; font-weight: 900; color: #6b7280; margin-bottom: 6px;">Subject</label>
                                    <select name="subject" required style="width: 100%; height: 38px; padding: 0 12px; border-radius: 10px; border: 1px solid rgba(0,0,0,.10); background: #fff; font-size: 13px; color: #111827; cursor: pointer;">
                                        <option value="">Select a topic</option>
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
                                <a href="{{ route('help') }}" style="font-size: 11px; font-weight: 900; color: #2563eb; text-decoration: none;">View FAQs ÃƒÂ¢Ã¢â‚¬Â Ã¢â‚¬â„¢</a>
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
                                <button style="font-size: 11px; font-weight: 900; color: #111827; background: none; border: none; cursor: pointer; padding: 0; text-align: left;">Start Chat ÃƒÂ¢Ã¢â‚¬Â Ã¢â‚¬â„¢</button>
                            </div>
                        </div>
                    </div>
                </div>
            
</div>
@endsection