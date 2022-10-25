<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterRequest;
use App\Models\Application;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;


class AuthController extends Controller
{
    public function register(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required',
                'email' => 'required|email:rfc,dns|unique:users,email',
                'username' => 'sometimes|unique:users,username',
                'password' => 'required|min:8',
                'phone_number' => 'required|unique:users,phone_number|min:11',
                'password_confirmation' => 'required|same:password',
            ]);
            
            $user = new User;
            $user->name = $request->name;
            $user->slug = generateSlug('App\Models\User', $request->name);
            $user->email = $request->email;
            $user->username = $request->username;
            $user->phone_number = $request->phone_number;
            $user->password = $request->password;
            $user->save();

            $user->assignRole('student');

            // if ($request->file('cv')) {
            //     $resume = $request->file('cv');
            //     $resume_full_name = time().'_'.$user->name.$user->id.'.'.$resume->extension();
            //     $upload_path = 'resume/';
            //     $resume_url = $upload_path.$resume_full_name;
            //     $success = $resume->move($upload_path, $resume_full_name);
            //     $user->cv = $resume_url;
            // }

            // if ($request->experties) {
            //     $user->experties = $request->experties;
            //     $user->applied = 1;
            // }
            // $user->about_me = $request->about_me;
            // $user->save();
            // $role = Role::where('name','student')->get()->first();
            // $user->assignRole([$role->id]);
            // // auth()->login($user);

            // if ($request->experties) {
            //     // creating new application
            //     $application = new Application;
            //     $application->user_id = $user->id;
            //     $application->status = 1;
            //     $application->save();
            //     //sending mail of application confirmation
            //     Mail::send('emails.application', [
            //         'name' => $request->name,
            //         'email' => $request->email,
            //     ], function($message) use ($request){
            //         $message->from('info@vcourse.net');
            //         $message->to($request->email, 'Admin')->subject('Application on review');
            //     });
            // }else{
            //     //sending welcome mail
            //     Mail::send('emails.welcomeMail', [
            //         'name' => $request->name,
            //         'email' => $request->email,
            //     ], function($message) use ($request){
            //         $message->from('info@vcourse.net');
            //         $message->to($request->email, 'Admin')->subject('Welcome to Vcourse');
            //     });
            // }

            return response()->json([
                'message' => 'Welcome to Vcourse',
                'token' => $user->createToken('AUTH TOKEN')->plainTextToken
            ], 200);

        } catch (\Throwable $e) {
            return response()->json([
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function login(Request $request)
    {
        try {
            $request->validate([
                'phone_number' => 'required',
                'password' => 'required',
            ]);

            $fieldType = filter_var($request->phone_number, FILTER_VALIDATE_EMAIL) ? 'email' : 'phone_number';
            $user = User::where($fieldType == 'email' ? 'email':'phone_number', $request->phone_number)->first();
            $credentials = array($fieldType => $request->phone_number, 'password' => $request->password);

            if ($user->hasRole('superadmin')) {
                if ($this->attempLogin($credentials)) {
                    return response()->json([
                        'message' => 'Admin Login successfully',
                        'role'=> 'admin',
                        'token' => $user->createToken('AUTH TOKEN')->plainTextToken
                    ], 200);
                }
            }
            if ($user->hasRole('Instructor')) {
                if ($this->attempLogin($credentials)) {
                    return response()->json([
                        'message' => 'Instructor Login successfully',
                        'role'=> 'instructor',
                        'token' => $user->createToken('AUTH TOKEN')->plainTextToken
                    ], 200);
                }
            }
            if ($this->attempLogin($credentials)) {
                return response()->json([
                    'message' => 'User Login successfully',
                    'role'=> 'user',
                    'token' => $user->createToken('AUTH TOKEN')->plainTextToken
                ], 200);
            }

            return response()->json([
                'error' => 'Your credentials does not match with our record.'
            ], 401);

        } catch (\Throwable $e) {
            return response()->json([
                'error' => $e->getMessage()
            ]);
        }
    }

    public function attempLogin($data)
    {
        return Auth::guard()->attempt($data);
    }

    public function logout(Request $request)
    {
        try {
            Auth::user()->currentAccessToken()->delete();
            return response()->json([
                'message' => 'Logout successfully'
            ], 200);
        } catch (\Throwable $e) {
            return response()->json([
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
