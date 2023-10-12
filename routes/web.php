<?php

use App\Http\Controllers\PromptController;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;
use Laravel\Prompts\Prompt;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/
Route::post('/public/generate-prompt',[PromptController::class,'generate'])->name('generate-prompt');
Route::post('/public/upload-file',[PromptController::class,'upload'])->name('upload-file');

Route::get('/', function () {
    return view('welcome');
});

Route::get('/mask',function(){
    return view('mask');
});
Route::get('/openpose',function(){
    return view('openpose');
});
Route::get('/run', function () {

    Artisan::call('route:clear'); 
    Artisan::call('cache:clear');
    Artisan::call('optimize:clear');
    return 'Command executed successfully';
});
