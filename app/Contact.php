<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    public $fillable = [
        'name',
        'birthday',
        'phone',
        'address',
        'credit_card',
        'email',
        'last_digits',
        'franchise'
        ];
}