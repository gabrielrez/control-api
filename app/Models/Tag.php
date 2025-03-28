<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    protected $fillable = [
        'name',
        'expense_id'
    ];

    public function expense()
    {
        return $this->belongsTo(Expense::class);
    }
}
