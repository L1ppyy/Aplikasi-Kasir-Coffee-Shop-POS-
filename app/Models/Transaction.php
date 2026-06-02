<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $fillable = [
        'invoice_number', 'user_id', 'customer_name', 'customer_phone',
        'subtotal', 'discount_amount', 'discount_percent', 'tax_percent',
        'tax_amount', 'total', 'amount_paid', 'change_amount',
        'payment_method', 'status', 'notes'
    ];

    protected $casts = [
        'subtotal' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'total' => 'decimal:2',
        'amount_paid' => 'decimal:2',
        'change_amount' => 'decimal:2',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(TransactionItem::class);
    }

    public static function generateInvoiceNumber(): string
    {
        $prefix = 'INV-' . date('Ymd') . '-';
        $last = static::where('invoice_number', 'like', $prefix . '%')
            ->orderBy('id', 'desc')
            ->first();
        $number = $last ? (int) substr($last->invoice_number, -4) + 1 : 1;
        return $prefix . str_pad($number, 4, '0', STR_PAD_LEFT);
    }
}
