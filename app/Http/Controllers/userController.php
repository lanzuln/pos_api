<?php

namespace App\Http\Controllers;
use App\Models\User;
use App\Mail\OTPMail;
use App\Helper\JWTToken;
use App\Exceptions\Handler;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;


class userController extends Controller
{

    // page route =====================================
    function LoginPage():View{
        return view('pages.auth.login-page');
    }
    function RegistrationPage():View{
        return view('pages.auth.registration-page');
    }
    function SendOtpPage():View{
        return view('pages.auth.send-otp-page');
    }
    function VerifyOTPPage():View{
        return view('pages.auth.verify-otp-page');
    }
    function ResetPasswordPage():View{
        return view('pages.auth.reset-pass-page');
    }




    // auth controller ===========================
    function userRegistration(Request $req){
        try {
             User::create([
                'fName'=>$req->input('fName'),
                'lName'=>$req->input('lName'),
                'email'=>$req->input('email'),
                'mobile'=>$req->input('mobile'),
                'password'=>$req->input('password')
            ]);
             return response()->json([
                'status'=>'success',
                'message' => 'user registered successfully'], 200);

            }
        catch (Exception $e) {
            return response()->json([
                'status'=>'faild',
                'message' => 'user registration failed'], 401);
        }
    }

    function userLogin(Request $req){
        $count= User::where('email','=',$req->input('email'))
            ->where('password','=',$req->input('password'))
            ->select('id')->first();

            if ($count !== null) {

                $token= JWTToken::CreateToken($req->input('email'), $count->id);
                return response()->json([
                    'status'=>'success',
                    'message' => 'USER LOGIN SEUCCESSFUL',
                    'token'=> $token ], 200)->cookie('token',$token,60*60*24);

            }else{
                return response()->json([
                    'status'=>'failed',
                    'message' => 'unauthorized'], 401);
            }
    }

    function sendOTP(Request $req){
        $email = $req->input('email');
        $otp=rand(1000,9999);

        $count= User::where('email','=',$email)->count();

        if ($count==1) {
            // otp send
            Mail::to($email)->send(new OTPMail($otp));

            // otp update column
            User::where('email','=',$email)->update(['otp'=>$otp]);


            return response()->json([
                'status'=>'success',
                'message' => '4 digit OTP code has been send to your email address'], 200);

        }else{
            return response()->json([
                'status'=>'faild',
                'message' => 'Wrong OTP'], 200);
        }
    }

    function verifyOTP(Request $req){
        $email = $req->input('email');
        $otp= $req->input('otp');
        $count= User::where('email','=',$email)
        ->where('otp','=',$otp)
        ->count();
        if ($count==1) {
            // otp update to 0
            User::where('email',$email)->update(['otp'=>'0']);

            // token issue
            $token= JWTToken::CreateTokenForSetPassword($req->input('email'));
            return response()->json([
                'status'=>'success',
                'message' => 'OTP Verification successfull',
                'token'=> $token], 200)->cookie('token',$token,60*60*24);

        }else{
            return response()->json([
                'status'=>'failed',
                'message' => 'unauthorise'], 401);
        }

    }

    function resetPass(Request $req){


       try {
        $email= $req->header('email');
        $password=$req->input('password');
        User::where('email','=',$email)->update(['password'=>$password]);

        return response()->json([
            'status'=>'success',
            'message' => 'Password reset successfull'], 200);
       } catch (Exception $exception) {
        return response()->json([
            'status'=>'failed',
            'message' => 'something went wrong'], 401);
       }
    }

    function userLogout(){
        return redirect('userLogin')->cookie('token','',-1);

    }



}