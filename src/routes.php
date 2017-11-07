<?php


Route::get('/alonight', function () {
    return 'Such a lonely night';


    $subject =  \App\User::query()->latest()->first();
    \App\User::first()->like($subject);

});