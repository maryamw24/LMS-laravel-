<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
{

        protected $primaryKey = 'Id';
    public $incrementing = true;
    public $keyType = 'int';
    protected $table = 'students';

    public $timestamps = false;

    protected $fillable = [
        'Age',
        'UserId'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'UserId');
    }

}
