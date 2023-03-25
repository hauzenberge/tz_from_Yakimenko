<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Task Routes
Route::prefix('tasks')->group(function () {
    Route::get('/', 'TaskController@index');
    Route::post('/', 'TaskController@store');
    Route::get('/{id}', 'TaskController@show');
    Route::put('/{id}', 'TaskController@update');
    Route::delete('/{id}', 'TaskController@destroy');
});

// Subtask Routes
Route::prefix('tasks/{task}/subtasks')->group(function () {
    Route::get('', 'API\SubtaskController@index')->name('subtasks.index');
    Route::post('', 'API\SubtaskController@store')->name('subtasks.store');
    Route::get('{subtask}', 'API\SubtaskController@show')->name('subtasks.show');
    Route::put('{subtask}', 'API\SubtaskController@update')->name('subtasks.update');
    Route::delete('{subtask}', 'API\SubtaskController@destroy')->name('subtasks.destroy');
});
