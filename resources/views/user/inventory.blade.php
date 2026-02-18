@extends('layouts.dashboard')

@section('title', 'TESLA Inventory')
@section('topTitle', 'Inventory')

@push('styles')
<style>
    .carGrid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
        gap: 14px;
    }
    .carCard {
        border-radius: 16px;
        border: 1px solid rgba(0,0,0,.06);
        background: #ffffff;
        overflow: hidden;
        display: flex;
        flex-direction: column;
    }
    .carImage {
        width: 100%;
        height: 190px;
        object-fit: cover;
        display: block;
    }
    .carBody {
        padding: 14px;
        display: flex;
        flex-direction: column;
        gap: 8px;
    }
    .carTitle {
        font-size: 13px;
        font-weight: 900;
        color: #111827;
        margin: 0;
    }
    .carMeta {
        font-size: 11px;
        font-weight: 700;
        color: #6b7280;
    }
    .carSpecs {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
        font-size: 10px;
        font-weight: 800;
        color: #6b7280;
    }
    .carSpecPill {
        padding: 4px 8px;
        border-radius: 999px;
        background: #f3f4f6;
    }
    .carFooter {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-top: 4px;
    }
    .carPrice {
        font-size: 14px;
        font-weight: 900;
        color: #111827;
    }
    .carActions {
        display: flex;
        gap: 6px;
    }
    .carButton {
        height: 30px;
        padding: 0 14px;
        border-radius: 10px;
        border: none;
        font-size: 11px;
        font-weight: 900;
        cursor: pointer;
    }
    .carButton.secondary {
        background: #f3f4f6;
        color: #111827;
    }
    .carButton.primary {
        background: #E31937;
        color: #ffffff;
    }
    
    /* Modal Styles */
    .carModalOverlay {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0, 0, 0, 0.6);
        z-index: 1000;
        align-items: center;
        justify-content: center;
        padding: 20px;
    }
    .carModalOverlay.show {
        display: flex;
    }
    .carModal {
        background: #ffffff;
        border-radius: 16px;
        max-width: 600px;
        width: 100%;
        max-height: 90vh;
        overflow-y: auto;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
    }
    .carModalHeader {
        padding: 20px;
        border-bottom: 1px solid rgba(0,0,0,.08);
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    .carModalTitle {
        font-size: 18px;
        font-weight: 900;
        color: #111827;
        margin: 0;
    }
    .carModalClose {
        background: none;
        border: none;
        font-size: 24px;
        color: #6b7280;
        cursor: pointer;
        padding: 0;
        width: 32px;
        height: 32px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 8px;
    }
    .carModalClose:hover {
        background: #f3f4f6;
    }
    .carModalBody {
        padding: 20px;
    }
    .carModalImage {
        width: 100%;
        height: 300px;
        object-fit: cover;
        border-radius: 12px;
        margin-bottom: 20px;
    }
    .carModalInfo {
        display: grid;
        gap: 16px;
    }
    .carModalRow {
        display: flex;
        justify-content: space-between;
        padding: 12px 0;
        border-bottom: 1px solid rgba(0,0,0,.06);
    }
    .carModalLabel {
        font-size: 12px;
        font-weight: 700;
        color: #6b7280;
    }
    .carModalValue {
        font-size: 13px;
        font-weight: 900;
        color: #111827;
    }
    .carModalPrice {
        font-size: 24px;
        font-weight: 900;
        color: #111827;
        text-align: center;
        padding: 20px 0;
        border-top: 2px solid rgba(0,0,0,.1);
        margin-top: 10px;
    }
</style>
@endpush

@section('content')
<div class="wrap" id="inventory">

    <!-- Inventory Header -->
    <div class="surface">
        <div class="heroCard">
            <div class="heroText">
                <h3>Vehicle Inventory</h3>
                <p>Browse available Tesla vehicles and place an order.</p>
            </div>
        </div>
    </div>

    <!-- Error Messages -->
    @if ($errors->any())
        <div style="padding: 12px; margin: 12px 0; border-radius: 10px; background: #fee2e2; border: 1px solid #fecaca;">
            <ul style="margin: 0; padding-left: 20px; font-size: 12px; color: #991b1b;">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if (session('success'))
        <div style="padding: 12px; margin: 12px 0; border-radius: 10px; background: #d1fae5; border: 1px solid #86efac; font-size: 12px; color: #065f46;">
            {{ session('success') }}
        </div>
    @endif

    <!-- Inventory Grid -->
    <div class="lowerGrid" style="grid-template-columns: 1fr; margin-top: 12px;">
        <div class="carGrid">
            @foreach($cars as $car)
                <div class="carCard">
                    @php
                        $primaryImage = $car->getPrimaryImage();
                        $imageSrc = (str_starts_with($primaryImage ?? '', 'http')) ? $primaryImage : asset($primaryImage ?? 'images/tesla1.jpg');
                    @endphp
                    @if($primaryImage)
                        <img src="{{ $imageSrc }}" alt="{{ $car->name }}" class="carImage" onerror="this.src='{{ asset('images/tesla1.jpg') }}';">
                    @endif
                    <div class="carBody">
                        <div>
                            <h5 class="carTitle">{{ $car->name }}</h5>
                            <p class="carMeta">
                                {{ $car->year }} · {{ $car->drivetrain ?? 'Electric' }}
                            </p>
                        </div>
                        <div class="carSpecs">
                            @if($car->range_miles)
                                <span class="carSpecPill">{{ $car->range_miles }} mi range</span>
                            @endif
                            @if($car->zero_to_sixty)
                                <span class="carSpecPill">{{ number_format($car->zero_to_sixty, 2) }}s 0–60 mph</span>
                            @endif
                            @if($car->top_speed_mph)
                                <span class="carSpecPill">{{ $car->top_speed_mph }} mph top speed</span>
                            @endif
                        </div>
                        <div class="carFooter">
                            <div class="carPrice">
                                ${{ number_format($car->price, 2) }}
                            </div>
                            <div class="carActions">
                                <button type="button" class="carButton secondary" onclick="openCarModal({{ $car->id }})">View Details</button>
                                <form method="POST" action="{{ route('dashboard.orders.place') }}">
                                    @csrf
                                    <input type="hidden" name="car_id" value="{{ $car->id }}">
                                    <button type="submit" class="carButton primary">
                                        Order
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>

<!-- Car Details Modal -->
<div class="carModalOverlay" id="carModalOverlay" onclick="closeCarModal(event)">
    <div class="carModal" onclick="event.stopPropagation()">
        <div class="carModalHeader">
            <h3 class="carModalTitle" id="carModalTitle">Vehicle Details</h3>
            <button type="button" class="carModalClose" onclick="closeCarModal()">&times;</button>
        </div>
        <div class="carModalBody" id="carModalBody">
            <!-- Content will be populated by JavaScript -->
        </div>
    </div>
</div>

@push('scripts')
<script>
    const cars = @json($carsForJs ?? []);
    
    function openCarModal(carId) {
        const car = cars.find(c => c.id === carId);
        if (!car) return;
        
        const modal = document.getElementById('carModalOverlay');
        const title = document.getElementById('carModalTitle');
        const body = document.getElementById('carModalBody');
        
        title.textContent = car.name;
        
        let html = '';
        
        // Use primary image (support both image_url and images array)
        const carImage = car.image_url || (car.images && car.images.length > 0 ? car.images[0] : null);
        if (carImage) {
            const imageSrc = carImage.startsWith('http') ? carImage : `/${carImage}`;
            html += `<img src="${imageSrc}" alt="${car.name}" class="carModalImage" onerror="this.src='/images/tesla1.jpg';">`;
        }
        
        html += '<div class="carModalInfo">';
        
        if (car.model) {
            html += `<div class="carModalRow">
                <span class="carModalLabel">Model</span>
                <span class="carModalValue">${car.model}</span>
            </div>`;
        }
        
        if (car.year) {
            html += `<div class="carModalRow">
                <span class="carModalLabel">Year</span>
                <span class="carModalValue">${car.year}</span>
            </div>`;
        }
        
        if (car.variant) {
            html += `<div class="carModalRow">
                <span class="carModalLabel">Variant</span>
                <span class="carModalValue">${car.variant}</span>
            </div>`;
        }
        
        html += `<div class="carModalRow">
            <span class="carModalLabel">Drivetrain</span>
            <span class="carModalValue">${car.drivetrain}</span>
        </div>`;
        
        if (car.range_miles) {
            html += `<div class="carModalRow">
                <span class="carModalLabel">Range</span>
                <span class="carModalValue">${car.range_miles} miles</span>
            </div>`;
        }
        
        if (car.zero_to_sixty) {
            html += `<div class="carModalRow">
                <span class="carModalLabel">0-60 mph</span>
                <span class="carModalValue">${parseFloat(car.zero_to_sixty).toFixed(2)} seconds</span>
            </div>`;
        }
        
        if (car.top_speed_mph) {
            html += `<div class="carModalRow">
                <span class="carModalLabel">Top Speed</span>
                <span class="carModalValue">${car.top_speed_mph} mph</span>
            </div>`;
        }
        
        html += '</div>';
        
        html += `<div class="carModalPrice">$${parseFloat(car.price).toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2})}</div>`;
        
        body.innerHTML = html;
        modal.classList.add('show');
        document.body.style.overflow = 'hidden';
    }
    
    function closeCarModal(event) {
        if (event && event.target !== event.currentTarget) return;
        const modal = document.getElementById('carModalOverlay');
        modal.classList.remove('show');
        document.body.style.overflow = '';
    }
    
    // Close modal on Escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeCarModal();
        }
    });
</script>
@endpush
@endsection
