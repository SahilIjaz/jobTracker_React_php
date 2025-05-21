<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserControllers\UserController;
use App\Http\Controllers\JobControllers\JobController;
// use App\Http\Controllers\JobControllers\applicationController;



Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


// user-routes

Route::get('all-users', [UserController::class, 'index']);
Route::post('create-user', [UserController::class, 'store']);
Route::put('update-user/{id}', [UserController::class, 'update']);
Route::delete('delete-user/{id}', [UserController::class, 'destroy']);
Route::get('/one-user/{id}', [UserController::class, 'show']);
Route::post('login', [UserController::class, 'login']);
Route::get('/logout', [UserController::class, 'logout']);

//job-routes
Route::get('all-jobs', [JobController::class, 'allJobs']);
Route::post('create-job', [JobController::class, 'store']);
// Route::patch('update-job/{jobId}', [JobController::class, 'updateJob']);
// Route::middleware('auth:sanctum')->group(function () {
Route::patch('/update-job/{jobId}', [JobController::class, 'updateJob']);
Route::get('/recruiter-id', [JobController::class, 'getRecruiterId']);
// });
Route::post('apply-job', [JobController::class, 'applyForJob']);
Route::delete('delete-job', [JobController::class, 'deleteJob']);
Route::get('/jobs-by-creator', [JobController::class, 'getJobsByCreator']);

//application-routes
Route::get('all-applications', [JobController::class, 'getAllApplications']);
Route::post('applications-by-email', [JobController::class, 'getApplicationsByEmail']);

Route::get('applications/job/{jobId}', [JobController::class, 'getApplicationsByJob']);

