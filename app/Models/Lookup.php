<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Lookup extends Model
{

        protected $primaryKey = 'Id';
    public $incrementing = true;
    public $keyType = 'int';
    protected $table = 'lookup';

    public $timestamps = false;

    protected $fillable = [
        'id',
        'type',
        'value',
    ];
}
