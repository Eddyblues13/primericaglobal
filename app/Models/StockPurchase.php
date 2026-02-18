<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockPurchase extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'stock_id',
        'quantity',
        'price_per_share',
        'total_amount',
        'order_type',
        'limit_price',
        'status',
    ];

    protected $casts = [
        'quantity' => 'integer',
        'price_per_share' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'limit_price' => 'decimal:2',
    ];

    /**
     * Get the user that owns the stock purchase.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the stock.
     */
    public function stock()
    {
        return $this->belongsTo(Stock::class);
    }
}
