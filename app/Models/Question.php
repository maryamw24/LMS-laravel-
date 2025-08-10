<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Question extends Model
{
    protected $primaryKey = 'Id';
    public $incrementing = true;
    public $keyType = 'int';

    use SoftDeletes;

    protected $table = 'questions';

    protected $fillable = [
        'Quiz_Id',
        'Text',
        'Marks',
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

        static::deleting(function ($question) {
            $userId = session('user_id');
            $actionType = Lookup::where('type', 'Action_Type')
                                ->where('value', 'Delete')
                                ->first();
            $question->Deleted_By = $userId;
            $question->Action_Type_Id = $actionType?->Id;
            $question->save();
        });

    }

    public function quiz()
    {
        return $this->belongsTo(Quiz::class, 'Quiz_Id');
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

    public function options()
    {
        return $this->hasMany(Option::class, 'Question_Id');
    }
}
