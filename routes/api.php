<?php

use App\Models\User;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\UserController;
use App\Http\Controllers\API\PengaduanController;
use App\Http\Controllers\API\EmailVerificationController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api', 'verified')->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/pengaduan', [PengaduanController::class, 'index']);
    Route::post('/pengaduan', [PengaduanController::class, 'store']);
    Route::get('pengaduan/{id}', [PengaduanController::class, 'show']);
    Route::delete('/pengaduan/{id}', [PengaduanController::class, 'destroy']);
    Route::put('/pengaduan/{id}', [PengaduanController::class, 'update']);

    Route::get('/dataUser', [UserController::class, 'index']);
});

Route::post('login', [UserController::class, 'login']);
Route::post('register', [UserController::class, 'register']);

Route::post('forgot-password', [UserController::class, 'forgotPassword']);
Route::post('reset-password', [UserController::class, 'resetPassword']);

Route::post('email/verification-notification', [EmailVerificationController::class, 'sendVerificationEmail'])->middleware('auth:sanctum');
// Route::get('email/verify/{id}/{hash}', [EmailVerificationController::class, 'verify'])
//     ->name('verification.verify')
//     ->middleware(['signed', 'throttle:6,1']);

Route::get('email/verify/{id}', [EmailVerificationController::class, 'verify'])->name('verification.verify');

Route::post('/token', function (Request $request) {
    $user = User::where('email', $request->email)->first();
    $tokenResult = $user->createToken('authToken')->accessToken;

    return response()->json([
            'success' => true,
            'message' => 'Login Success!',
            'data'    => $user,
            'token'   => $user->createToken('authToken')->accessToken    
        ], 200);

    // return response()->json([
    //     'access_token' => $tokenResult,
    //     'token_type' => 'Bearer',
    // ], 200);
});