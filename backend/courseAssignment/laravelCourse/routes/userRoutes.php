<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\userControllers\CreateUserController;
// Correct controller class name and method
Route::post('/create-user', [CreateUserController::class, 'store']);
?>

