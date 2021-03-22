<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = [
        'loan_id', 'debt', 'interest_amount', 'capital', 'must', 'date', 'state', 'observation'
    ];

    public function loan()
    {
        return $this->belongsTo(Loan::class);
    }
}
