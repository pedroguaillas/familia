<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = [
        'loan_id', 'interest_amount', 'capital', 'must', 'date', 'state'
    ];

    public function loan()
    {
        return $this->belongsTo(Loan::class);
    }
}
