<?php

use App\Livewire\SkillSelection;
use App\Livewire\Auth\Login;
use App\Livewire\Auth\Register;
use App\Livewire\Lawyer\Dashboard;
use App\Livewire\Lawyer\Profile;
use App\Livewire\Lawyer\Skills;
use App\Livewire\Lawyer\Wallet;
use App\Livewire\Lawyer\Clicks;
use App\Livewire\Lawyer\Transactions;
use App\Http\Controllers\PaymentController;
use Illuminate\Support\Facades\Route;

// صفحات عمومی
Route::get('/', SkillSelection::class)->name('home');
Route::get('/skills', SkillSelection::class)->name('skills.selection');

// احراز هویت
Route::middleware('guest')->group(function () {
    Route::get('/login', Login::class)->name('login');
    Route::get('/register', Register::class)->name('register');
});

// پنل وکیل (نیاز به احراز هویت)
Route::middleware(['auth', 'verified', 'lawyer'])->prefix('lawyer')->name('lawyer.')->group(function () {
    Route::get('/dashboard', Dashboard::class)->name('dashboard');
    Route::get('/profile', Profile::class)->name('profile');
    Route::get('/skills', Skills::class)->name('skills');
    Route::get('/wallet', Wallet::class)->name('wallet');
    Route::get('/clicks', Clicks::class)->name('clicks');
    Route::get('/transactions', Transactions::class)->name('transactions');

    // پرداخت
    Route::get('/payment/create', [PaymentController::class, 'create'])->name('payment.create');
    Route::get('/payment/callback', [PaymentController::class, 'callback'])->name('payment.callback');
});

// لاگاوت
Route::post('/logout', function () {
    Auth::logout();
    session()->invalidate();
    session()->regenerateToken();
    return redirect('/');
})->name('logout');

