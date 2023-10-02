<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\IndexController;
use App\Http\Controllers\BoardController;
use App\Http\Controllers\ColumnController;
use App\Http\Controllers\CardController;

use App\Http\Controllers\User\SteamAuthController;
use App\Http\Controllers\User\GoogleAuthController;
use App\Http\Controllers\User\AuthController;
use Inertia\Inertia;
use App\Models\Board;
use App\Models\Column;
use App\Models\User;
use Illuminate\Support\Facades\Auth;


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

Inertia::share('appName', config('app.name'));
Inertia::share('toast.success', fn() => session()->get('success'));
Inertia::share('toast.error', fn() => session()->get('error'));
/*
Inertia::share('toast', function() { 
  if(session()->get('errors')) return session()->get('errors')->all();
});
*/

// dd(Config::get('lang'));


Route::get('auth', [AuthController::class, 'index'])->name('login');
Route::post('auth', [AuthController::class, 'login']);
Route::get('steam', SteamAuthController::class);
Route::get('google', GoogleAuthController::class);
Route::get('logout', [AuthController::class, 'logout']);

Route::middleware(['auth'])->group(function () {
  Inertia::share('boards', fn() => Board::where('user_id', Auth::id())->get());
  Inertia::share('columns', function() {
    if (Route::current()->parameters && isset(Route::current()->parameters['board'])) {
      return Column::where('board_id', Route::current()->parameters['board'])->orderBy('position', 'asc')->get();
    }
  });
  Inertia::share('user', fn() => Auth::user());

  Route::get('/', [IndexController::class, 'index'])->name('index');
  Route::get('author', [IndexController::class, 'author'])->name('author');
  Route::get('about', [IndexController::class, 'about'])->name('about');
  Route::resource('boards', BoardController::class);
  Route::put('boards/switch/{boardId}', [BoardController::class, 'switch']);
  Route::resource('columns', ColumnController::class);
  Route::put('columns/sort/{boardId}', [ColumnController::class, 'sort']);
  Route::resource('cards', CardController::class);
  Route::put('cards/todo/{cardId}', [CardController::class, 'todo']);
  Route::put('cards/sort/{columndId}/{cardId}', [CardController::class, 'sort']);
  Route::get('geturldata', [CardController::class, 'geturldata']);
  Route::get('getimage', [CardController::class, 'getimage']);
});
