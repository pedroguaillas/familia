<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LoanRenewal extends Model
{
    protected $fillable = [
        'loan_id', 'interest_percentage', 'amount', 'date', 'state'
    ];

    public function loan()
    {
        return $this->belongsTo(Loan::class);
    }
}
