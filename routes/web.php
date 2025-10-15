<?php

use App\Http\Controllers\BotManController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view()->file(resource_path('views/welcome.php'));
});

//Dette forteller lavarel hvor den skal gå ved forskjellige URLer
Route::get('/manual_calc.php', function () {
    return view()->file(resource_path('views/manual_calc.php'));
});

Route::get('/chatbot', function () {
    return view()->file(resource_path('views/botman.php'));
});

//Gjør at både GET og POST forespørsler til /manual_calc.php håndteres av samme funksjon
Route::match(['GET','POST'], '/manual_calc.php', function () {
    return view()->file(resource_path('views/manual_calc.php'));
});

Route::match(['get','post'], '/botman', [BotManController::class, 'handle']);
