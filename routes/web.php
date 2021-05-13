<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/mail/{email}', function ($email) {
    $to_name = "SSoftware";
    $to_email = $email;
    $data = array("name" => "Mayank Bisht", "body" => "Task is Completed");
    Mail::send('mail', $data, function ($message) use ($to_name, $to_email) {
        $message->to($to_email)
            ->subject('TASK COMPLETED');
    });
    return view('mail');
})->name('mail');

Route::post('file-import', [UserController::class, 'fileImport'])->name('file-import');
