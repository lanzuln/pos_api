<?php

use Illuminate\Http\Request;
use App\Http\Controllers\userController;
use App\Http\Controllers\DashboardController;
use App\Http\Middleware\tokerVerificationMiddleware;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

// Page Routes
// ----auth
Route::get('/userLogin',[userController::class,'LoginPage']);
Route::get('/userRegistration',[userController::class,'RegistrationPage']);
Route::get('/sendOtp',[userController::class,'SendOtpPage']);
Route::get('/verifyOtp',[userController::class,'VerifyOTPPage']);
Route::get('/resetPassword',[userController::class,'ResetPasswordPage'])->middleware([tokerVerificationMiddleware::class]);
Route::get('/logout',[userController::class,'userLogout']);

// -----dashboard
Route::get('/dashboard',[DashboardController::class,'DashboardPage'])->middleware([tokerVerificationMiddleware::class]);
Route::get('/profile',[DashboardController::class,'profilePage'])->middleware([tokerVerificationMiddleware::class]);






// API Routes
// -------- auth
Route::post('/user_registration',[userController::class,'userRegistration']);
Route::post('/user_login',[userController::class,'userLogin']);
Route::post('/send_otp',[userController::class,'sendOTP']);
Route::post('/verify_otp',[userController::class,'verifyOTP']);
Route::post('/reset_password',[userController::class,'resetPass'])->middleware([tokerVerificationMiddleware::class]);



// ----------dashboard
Route::get('/get_profile',[DashboardController::class,'userProfile'])->middleware([tokerVerificationMiddleware::class]);
Route::post('/profile_update',[DashboardController::class,'updateProfile'])->middleware([tokerVerificationMiddleware::class]);




// Category API for CRUD
Route::post("/create-category",[CategoryController::class,'CategoryCreate'])->middleware([TokenVerificationMiddleware::class]);
Route::get("/list-category",[CategoryController::class,'CategoryList'])->middleware([TokenVerificationMiddleware::class]);
Route::post("/delete-category",[CategoryController::class,'CategoryDelete'])->middleware([TokenVerificationMiddleware::class]);
Route::post("/update-category",[CategoryController::class,'CategoryUpdate'])->middleware([TokenVerificationMiddleware::class]);