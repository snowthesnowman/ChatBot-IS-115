<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view()->file(resource_path('views/welcome.php'));
});

//Dette forteller lavarel hvor den skal gå ved manual_calc.php
Route::get('/manual_calc.php', function () {
    return view()->file(resource_path('views/manual_calc.php'));
});

//Gjør at både GET og POST forespørsler til /manual_calc.php håndteres av samme funksjon
Route::match(['GET','POST'], '/manual_calc.php', function () {
    return view()->file(resource_path('views/manual_calc.php'));
});