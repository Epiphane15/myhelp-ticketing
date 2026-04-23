<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\TicketMessageController;
use App\Http\Controllers\HomeController;

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [HomeController::class, 'index'])->name('home');

Route::middleware('auth')->group(function () {
    Route::resource('tickets', TicketController::class);
    Route::get('/notifications', [App\Http\Controllers\NotificationController::class, 'index'])->name('notifications.index');
    Route::get('/notifications/read', function() {
        auth()->user()->unreadNotifications->markAsRead();
        return back()->with('success', 'Toutes les notifications marquées comme lues.');
    })->name('notifications.read');
    Route::post('tickets/{ticket}/reply', [TicketMessageController::class, 'store'])->name('tickets.reply');
    Route::post('tickets/{ticket}/assign', [TicketController::class, 'assign'])->name('tickets.assign');
    Route::post('tickets/{ticket}/close', [TicketController::class, 'close'])->name('tickets.close');
});
