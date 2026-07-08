<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Promo extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'title',
        'code',
        'percentage',
        'description',
        'image_path',
        'is_active',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'is_active'  => 'boolean',
        'percentage' => 'integer',
    ];

    /**
     * Get the resolved URL for the promo image.
     *
     * @return string
     */
    public function getImageUrlAttribute()
    {
        if ($this->image_path && $this->image_path !== 'images/promo-bg.png') {
            return asset('storage/' . $this->image_path);
        }
        return asset('images/promo-bg.png');
    }
}
