<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Discount extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'min_weight',
        'discount_percentage',
        'is_member_only',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'min_weight' => 'decimal:2',
            'discount_percentage' => 'decimal:2',
            'is_member_only' => 'boolean',
            'is_active' => 'boolean',
        ];
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeForWeight($query, $weight)
    {
        return $query->where('min_weight', '<=', $weight);
    }

    public function scopeForMember($query, $isMember = false)
    {
        return $query->where(function ($q) use ($isMember) {
            $q->where('is_member_only', false);
            if ($isMember) {
                $q->orWhere('is_member_only', true);
            }
        });
    }

    public static function getBestDiscount($weight, $isMember = false)
    {
        return self::active()
            ->forWeight($weight)
            ->forMember($isMember)
            ->orderBy('discount_percentage', 'desc')
            ->first();
    }
}
