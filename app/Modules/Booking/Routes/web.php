<?php
use App\Modules\Booking\Http\Controllers\JadwalController;
use App\Modules\Booking\Http\Controllers\BookingController;
use App\Modules\Patient\Http\Controllers\AppointmentController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of the routes that are handled
| by your module. Just tell Laravel the URIs it should respond
| to using a Closure or controller method. Build something great!
|
*/

Route::group([
    'namesapace' => 'Backend',
    'prefix' => 'admin',
    'as' => 'admin.',
    'middleware' => 'admin'
], function () {
    Route::group([
        'prefix' => 'calendar/booking',
        'as' => 'booking.',
        'namespace' => 'Booking',
        'middleware' => 'can:view backend',
    ], function () {
        Route::get('/',[BookingController::class, 'getBooking'])->name('getBooking');
        Route::get('docter',[BookingController::class, 'showByDocter'])->name('docter');
        Route::get('therapist',[BookingController::class, 'showByTherapist'])->name('therapist');
        Route::get('room',[BookingController::class, 'showByRoom'])->name('room');

        Route::patch('update', [BookingController::class, 'store'])->name('store');

        Route::get('event-by-room',[BookingController::class, 'getEventByRoom'])->name('event-by-room');
        Route::get('event-by-therapist',[BookingController::class, 'getEventByTherapist'])->name('event-by-therapist');
        Route::get('event-by-docter',[BookingController::class, 'getEventByDocter'])->name('event-by-docter');
        
        Route::post('store-appointment', [BookingController::class, 'storeAppointment'])->name('store-appointment');
        Route::post('store-treatment', [BookingController::class, 'storeTreatment'])->name('store-treatment');
        
    });

    /**
     * Jadwal Pasien
     */
    Route::group([
        'prefix' => 'calendar/jadwal',
        'namespace' => 'Jadwal',
        'as' => 'calendar.',
        'middleware' => 'can:view backend'
    ], function(){
        Route::get('/appointment', [JadwalController::class, 'appointment'])->name('jadwal-appointment');
        Route::post('/appointment', [JadwalController::class, 'appointment'])->name('jadwal-appointment');

        Route::get('/treatment', [JadwalController::class, 'treatment'])->name('jadwal-treatment');
        Route::post('/treatment', [JadwalController::class, 'treatment'])->name('jadwal-treatment');
        
        // Route::get('/next', [JadwalController::class, 'nextSchedule'])->name('next-schedule');
        Route::post('/sendWa', [JadwalController::class, 'sendWa'])->name('sendWa');

		Route::get('/next', [AppointmentController::class, 'nextappointment'])->name('next-schedule');
    });
});
