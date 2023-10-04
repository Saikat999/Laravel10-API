<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserApiController;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

//get api for fetch users
Route::get('/users/{id?}',[UserApiController::class,'showUser']);

// post api for add user
Route::post('/add-user',[UserApiController::class,'addUser']);

// post api for add multiple user
Route::post('/add-multiple-user',[UserApiController::class,'addMultipleUser']);

// put api for update user details
Route::put('/update-user-details/{id}',[UserApiController::class,'updateUserDetails']);

// delete api for delete single user
Route::delete('/delete-single-user/{id}',[UserApiController::class,'deleteUser']);

// delete api for delete single user with json
Route::delete('/delete-single-user-with-json',[UserApiController::class,'deleteUserJson']);

// delete api for delete multiple user
Route::delete('/delete-multiple-user/{ids}',[UserApiController::class,'deleteMultipleUser']);

// delete api for delete multipple user with json
Route::delete('/delete-multiple-user-with-json',[UserApiController::class,'deleteMultipleUserJson']);