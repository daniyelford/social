<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApiController;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
Route::get('/', function () {
    session()->put('api_key', bin2hex(random_bytes(16)));
    session()->put('api_key_back', bin2hex(random_bytes(16)));
    return view('welcome',['api_key'=>session('api_key')]);
});
Route::get('/create_token', function (Request $request) {
    $apiKey = $request->header('X-API-KEY');
    if (!session()->has('api_key') || !session()->has('api_key_back') ||
        empty(session('api_key')) || empty(session('api_key_back')) ||
        $apiKey !== session('api_key')) {
        return response()->json(['error' => 'دسترسی غیرمجاز']);
    }
    $token = bin2hex(random_bytes(32));
    session()->put('token', $token);
    return response()->json(['token' => $token]);
})->withoutMiddleware([VerifyCsrfToken::class]);
Route::post('/check_token', function (Request $request) {
    $authHeader = $request->header('Authorization');
    $apiKey = $request->header('X-API-KEY');
    $token = str_replace('Bearer ', '', $authHeader);
    if (!session()->has('api_key') || !session()->has('api_key_back') || !session()->has('token') ||
        empty(session('api_key')) || empty(session('api_key_back')) || empty(session('token')) ||
        session('token') !== $token || session('api_key') !== $apiKey) {
        return response()->json(['status' => 'error', 'message' => 'توکن نامعتبر است']);
    }
    return app(ApiController::class)->handle($request);
})->withoutMiddleware([VerifyCsrfToken::class]);
Route::fallback(function () {
    return view('welcome',['api_key'=>session('api_key')]);

});
