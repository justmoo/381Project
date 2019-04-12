<?php

Route::get('/home', function () {
    $users[] = Auth::user();
    $users[] = Auth::guard()->user();
    $users[] = Auth::guard('eo')->user();

    //dd($users);

    return view('eo.home');
})->name('home');

