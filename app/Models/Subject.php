<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{

        protected $primaryKey = 'Id';
    public $incrementing = true;
    public $keyType = 'int';
    protected $table = 'Subjects';

    public $timestamps = false;

    protected $fillable = [
        'name',
        'creditHours',
    ];

}
