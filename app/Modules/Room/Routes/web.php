<?php

use App\Modules\Room\Http\Controllers\GroupController;
use App\Modules\Room\Http\Controllers\FloorController;
use App\Modules\Room\Http\Controllers\RoomController;

Route::group(['namespace' => 'Backend', 'prefix' => 'admin', 'as' => 'admin.', 'middleware' => 'admin'], function () {

	Route::group([
	    'prefix'     => 'masterdata/room',
	    'as'         => 'room.',
	    'namespace'  => 'Room',
	    'middleware' => 'can:view backend',
	], function () {

		Route::get('/', function () {
	        dd('Modul Ruangan!');
	    });

	    /*
	     * Room Group
	     */
	    Route::group(['namespace' => 'group'], function () {

	        Route::get('group', [GroupController::class, 'index'])->name('group.index');

	        Route::get('group/create', [GroupController::class, 'create'])->name('group.create');
	        Route::post('group', [GroupController::class, 'store'])->name('group.store');

	        Route::group(['prefix' => 'group/{group}'], function () {
	            Route::get('edit', [GroupController::class, 'edit'])->name('group.edit');
	            Route::patch('/', [GroupController::class, 'update'])->name('group.update');
	            Route::delete('/', [GroupController::class, 'destroy'])->name('group.destroy');
	        });
	    });

	    /*
	     * Room Floor
	     */
	    Route::group(['namespace' => 'floor'], function () {

	        Route::get('floor', [FloorController::class, 'index'])->name('floor.index');

	        Route::get('floor/create', [FloorController::class, 'create'])->name('floor.create');
	        Route::post('floor', [FloorController::class, 'store'])->name('floor.store');

	        Route::group(['prefix' => 'floor/{floor}'], function () {
	            Route::get('edit', [FloorController::class, 'edit'])->name('floor.edit');
	            Route::patch('/', [FloorController::class, 'update'])->name('floor.update');
	            Route::delete('/', [FloorController::class, 'destroy'])->name('floor.destroy');
	        });
	    });

	    /*
	     * Room
	     */

		Route::get('room', [RoomController::class, 'index'])->name('index');
		Route::get('room/getbygroupid/{id}', [RoomController::class, 'getByGroupId'])->name('getbygroupid');
	    Route::get('room/create', [RoomController::class, 'create'])->name('create');
	    Route::post('room', [RoomController::class, 'store'])->name('store');

	    Route::group(['prefix' => 'room/{room}'], function () {
	    	Route::get('edit', [RoomController::class, 'edit'])->name('edit');
	    	Route::patch('/', [RoomController::class, 'update'])->name('update');
	    	Route::delete('/', [RoomController::class, 'destroy'])->name('destroy');
	    });
	});

});