<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
        protected $primaryKey = 'Id';
    public $incrementing = true;
    public $keyType = 'int';
    public $timestamps = false;

    protected $fillable = [
        'Name',
        'Email',
        'Password',
        'RoleId',
        'GenderId'
    ];

    public function role()
    {
        return $this->belongsTo(Role::class, 'RoleId');
    }

    public function gender()
    {
        return $this->belongsTo(Lookup::class, 'GenderId');
    }

    public function student()
    {
        return $this->hasOne(Student::class, 'UserId');
    }
   
}