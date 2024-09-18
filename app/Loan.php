<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Loan extends Model
{
    protected $fillable = [
        'person_id', 'guarantor_id', 'interest_percentage',
        'amount', 'date', 'state', 'type', 'period', 'method'
    ];

    public function person()
    {
        return $this->belongsTo(Person::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function loan_renewals()
    {
        return $this->hasMany(LoanRenewal::class);
    }
}
