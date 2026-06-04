<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

Route::get('/', function () {
    return view('welcome');
});

// Auth
Route::get('/register', function () {
    return view('auth.choose-role');
})->name('register');

Route::post('/register/set-role', function (Request $request) {
    $role = $request->input('role');
    if (!in_array($role, ['host', 'user'])) {
        return back();
    }
    session(['register_role' => $role]);
    return redirect()->route('register.form');
})->name('register.set-role');

Route::get('/login', function () {
    return redirect('/');
})->name('login');

Route::get('/register/form', function () {
    return view('auth.register');
})->name('register.form');

Route::post('/register', [App\Http\Controllers\Auth\RegisterController::class, 'store'])->name('register.store');
Route::get('/auth/google/redirect', function () {
    return redirect('/register');
})->name('auth.google.redirect');