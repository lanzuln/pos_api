<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;
class DashboardController extends Controller
{
    // page route controller
    function DashboardPage():View{
        return view('pages.dashboard.dashboard-page');
    }

    function profilePage():View{
        return view('pages.dashboard.profile-page');
    }


    // dashboard data controller
    function userProfile(Request $req){
        $email= $req->header('email');
        $user= User::where('email','=', $email)->first();
        return response()->json([
            'status'=>'success',
            'message' => 'Request SEUCCESSFUL',
            'data'=> $user],200);
    }

    function updateProfile(Request $req){
        try {
            $email= $req->header('email');
        $fname=$req->input('fName');
        $lname = $req->input('lName');
        $mobile = $req->input('mobile');
        $password = $req->input('password');
        User::where('email','=', $email)->update([
            "fname" =>  $fname,
            "lname"=>   $lname,
            "mobile"=>    $mobile,
            "password"=>   $password
        ]);

        return response()->json([
            'status'=>'success',
            'message' => 'Request SEUCCESSFUL'],200);
        } catch (Exception $e) {
            return response()->json([
                'status'=>'fail',
                'message' => 'unauthorize'], 401);
        }
    }


}