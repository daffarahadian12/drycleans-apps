<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = ['invoice_number', 'customer_id', 'package_id', 'user_id', 'weight', 'price_per_kg', 'subtotal', 'discount_amount', 'total_amount', 'status', 'order_date', 'estimated_completion', 'actual_completion', 'notes','delivery_status'];

    protected $casts = [
        'weight' => 'decimal:2',
        'price_per_kg' => 'decimal:2',
        'subtotal' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'order_date' => 'datetime',
        'estimated_completion' => 'datetime',
        'actual_completion' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function package()
    {
        return $this->belongsTo(Package::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public static function generateInvoiceNumber()
    {
        $date = Carbon::now()->format('Ymd');
        $lastTransaction = self::whereDate('created_at', Carbon::today())->orderBy('id', 'desc')->first();

        $sequence = $lastTransaction ? (int) substr($lastTransaction->invoice_number, -3) + 1 : 1;

        return 'INV-' . $date . '-' . str_pad($sequence, 3, '0', STR_PAD_LEFT);
    }

    public function calculateTotal()
    {
        $this->subtotal = $this->weight * $this->price_per_kg;

        // Check for applicable discounts
        $discount = Discount::getBestDiscount($this->weight, $this->customer->is_member);

        if ($discount) {
            $this->discount_amount = ($this->subtotal * $discount->discount_percentage) / 100;
        } else {
            $this->discount_amount = 0;
        }

        $this->total_amount = $this->subtotal - $this->discount_amount;

        return $this;
    }

    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    public function scopeByDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('order_date', [$startDate, $endDate]);
    }

    public function getStatusColorAttribute()
    {
        $colors = [
            'pending' => 'warning',
            'process' => 'info',
            'washing' => 'primary',
            'drying' => 'secondary',
            'ironing' => 'dark',
            'ready' => 'success',
            'completed' => 'success',
        ];

        return $colors[$this->status] ?? 'secondary';
    }

    public function getStatusLabelAttribute()
    {
        $labels = [
            'pending' => 'Menunggu',
            'process' => 'Diproses',
            'washing' => 'Dicuci',
            'drying' => 'Dikeringkan',
            'ironing' => 'Disetrika',
            'ready' => 'Siap Diambil',
            'completed' => 'Selesai',
        ];

        return $labels[$this->status] ?? 'Unknown';
    }

    // Helper method untuk format tanggal yang aman
    public function getFormattedOrderDateAttribute()
    {
        return $this->order_date ? $this->order_date->translatedFormat('d M Y') : '-';
    }

    public function getFormattedEstimatedCompletionAttribute()
    {
        return $this->estimated_completion ? $this->estimated_completion->translatedFormat('d M Y') : '-';
    }

    public function getFormattedActualCompletionAttribute()
    {
        return $this->actual_completion ? $this->actual_completion->translatedFormat('d M Y') : '-';
    }
}
