<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PrizeReservation extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'prize_id', 'user_id', 'status',
    ];
}
