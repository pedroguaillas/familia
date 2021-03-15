<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contribution extends Model
{
    protected $fillable = [
        'person_id', 'amount', 'date', 'type', 'state', 'actions'
    ];

    public function person()
    {
        return $this->belongsTo(Person::class);
    }
}
