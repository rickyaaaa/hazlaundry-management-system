<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class LaundryService extends Model
{
    protected $fillable = [
        'name',
        'description',
        'price_per_kg',
        'is_active',
    ];

    protected $casts = [
        'price_per_kg' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class, 'service_id');
    }

    public function multiTransactions(): BelongsToMany
    {
        return $this->belongsToMany(Transaction::class, 'transaction_laundry_service')
            ->withPivot(['quantity', 'price_per_kg', 'subtotal'])
            ->withTimestamps();
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
