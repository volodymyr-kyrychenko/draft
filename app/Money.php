<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Money extends Model
{
	/**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'money';
	
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'current_amount', 'converting_ratio', 'min_amount', 'max_amount',
    ];
}
