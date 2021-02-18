<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Loan extends Model
{
    protected $fillable = [
        'person_id', 'guarantor_id', 'interest_percentage', 'amount', 'date', 'state'
    ];

    public function person()
    {
        return $this->belongsTo(Person::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }
}
