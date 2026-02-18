<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvestmentPlan extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'category',
        'strategy',
        'risk_level',
        'nav',
        'one_year_return',
        'min_investment',
        'max_investment',
        'profit_margin',
        'duration_days',
        'duration_label',
        'is_featured',
        'display_order',
    ];

    protected $casts = [
        'nav' => 'decimal:4',
        'one_year_return' => 'decimal:2',
        'min_investment' => 'decimal:2',
        'max_investment' => 'decimal:2',
        'profit_margin' => 'decimal:2',
        'duration_days' => 'integer',
        'is_featured' => 'boolean',
    ];
}

