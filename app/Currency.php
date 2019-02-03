<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Currency extends Model
{
    protected $fillable = ['code', 'title'];

    protected $primaryKey = 'code';

    public $incrementing = false;

    public $timestamps = false;

}
