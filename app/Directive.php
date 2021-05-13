<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Directive extends Model
{
    protected $fillable = ['person_id'];

    public function person()
    {
        return $this->belongsTo(Person::class);
    }
}
