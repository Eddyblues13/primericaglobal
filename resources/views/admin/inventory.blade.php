@extends('layouts.admin')

@section('title', 'Inventory Management - Admin')
@section('topTitle', 'Inventory Management')

@section('content')
<div class="wrap">
    <div class="whitePanel" style="margin-bottom: 12px;">
        <form method="GET" action="{{ route('admin.inventory') }}" style="padding: 16px;">
            <div style="display: flex; gap: 12px; align-items: end;">
                <div style="flex: 1;">
                    <label style="display: block; font-size: 11px; font-weight: 900; color: #6b7280; margin-bottom: 6px;">Search Vehicles</label>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by name, model, or year..." 
                        style="width: 100%; height: 40px; padding: 0 12px; border-radius: 8px; border: 1px solid rgba(0,0,0,.1); font-size: 13px;" />
                </div>
                <button type="submit" style="height: 40px; padding: 0 20px; border-radius: 10px; background: #111827; color: white; font-size: 12px; font-weight: 900; cursor: pointer;">
                    Search
                </button>
                @if(request('search'))
                    <a href="{{ route('admin.inventory') }}" style="height: 40px; padding: 0 20px; border-radius: 10px; background: #ef4444; color: white; font-size: 12px; font-weight: 900; cursor: pointer; display: inline-flex; align-items: center; text-decoration: none;">
                        Clear
                    </a>
                @endif
            </div>
        </form>
    </div>

    <div class="whitePanel">
        <div class="panelHead">
            <div>
                <h5>Tesla Vehicles</h5>
                <small>Total: {{ $cars->total() }} vehicles</small>
            </div>
            <a href="{{ route('admin.inventory.create') }}" style="padding: 8px 16px; border-radius: 8px; background: #111827; color: white; font-size: 12px; font-weight: 900; text-decoration: none; display: inline-flex; align-items: center; gap: 6px;">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M12 5v14M5 12h14" />
                </svg>
                Add Vehicle
            </a>
        </div>

        @if(session('success'))
            <div style="margin: 12px 14px; padding: 12px; background: #d1fae5; border: 1px solid #10b981; border-radius: 8px; color: #065f46; font-size: 12px; font-weight: 700;">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div style="margin: 12px 14px; padding: 12px; background: #fee2e2; border: 1px solid #ef4444; border-radius: 8px; color: #991b1b; font-size: 12px; font-weight: 700;">
                {{ session('error') }}
            </div>
        @endif

        <div style="overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr style="background: #f9fafb; border-bottom: 1px solid rgba(0,0,0,.06);">
                        <th style="padding: 12px 14px; text-align: left; font-size: 11px; font-weight: 900; color: #6b7280;">Image</th>
                        <th style="padding: 12px 14px; text-align: left; font-size: 11px; font-weight: 900; color: #6b7280;">Name</th>
                        <th style="padding: 12px 14px; text-align: left; font-size: 11px; font-weight: 900; color: #6b7280;">Model</th>
                        <th style="padding: 12px 14px; text-align: left; font-size: 11px; font-weight: 900; color: #6b7280;">Year</th>
                        <th style="padding: 12px 14px; text-align: left; font-size: 11px; font-weight: 900; color: #6b7280;">Price</th>
                        <th style="padding: 12px 14px; text-align: left; font-size: 11px; font-weight: 900; color: #6b7280;">Range</th>
                        <th style="padding: 12px 14px; text-align: left; font-size: 11px; font-weight: 900; color: #6b7280;">Status</th>
                        <th style="padding: 12px 14px; text-align: left; font-size: 11px; font-weight: 900; color: #6b7280;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($cars as $car)
                        <tr style="border-bottom: 1px solid rgba(0,0,0,.06);">
                            <td style="padding: 12px 14px;">
                                @php
                                    $primaryImage = $car->getPrimaryImage();
                                    $imageSrc = (str_starts_with($primaryImage ?? '', 'http')) ? $primaryImage : asset($primaryImage ?? 'images/tesla1.jpg');
                                @endphp
                                <img src="{{ $imageSrc }}" alt="{{ $car->name }}" style="width: 60px; height: 40px; object-fit: cover; border-radius: 8px;" onerror="this.src='{{ asset('images/tesla1.jpg') }}';" />
                            </td>
                            <td style="padding: 12px 14px;">
                                <div style="font-size: 12px; font-weight: 900; color: #111827;">{{ $car->name }}</div>
                                @if($car->variant)
                                    <div style="font-size: 11px; color: #6b7280; margin-top: 2px;">{{ $car->variant }}</div>
                                @endif
                            </td>
                            <td style="padding: 12px 14px; font-size: 12px; color: #111827;">{{ $car->model }}</td>
                            <td style="padding: 12px 14px; font-size: 12px; color: #111827;">{{ $car->year }}</td>
                            <td style="padding: 12px 14px; font-size: 12px; font-weight: 900; color: #111827;">${{ number_format((float)$car->price, 2) }}</td>
                            <td style="padding: 12px 14px; font-size: 12px; color: #111827;">{{ $car->range_miles ?? 'N/A' }} mi</td>
                            <td style="padding: 12px 14px;">
                                @if($car->is_available)
                                    <span style="font-size: 10px; font-weight: 900; padding: 4px 8px; border-radius: 999px; background: rgba(16,185,129,.12); color: rgba(5,150,105,1); border: 1px solid rgba(16,185,129,.35);">Available</span>
                                @else
                                    <span style="font-size: 10px; font-weight: 900; padding: 4px 8px; border-radius: 999px; background: rgba(239, 68, 68, .14); color: rgba(220, 38, 38, .95); border: 1px solid rgba(220, 38, 38, .25);">Unavailable</span>
                                @endif
                            </td>
                            <td style="padding: 12px 14px;">
                                <div style="display: flex; gap: 6px;">
                                    <a href="{{ route('admin.inventory.edit', $car) }}" style="padding: 6px 12px; border-radius: 8px; background: #2563eb; color: white; font-size: 11px; font-weight: 900; text-decoration: none; cursor: pointer;">
                                        Edit
                                    </a>
                                    <form method="POST" action="{{ route('admin.inventory.delete', $car) }}" style="display: inline;" onsubmit="return confirm('Are you sure you want to delete this vehicle?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" style="padding: 6px 12px; border-radius: 8px; background: #ef4444; color: white; font-size: 11px; font-weight: 900; cursor: pointer; border: none;">
                                            Delete
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" style="padding: 40px; text-align: center; color: #6b7280; font-size: 13px;">
                                No vehicles found. <a href="{{ route('admin.inventory.create') }}" style="color: #E31937; font-weight: 700; text-decoration: none;">Add your first vehicle</a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($cars->hasPages())
            <div style="padding: 14px; border-top: 1px solid rgba(0,0,0,.06);">
                {{ $cars->links() }}
            </div>
        @endif
    </div>
</div>
@endsection