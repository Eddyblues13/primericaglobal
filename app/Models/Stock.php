<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Stock extends Model
{
    use HasFactory;

    protected $fillable = [
        'ticker',
        'name',
        'price',
        'change',
        'change_percent',
        'volume',
        'market_cap',
        'domain',
        'description',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'change' => 'decimal:2',
        'change_percent' => 'decimal:2',
    ];
}
