<?php

namespace App\Http\Controllers\API;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Auth\EmailVerificationRequest;

class EmailVerificationController extends Controller
{
    public function sendVerificationEmail(Request $request)
    {
        if($request->user()->hasVerifiedEmail()){
            return [
                'message' => 'Already Verified'
            ];
        }
        $request->user()->sendEmailVerificationNotification();
        return [
            'status' => 'verification-link-sent',
            
        ];
    }
    
    // public function verify(EmailVerificationRequest $request)
    // {
    //     if($request->user()->hasVerifiedEmail()) {
    //         return [
    //             'message' => 'Email already verified'
    //         ];
    //     }
    //     if($request->user()->markEmailAsVerified()){
    //         event(new Verified($request->user()));
    //     }
    //     return [
    //         'message'=> 'Email has been verified'
    //     ];
    // }

    public function verify(Request $request)
    {
        $userID = $request['id'];
        $user = User::findOrFail($userID);
        $date = date("Y-m-d g:i:s");
        $user->email_verified_at = $date; 
        $user->save();

        // return response()->json('Email has been verified');
        return 'Email has been verified';
    }
}

// bisa  https://medium.com/@pran.81/how-to-implement-laravels-must-verify-email-feature-in-the-api-registration-b531608ecb99
// belum sukses https://stackoverflow.com/questions/65285530/laravel-8-rest-api-email-verification