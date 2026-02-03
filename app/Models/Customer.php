<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Customer extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'phone', 'address', 'is_member', 'member_since', 'total_transactions', 'total_spent', 'points', 'points_earned_rate'];

    protected $casts = [
        'is_member' => 'boolean',
        'member_since' => 'datetime',
        'total_spent' => 'decimal:2',
        'points_earned_rate' => 'decimal:2',
    ];

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    public function addPoints($amount)
    {
        $pointsToAdd = floor($amount / 1000) * $this->points_earned_rate;
        $this->increment('points', $pointsToAdd);
        return $pointsToAdd;
    }

    public function usePoints($points)
    {
        if ($this->points >= $points) {
            $this->decrement('points', $points);
            return true;
        }
        return false;
    }

    public function becomeMember()
    {
        $this->update([
            'is_member' => true,
            'member_since' => Carbon::now(),
        ]);
    }

    public function updateTransactionStats($amount)
    {
        $this->increment('total_transactions');
        $this->increment('total_spent', $amount);
    }
}
