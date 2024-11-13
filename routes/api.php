<?php

/* use App\Http\Controllers\Api\AttendeeController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\EventController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/login', [AuthController::class, 'login']);

Route::apiResource('events', EventController::class);

Route::apiResource('events.attendees', AttendeeController::class)->scoped()->except(['update']); */


use App\Http\Controllers\Api\AttendeeController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\EventController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

//Authenticated user route
Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


//Authentication routes
Route::post('/login', [AuthController::class, 'login']);


//Publicly accessible routes
Route::get('events', [EventController::class, 'index']);
Route::get('events/{event}', [EventController::class, 'show']);

//Event attendees
Route::get('events/{event}/attendees', [AttendeeController::class, 'index'])->middleware('auth.sanctum');
Route::get('events/{event}/attendees/{attendee}', [AttendeeController::class, 'show']);

// Auth protected routes
Route::middleware('auth:sanctum')->group(function () {

    //Log out
    Route::post('/logout', [AuthController::class, 'logout']);

    // Events
    Route::post('events', [EventController::class, 'store']);
    Route::put('events/{event}', [EventController::class, 'update']);
    Route::delete('events/{event}', [EventController::class, 'destroy']);

    // Event Attendees
    Route::delete('events/{event}/attendees/{attendee}', [AttendeeController::class, 'destroy']);
    Route::post('events/{event}/attendees', [AttendeeController::class, 'store']);
});

// Fallback route for undefined endpoints
Route::fallback(function () {
    return response()->json(['message' => 'This endpoint was not found!'], 404);
});


