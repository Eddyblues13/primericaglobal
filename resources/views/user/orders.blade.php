@extends('layouts.dashboard')

@section('title', 'Primrica Global Capital Orders')
@section('topTitle', 'Orders')

@section('content')
<div class="wrap" id="orders">

    <!-- Orders Header -->
    <div class="surface">
        <div class="heroCard">
            <div class="heroText">
                <h3>Order History</h3>
                <p>View your purchase history and order status.</p>
            </div>
        </div>
    </div>

    <!-- Orders List -->
    <div class="lowerGrid" style="grid-template-columns: 1fr; margin-top: 12px;">
        <div class="whitePanel">
            <div class="panelHead">
                <div>
                    <h5>All Orders</h5>
                    <small>Your latest vehicle orders</small>
                </div>
            </div>
            <div style="padding: 14px;">
                @if (session('success'))
                <div
                    style="padding: 12px; margin-bottom: 12px; border-radius: 10px; background: #d1fae5; border: 1px solid #86efac; font-size: 12px; color: #065f46;">
                    {{ session('success') }}
                </div>
                @endif

                @forelse($orders as $order)
                <div class="orderRow">
                    <div class="orderLeft">
                        <div class="thumb">
                            @if($order->car && $order->car->image_url)
                            <img src="{{ $order->car->image_url }}" alt="{{ $order->car->name }}"
                                style="width:100%;height:100%;object-fit:cover;" />
                            @endif
                        </div>
                        <div class="min-w-0">
                            <div class="orderTitle">{{ $order->car?->name ?? 'Primrica Global Capital Vehicle' }}</div>
                            <div class="orderMeta">
                                {{ $order->car?->year }} {{ $order->car?->model }}
                                @if($order->car?->variant)
                                · {{ $order->car->variant }}
                                @endif
                                · {{ $order->order_number }}
                                · {{ $order->created_at->format('M d, Y') }}
                            </div>
                        </div>
                    </div>
                    <div class="orderRight">
                        <div class="price">
                            ${{ number_format($order->total_price, 2) }}
                        </div>
                        <span class="status ok">{{ $order->status }}</span>
                    </div>
                </div>
                @if (! $loop->last)
                <div style="height:1px; background:rgba(0,0,0,.06); margin:8px 0;"></div>
                @endif
                @empty
                <div style="padding: 32px 20px; text-align:center; font-size:13px; color:#6b7280;">
                    You have no orders yet. Visit the Inventory page to place your first order.
                </div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection