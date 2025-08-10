<?php

namespace App\Http\Controllers;
use App\Models\User;
use App\Models\Student;
use App\Models\Teacher;
    use Illuminate\Support\Facades\Session;


use Illuminate\Http\Request;

class UserController extends Controller
{
    public function verify(Request $request){
        $user = User::where('email', $request->email)->where('password', $request->password)->first();
        if(!$user){
            return redirect()->back()->with('error', 'Invalid credentials.');
        }
        else{
            Session::put('user_id', $user->Id);
            Session::put('user_role', $user->role->Name);
            if($user->role->Name == "Admin"){
                return view('Pages.adminDashboard');
            }   
            if($user->role->Name == "Student"){
                $student = Student::where('UserId', $user->Id)->first();
                if ($student) {
                    Session::put('student_id', $student->Id);
                    return redirect()->route('students.dashboard');
                }   
            }
            if($user->role->Name == "Teacher"){
                return view('Pages.teacherDashboard');
            }
        }
    }

    public function logout(){
        Session::flush();
        return redirect('/')->with('success', 'Logged out successfully!');
    }

    public function updateCredentials(Request $request, $userId){
        
        $user = User::findOrFail($userId);
        $user->update([
            'email' => $request->email,
            'password' => $request->password
        ]);
        return redirect()->route('students.index');
    }

}
