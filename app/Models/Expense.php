<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    protected $fillable = ['user_id', 'title', 'description', 'amount', 'category', 'expense_date'];
    protected $casts = ['expense_date' => 'date', 'amount' => 'decimal:2'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
