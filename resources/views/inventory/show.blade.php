@extends('layouts.app')

@section('title', $car->name . ' - TESLA Inventory')

@push('styles')
<style>
    .car-image-slider {
        position: relative;
        width: 100%;
        height: 500px;
        overflow: hidden;
        border-radius: 18px;
        background: #f3f4f6;
    }
    .car-image-slider img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: none;
    }
    .car-image-slider img.active {
        display: block;
        animation: fadeIn 0.5s;
    }
    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }
    .slider-nav {
        position: absolute;
        top: 50%;
        transform: translateY(-50%);
        background: rgba(0,0,0,0.6);
        color: white;
        border: none;
        width: 44px;
        height: 44px;
        border-radius: 50%;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
        font-weight: 900;
        transition: all 0.2s;
        z-index: 10;
    }
    .slider-nav:hover {
        background: rgba(227,25,55,0.9);
    }
    .slider-nav.prev {
        left: 16px;
    }
    .slider-nav.next {
        right: 16px;
    }
    .slider-dots {
        position: absolute;
        bottom: 16px;
        left: 50%;
        transform: translateX(-50%);
        display: flex;
        gap: 8px;
        z-index: 10;
    }
    .slider-dot {
        width: 10px;
        height: 10px;
        border-radius: 50%;
        background: rgba(255,255,255,0.5);
        border: 2px solid rgba(255,255,255,0.8);
        cursor: pointer;
        transition: all 0.2s;
    }
    .slider-dot.active {
        background: #E31937;
        border-color: #E31937;
    }
    .thumbnail-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(80px, 1fr));
        gap: 8px;
        margin-top: 12px;
    }
    .thumbnail {
        width: 100%;
        aspect-ratio: 1;
        border-radius: 8px;
        overflow: hidden;
        border: 2px solid transparent;
        cursor: pointer;
        transition: all 0.2s;
    }
    .thumbnail:hover {
        border-color: #E31937;
    }
    .thumbnail.active {
        border-color: #E31937;
    }
    .thumbnail img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const images = @json($car->getAllImages());
    if (images.length === 0) return;

    let currentIndex = 0;
    const mainImage = document.getElementById('main-car-image');
    const thumbnails = document.querySelectorAll('.thumbnail');
    const dots = document.querySelectorAll('.slider-dot');
    const prevBtn = document.getElementById('slider-prev');
    const nextBtn = document.getElementById('slider-next');

    function showImage(index) {
        currentIndex = index;
        const imagePath = images[index].startsWith('http') ? images[index] : '{{ asset("") }}' + images[index];
        mainImage.src = imagePath;
        
        thumbnails.forEach((thumb, i) => {
            thumb.classList.toggle('active', i === index);
        });
        
        dots.forEach((dot, i) => {
            dot.classList.toggle('active', i === index);
        });
    }

    function nextImage() {
        currentIndex = (currentIndex + 1) % images.length;
        showImage(currentIndex);
    }

    function prevImage() {
        currentIndex = (currentIndex - 1 + images.length) % images.length;
        showImage(currentIndex);
    }

    if (prevBtn) prevBtn.addEventListener('click', prevImage);
    if (nextBtn) nextBtn.addEventListener('click', nextImage);

    thumbnails.forEach((thumb, index) => {
        thumb.addEventListener('click', () => showImage(index));
    });

    dots.forEach((dot, index) => {
        dot.addEventListener('click', () => showImage(index));
    });

    // Auto-play slider (optional)
    // setInterval(nextImage, 5000);
});
</script>
@endpush

@section('content')
    <!-- Car Detail Section -->
    <section class="bg-white py-16">
        <div class="wrap">
            <div class="mb-6">
                <a href="{{ route('inventory') }}" class="inline-flex items-center gap-2 text-[13px] font-[700] text-[#E31937] hover:opacity-80 transition-colors mb-4">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M19 12H5M12 19l-7-7 7-7" />
                    </svg>
                    Back to Inventory
                </a>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- Image Slider -->
                <div>
                    <div class="car-image-slider">
                        @if(!empty($car->getAllImages()))
                            <img id="main-car-image" src="{{ asset($car->getAllImages()[0]) }}" alt="{{ $car->name }}" class="active" />
                            @if(count($car->getAllImages()) > 1)
                                <button class="slider-nav prev" id="slider-prev">‹</button>
                                <button class="slider-nav next" id="slider-next">›</button>
                                <div class="slider-dots">
                                    @foreach($car->getAllImages() as $index => $image)
                                        <div class="slider-dot {{ $index === 0 ? 'active' : '' }}" data-index="{{ $index }}"></div>
                                    @endforeach
                                </div>
                            @endif
                        @else
                            <img src="{{ asset('images/tesla1.jpg') }}" alt="{{ $car->name }}" />
                        @endif
                    </div>

                    @if(count($car->getAllImages()) > 1)
                        <div class="thumbnail-grid">
                            @foreach($car->getAllImages() as $index => $image)
                                <div class="thumbnail {{ $index === 0 ? 'active' : '' }}" data-index="{{ $index }}">
                                    <img src="{{ asset($image) }}" alt="{{ $car->name }} Thumbnail {{ $index + 1 }}" />
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>

                <!-- Car Details -->
                <div>
                    <h1 class="text-[32px] md:text-[40px] font-[900] tracking-[-.02em] text-[#0f1115] mb-2">
                        {{ $car->name }}
                    </h1>
                    @if($car->variant)
                        <p class="text-[16px] text-black/60 mb-6">{{ $car->variant }}</p>
                    @endif

                    <div class="mb-8">
                        <div class="text-[28px] font-[900] text-[#E31937] mb-2">
                            ${{ number_format($car->price, 2) }}
                        </div>
                        <div class="text-[14px] text-black/50">
                            Starting price
                        </div>
                    </div>

                    <!-- Specs Grid -->
                    <div class="grid grid-cols-3 gap-4 mb-8 p-6 bg-[#f9fafb] rounded-[12px]">
                        @if($car->range_miles)
                        <div>
                            <div class="text-[20px] font-[900] text-[#0f1115]">{{ $car->range_miles }}mi</div>
                            <div class="text-[12px] text-black/50 mt-1">Range</div>
                        </div>
                        @endif
                        @if($car->zero_to_sixty)
                        <div>
                            <div class="text-[20px] font-[900] text-[#0f1115]">{{ number_format($car->zero_to_sixty, 1) }}s</div>
                            <div class="text-[12px] text-black/50 mt-1">0-60 mph</div>
                        </div>
                        @endif
                        @if($car->top_speed_mph)
                        <div>
                            <div class="text-[20px] font-[900] text-[#0f1115]">{{ $car->top_speed_mph }}mph</div>
                            <div class="text-[12px] text-black/50 mt-1">Top Speed</div>
                        </div>
                        @endif
                    </div>

                    <!-- Additional Details -->
                    <div class="mb-8 space-y-3">
                        @if($car->year)
                        <div class="flex justify-between py-2 border-b border-black/5">
                            <span class="text-[14px] font-[700] text-black/60">Year</span>
                            <span class="text-[14px] font-[900] text-[#0f1115]">{{ $car->year }}</span>
                        </div>
                        @endif
                        @if($car->model)
                        <div class="flex justify-between py-2 border-b border-black/5">
                            <span class="text-[14px] font-[700] text-black/60">Model</span>
                            <span class="text-[14px] font-[900] text-[#0f1115]">{{ $car->model }}</span>
                        </div>
                        @endif
                        @if($car->drivetrain)
                        <div class="flex justify-between py-2 border-b border-black/5">
                            <span class="text-[14px] font-[700] text-black/60">Drivetrain</span>
                            <span class="text-[14px] font-[900] text-[#0f1115]">{{ $car->drivetrain }}</span>
                        </div>
                        @endif
                    </div>

                    <!-- Order Button -->
                    <div class="flex gap-4">
                        @auth
                            <form method="POST" action="{{ route('dashboard.orders.place') }}" class="flex-1">
                                @csrf
                                <input type="hidden" name="car_id" value="{{ $car->id }}" />
                                <input type="hidden" name="quantity" value="1" />
                                <button type="submit" class="w-full h-[50px] rounded-md text-white text-[14px] font-[900] hover:opacity-90 transition cursor-pointer" style="background: #E31937;">
                                    Place Order - ${{ number_format($car->price, 2) }}
                                </button>
                            </form>
                        @else
                            <a href="{{ route('login') }}" class="flex-1 h-[50px] rounded-md text-white text-[14px] font-[900] hover:opacity-90 transition cursor-pointer inline-flex items-center justify-center" style="background: #E31937;">
                                Login to Order
                            </a>
                        @endauth
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
