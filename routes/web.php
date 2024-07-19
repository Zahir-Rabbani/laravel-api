<?php

use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

    //////////////// api authentication
Route::get('/setup', function() {
    $credentails = [
        'email' => 'admin@admin.com',
        'password' => 'password'
    ];
    if(!Auth::attempt($credentails)){
        $user = new \App\Models\User();

        $user->name = 'Admin';
        $user->email = $credentails['email'];
        $user->password = Hash::make($credentails['password']);
        
        $user->save();

        if(Auth::attempt($credentails)){
            $user = Auth::user();

            $adminToken = $user->createToken('admin-token', ['create','update','delete']);
            $updateToken = $user->createToken('update-token', ['create','update']);
            $basicToken = $user->createToken('basic-token');

            return [
                'admin' => $adminToken->plainTextToken,
                'update' => $updateToken->plainTextToken,
                'basic' => $basicToken->plainTextToken,
            ];
        }
       
    }
    
});
require __DIR__.'/auth.php';
