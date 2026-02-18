@extends('layouts.app')

@section('title', 'Premium Primrica Global Capital Vehicles for Sale | Primrica Global Capital Market Hub Inventory')
@section('meta_title', 'Premium Primrica Global Capital Vehicles for Sale | Primrica Global Capital Market Hub
Inventory')
@section('meta_description', 'Browse our collection of premium Primrica Global Capital vehicles. Find your perfect Model
S, Model 3, Model X, or Model Y. Reserve your electric vehicle today with flexible financing options.')
@section('meta_keywords', 'Primrica Global Capital for sale, Primrica Global Capital model s, Primrica Global Capital
model 3, model x, model y, electric vehicles, ev inventory, Primrica Global Capital cars, buy Primrica Global Capital,
Primrica Global Capital dealership')

@section('og_title', 'Primrica Global Capital Premium Inventory - Electric Vehicles for Sale')
@section('og_description', 'Explore our curated selection of Primrica Global Capital vehicles ready for delivery. Model
S, 3, X, and Y available now.')
@section('og_type', 'website')

@section('content')
<!-- Hero Section -->
<section class="bg-white py-16">
    <div class="wrap">
        <div class="max-w-3xl">
            <h1 class="text-[48px] md:text-[64px] font-[900] tracking-[-.04em] text-[#0f1115] mb-4">
                Available Inventory <span class="text-[#E31937]">•</span>
            </h1>
            <p class="text-[15px] md:text-[16px] text-black/60">
                Explore our curated selection of <span class="text-[#E31937] font-[600]">premium Primrica Global Capital
                    vehicles</span> ready for delivery.
            </p>
        </div>
    </div>
</section>

<!-- Inventory Grid -->
<section class="bg-white pb-20">
    <div class="wrap">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($cars as $car)
            <div
                class="rounded-[18px] border border-black/10 bg-white overflow-hidden shadow-[0_10px_30px_rgba(0,0,0,0.08)] hover:shadow-[0_15px_40px_rgba(227,25,55,0.2)] hover:border-[#E31937]/50 transition">
                <a href="{{ route('inventory.show', $car) }}">
                    <div class="h-[300px] bg-[#f3f4f6] relative overflow-hidden">
                        <img src="{{ asset($car->getPrimaryImage()) }}" alt="{{ $car->name }}"
                            class="w-full h-full object-cover transition-transform hover:scale-105" loading="lazy" />
                    </div>
                </a>
                <div class="p-5">
                    <div class="text-[18px] font-[900] tracking-[-.01em] text-[#0f1115] mb-3">
                        {{ $car->name }}
                    </div>
                    <div class="grid grid-cols-3 gap-3 mb-4">
                        @if($car->range_miles)
                        <div>
                            <div class="text-[14px] font-[900] text-[#0f1115]">{{ $car->range_miles }}mi</div>
                            <div class="text-[11px] text-black/45">Range</div>
                        </div>
                        @endif
                        @if($car->zero_to_sixty)
                        <div>
                            <div class="text-[14px] font-[900] text-[#0f1115]">{{ number_format($car->zero_to_sixty, 1)
                                }}s</div>
                            <div class="text-[11px] text-black/45">0-60 mph</div>
                        </div>
                        @endif
                        @if($car->top_speed_mph)
                        <div>
                            <div class="text-[14px] font-[900] text-[#0f1115]">{{ $car->top_speed_mph }}mph</div>
                            <div class="text-[11px] text-black/45">Top Speed</div>
                        </div>
                        @endif
                    </div>
                    <div class="mb-4">
                        <div class="text-[14px] font-[900] text-[#0f1115]">
                            Starting at <span class="text-[#E31937]">${{ number_format($car->price, 2) }}*</span>
                        </div>
                        <div class="text-[12px] text-black/45">
                            After Est. Gas Savings
                        </div>
                    </div>
                    <div class="flex gap-3">
                        <a href="{{ route('inventory.show', $car) }}"
                            class="flex-1 h-[34px] px-4 rounded-md border border-black/15 bg-white text-[#0f1115] text-[12px] font-[800] hover:bg-black/5 hover:border-[#E31937]/50 transition inline-flex items-center justify-center">
                            View Details
                        </a>
                        @auth
                        <a href="{{ route('inventory.show', $car) }}"
                            class="flex-1 h-[34px] px-4 rounded-md text-white text-[12px] font-[800] hover:opacity-90 transition inline-flex items-center justify-center"
                            style="background: #E31937;">
                            Order
                        </a>
                        @else
                        <a href="{{ route('login') }}"
                            class="flex-1 h-[34px] px-4 rounded-md text-white text-[12px] font-[800] hover:opacity-90 transition inline-flex items-center justify-center"
                            style="background: #E31937;">
                            Order
                        </a>
                        @endauth
                    </div>
                </div>
            </div>
            @empty
            <div class="col-span-3 text-center py-16">
                <div class="text-[16px] text-black/50 mb-4">No vehicles available at the moment.</div>
                <a href="{{ route('home') }}"
                    class="text-[14px] font-[700] text-[#E31937] hover:opacity-80 transition-colors">
                    Return to Homepage
                </a>
            </div>
            @endforelse
        </div>
    </div>
</section>
@endsection