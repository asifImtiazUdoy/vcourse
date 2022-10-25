<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Laravel\Socialite\Facades\Socialite;

class SocialiteController extends Controller
{
    public function socialiteRedirect ($driver) {
        return Socialite::driver($driver)->redirect();
    }

    public function socialiteCallback ($driver) {
        try {
            $socialUser = Socialite::driver($driver)->user();
 
            $user = User::updateOrCreate([
                'provider_id' => $socialUser->id,
                'provider_type' => $driver,
            ], [
                'name' => $socialUser->name,
                'email' => $socialUser->email,
                'auth_type' => $driver
            ]);
    
            $user->assignRole('user');
            return response()->json([
                'message' => 'User Login successfully',
                'role'=> 'user',
                'token' => $user->createToken('AUTH TOKEN')->plainTextToken
            ], 200);

        } catch (\Throwable $e) {
            return response()->json([
                'message' => $e->getMessage()
            ]);
        }
    }
}
