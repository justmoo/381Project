<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    // table name
    protected $table = 'events';
    // primary key
    public $primaryKey = 'id';
    // timestamps
    public $timestamps = true;

    protected $dates = [
        'takes_place_at'
    ];

    public function users()
    {
        return $this->belongsToMany(User::class)->withPivot('ticket');
    }
}
