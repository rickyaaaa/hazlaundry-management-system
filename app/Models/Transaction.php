<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Transaction extends Model
{
    protected $fillable = [
        'tracking_code',
        'customer_name',
        'phone_number',
        'service_id',
        'weight',
        'price_per_kg',
        'total_price',
        'status',
        'payment_status',
        'notes',
        'estimated_completion',
    ];

    protected $casts = [
        'weight' => 'decimal:2',
        'price_per_kg' => 'decimal:2',
        'total_price' => 'decimal:2',
        'estimated_completion' => 'datetime',
    ];

    // Payment statuses
    public const PAYMENT_STATUSES = [
        'belum_bayar' => 'Belum Bayar',
        'lunas'       => 'Lunas',
    ];

    // All possible statuses in order
    public const STATUSES = [
        'Diproses',
        'Dicuci',
        'Dikeringkan',
        'Disetrika',
        'Selesai',
        'Diambil',
    ];

    public function service(): BelongsTo
    {
        return $this->belongsTo(LaundryService::class, 'service_id');
    }

    public function statusHistories(): HasMany
    {
        return $this->hasMany(TransactionStatus::class)->orderBy('changed_at', 'asc');
    }

    public function latestStatus(): HasMany
    {
        return $this->hasMany(TransactionStatus::class)->latest('changed_at');
    }

    /**
     * Generate a unique tracking code: LDY-YYYY-XXXXX
     */
    public static function generateTrackingCode(): string
    {
        $year = date('Y');
        do {
            $random = strtoupper(substr(str_shuffle('ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789'), 0, 5));
            $code = "LDY-{$year}-{$random}";
        } while (self::where('tracking_code', $code)->exists());

        return $code;
    }

    public function getStatusIndexAttribute(): int
    {
        return array_search($this->status, self::STATUSES) ?: 0;
    }

    public function scopeInProcess($query)
    {
        return $query->whereNotIn('status', ['Selesai', 'Diambil']);
    }

    public function scopeCompleted($query)
    {
        return $query->whereIn('status', ['Selesai', 'Diambil']);
    }

    public function scopeToday($query)
    {
        return $query->whereDate('created_at', today());
    }

    public function scopeThisMonth($query)
    {
        return $query->whereMonth('created_at', now()->month)
                     ->whereYear('created_at', now()->year);
    }
}
