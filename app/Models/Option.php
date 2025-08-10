<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Option extends Model
{
    use SoftDeletes;

    protected $primaryKey = 'Id';
    public $incrementing = true;
    public $keyType = 'int';

    protected $table = 'options';

    protected $fillable = [
        'Question_Id',
        'Text',
        'Is_Correct',
        'Action_Type_Id',
        'Created_By',
        'Updated_By',
        'Deleted_By',
    ];

    protected $dates = [
        'Created_At',
        'Updated_At',
        'Deleted_At',
    ];

    public $timestamps = true;

    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($option) {
            $userId = session('user_id');
            $actionType = Lookup::where('type', 'Action_Type')
                                ->where('value', 'Delete')
                                ->first();
            $option->Deleted_By = $userId;
            $option->Action_Type_Id = $actionType?->Id;
            $option->save();
        });

    }

    public function question()
    {
        return $this->belongsTo(Question::class, 'Question_Id');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'Created_By');
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'Updated_By');
    }

    public function deletedBy()
    {
        return $this->belongsTo(User::class, 'Deleted_By');
    }
}
