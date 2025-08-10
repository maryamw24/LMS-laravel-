<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
        protected $primaryKey = 'Id';
    public $incrementing = true;
    public $keyType = 'int';
    public $imestamps = false;

    protected $fillable = [
        'name',
    ];
}
