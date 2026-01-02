<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController\AuthController;
use App\Http\Controllers\HotelController\HotelController;
use App\Http\Controllers\DashboardController\DashboardController;
use App\Http\Controllers\HotelRoomController\HotelRoomController;
use App\Http\Controllers\BookingController\BookingController;
use App\Http\Controllers\TransportController\TransportController;
use App\Models\Booking\Booking;

/*             AUTH            */

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

/*         USER AREA (AUTH)        */

Route::middleware(['auth'])->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('dashboard');


/*              HOTEL              */
    Route::get('/hotel', [HotelController::class, 'index'])->name('hotel.index');
    Route::get('/hotel/{id}', [HotelController::class, 'show'])
        ->whereNumber('id')
        ->name('hotel.show');

    Route::view('/hotel-crud', 'hotel.hotel-crud')->name('hotel.crud');


/*           HOTEL ROOMS             */
    Route::get('/hotelroom', [HotelRoomController::class, 'index'])->name('hotelroom.index');
    Route::get('/hotelroom/create', [HotelRoomController::class, 'create'])->name('hotelroom.create');

    Route::get('/hotel/{id}/rooms', [HotelRoomController::class, 'manage'])
        ->whereNumber('id')
        ->name('hotel.rooms.manage');

    Route::get('/hotel-room/{id}', [HotelRoomController::class, 'show'])
        ->whereNumber('id')
        ->name('hotelroom.show');

    Route::get('/hotel/{id}/add-room', [HotelRoomController::class, 'create'])
        ->whereNumber('id')
        ->name('hotel.addroom');

    Route::post('/hotelroom/store', [HotelRoomController::class, 'store'])
        ->name('hotelroom.store');


/*             TRANSPORT               */
    Route::prefix('transport')->group(function () {

        Route::get('/', [TransportController::class, 'index'])
            ->name('transport.index');

        Route::get('/locations', [TransportController::class, 'locations'])
            ->name('transport.locations.index');

        Route::get('/locations/{id}', function ($id) {
            return view('transport.locations.show', compact('id'));
        })
        ->whereNumber('id')
        ->name('transport.locations.show');

        Route::view('/crud', 'transport.crud.index')->name('transport.crud');
        Route::view('/schedules/crud', 'transport.schedule.crud')->name('transport.schedule.crud');

        Route::get('/{id}', [TransportController::class, 'show'])
            ->whereNumber('id')
            ->name('transport.show');
    });


    /*
    |--------------------------------------------------------------------------
    | BOOKING (USER + ADMIN SHARED)
    |--------------------------------------------------------------------------
    */

    Route::get('/booking/history', [BookingController::class, 'index'])
        ->name('booking.history');

    Route::view('/booking/create', 'booking.create')->name('booking.create');
    Route::view('/booking/transport', 'booking.transport.create')->name('booking.transport.create');

    /*
    |--------------------------------------------------------------------------
    | UNIVERSAL BOOKING DETAIL
    | - admin dapat lihat semua booking
    | - user hanya dapat lihat booking miliknya
    |--------------------------------------------------------------------------
    */
    Route::get('/booking/{id}', function ($id) {

        $user = auth()->user();

        // jika admin → bisa lihat semua booking
        if ($user->is_admin) {
            $booking = Booking::findOrFail($id);
        } else {
            // user biasa → hanya booking miliknya
            $booking = Booking::where('id', $id)
                ->where('user_id', $user->id)
                ->firstOrFail();
        }

        return view('booking.detail', compact('id', 'booking'));
    })
    ->whereNumber('id')
    ->name('booking.show');


    /*
    |--------------------------------------------------------------------------
    | BOOKING EDIT (HANYA USER)
    |--------------------------------------------------------------------------
    */
    Route::get('/booking/{id}/edit', function ($id) {

        $booking = Booking::where('id', $id)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        return view('booking.edit', compact('id', 'booking'));
    })
    ->whereNumber('id')
    ->name('booking.edit');


    /*
    |--------------------------------------------------------------------------
    | PAYMENT UPLOAD (HANYA USER)
    |--------------------------------------------------------------------------
    */
    Route::get('/booking/{id}/payment', function ($id) {

        $booking = Booking::where('id', $id)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        return view('booking.payment-upload', compact('booking'));
    })
    ->whereNumber('id')
    ->name('booking.payment.upload');
});


/*
|--------------------------------------------------------------------------
| ADMIN PANEL
|--------------------------------------------------------------------------
*/

Route::prefix('admin')
    ->middleware(['auth', 'admin'])
    ->group(function () {

        Route::get('/payments', function () {
            return view('admin.payment.index');
        })->name('admin.payment.index');

        Route::get('/payments/{id}', function ($id) {
            return view('admin.payment.detail', compact('id'));
        })
        ->whereNumber('id')
        ->name('admin.payment.detail');

        Route::get('/bookings', function () {
            return view('admin.booking.index');
        })->name('admin.booking.index');
    });
