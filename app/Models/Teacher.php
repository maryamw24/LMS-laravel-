<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Teacher extends Model
{

        protected $primaryKey = 'Id';
    public $incrementing = true;
    public $keyType = 'int';
    protected $table = 'Teachers';

    public $timestamps = false;

    protected $fillable = [
        'DepartmentId',
        'UserId',
    ];

    public function department()
    {
        return $this->belongsTo(Lookup::class, 'DepartmentId');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'UserId');
    }
}
