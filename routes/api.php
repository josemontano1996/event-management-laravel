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
Route::post('/login', [AuthController::class, 'login'])->name('login');


//Publicly accessible routes
Route::get('events', [EventController::class, 'index'])->name('events.index');
Route::get('events/{event}', [EventController::class, 'show'])->name('events.show');

//Event attendees
Route::get('events/{event}/attendees', [AttendeeController::class, 'index'])->name('attendees.index');
Route::get('events/{event}/attendees/{attendee}', [AttendeeController::class, 'show'])->name('attendees.show');

// Auth protected routes
Route::middleware('auth:sanctum')->group(function () {

    //Log out
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Events
    Route::post('events', [EventController::class, 'store'])->name('events.store');
    Route::put('events/{event}', [EventController::class, 'update'])->name('events.update');
    Route::delete('events/{event}', [EventController::class, 'destroy'])->name('events.destroy');

    // Event Attendees
    Route::delete('events/{event}/attendees/{attendee}', [AttendeeController::class, 'destroy'])->name('attendees.destroy');
    Route::post('events/{event}/attendees', [AttendeeController::class, 'store'])->name('attendees.store');
});

// Fallback route for undefined endpoints
Route::fallback(function () {
    return response()->json(['message' => 'This endpoint was not found!'], 404);
});


