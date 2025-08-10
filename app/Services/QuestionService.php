<?php

namespace App\Services;

use App\Models\{ Question,Option, Lookup};
use Carbon\Carbon;
use App\Services\TeacherService;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class QuestionService
{
        public static function createQuestion($data)
        {
            $userId = session('user_id');

            $actionType = Lookup::where('type', 'Action_Type')
                ->where('value', 'Create')
                ->first();
            $question = Question::create([
                'Quiz_Id' => $data['quiz_id'],
                'Text' => $data['question_text'],
                'Marks' => $data['marks'],
                'Created_By' => $userId,
                'Action_Type_Id' => $actionType->Id,
            ]);
            $optionKeys = ['a', 'b', 'c', 'd'];
            foreach ($optionKeys as $key) {
                $optionText = $data['option_' . strtolower($key)];
                $isCorrect = $data['correct_option'] === `option_${key}` ? 1 : 0;

                Option::create([
                    'Question_Id' => $question->Id,
                    'Text' => $optionText,
                    'Is_Correct' => $isCorrect,
                    'Created_By' => $userId,
                    'Action_Type_Id' => $actionType->Id,
                ]);
            }

            return $question;
        }

    public static function updateQuestion($data, $id)
    {
        $userId = session('user_id');
        $actionType = Lookup::where('type', 'Action_Type')
            ->where('value', 'Update')
            ->first();
        $question = Question::findOrFail($id);
        $question = $question->update([
            'Text' => $data['question_text'],
            'Marks' => $data['marks'],
            'Created_By' => $userId,
            'Action_Type_Id' => $actionType->Id,
        ]);
        $options = $data['options'];
        foreach ($options as $optionId => $optionData) {
            $text = $optionData['text'];
            $key = $optionData['key'];
            $isCorrect = $data['correct_option'] === $key ? 1 : 0;
            Option::where('Id', $optionId)->update([
                'Text' => $text,
                'Is_Correct' => $isCorrect,
                'Updated_By' => $userId,
                'Action_Type_Id' => $actionType->Id,
            ]);
        }
        return $question;
    }

    public static function removeQuestion($id){
        $question = Question::findOrFail($id);
        foreach ($question->options as $option) {
            $option->delete();
        }
        $isDeleted = $question->delete();
        return $isDeleted;
    }

}