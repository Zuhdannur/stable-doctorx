<?php
use App\Modules\Patient\Http\Controllers\PatientController;
use App\Modules\Patient\Http\Controllers\AppointmentController;
use App\Modules\Patient\Http\Controllers\PrescriptionController;
use App\Modules\Patient\Http\Controllers\TreatmentController;

Route::group(['namespace' => 'Backend', 'prefix' => 'admin', 'as' => 'admin.', 'middleware' => 'admin'], function () {

	Route::group([
	    'prefix'     => 'patient',
	    'as'         => 'patient.',
	    'namespace'  => 'Patient',
	    'middleware' => 'can:view backend',
	], function () {

		Route::get('/', function () {
	        dd('This is the Patient module index page. Build something great!');
	    });

	    Route::get("/inform-concern", function(){
			return View::make("patient::appointment.inform-concern");
		});

	    Route::get("/inform-concern", function(){
			return View::make("patient::appointment.inform-concern");
		});

		Route::get('calendar', [PatientController::class, 'calendar'])->name('calendar');
		Route::get('calendar/load', [PatientController::class, 'calendarload'])->name('calendar.load');


	    /*
	     * Patient
	     */

		Route::get('index', [PatientController::class, 'index'])->name('index');
		Route::get('queues', [PatientController::class, 'queues'])->name('queues');
		Route::get('has-queues', [PatientController::class, 'hasqueues'])->name('has-queues');
		Route::get('beforeafter', [PatientController::class, 'beforeafter'])->name('beforeafter');
		Route::post('beforeafter', [PatientController::class, 'storebeforeafter'])->name('beforeafter.store');
		Route::get('{patientId}/getbeforeafter', [PatientController::class, 'getbeforeafter'])->name('beforeafter.get');

		Route::get('birthday', [PatientController::class, 'birthday'])->name('birthday');
		Route::get('birthdaylist', [PatientController::class, 'birthdaylist'])->name('birthdaylist');

		Route::get('patient/{id}/timeline', [PatientController::class, 'timeline'])->name('timeline');
		Route::post('patient/{id}/timeline', [PatientController::class, 'storetimeline'])->name('timeline');

	    Route::get('create', [PatientController::class, 'create'])->name('create');
	    Route::get('chart', [PatientController::class, 'chart'])->name('chart');
	    Route::post('patient/store', [PatientController::class, 'store'])->name('store');
	    Route::get('{patient}/show', [PatientController::class, 'show'])->name('show');

	    Route::group(['prefix' => 'patient/{patient}'], function () {
	    	Route::get('edit', [PatientController::class, 'edit'])->name('edit');
	    	Route::patch('/', [PatientController::class, 'update'])->name('update');
	    	Route::delete('/', [PatientController::class, 'destroy'])->name('destroy');
	    });

	    /*
	     * Appointment
	     */

	    Route::group(['prefix' => 'appointment'], function () {
			Route::get('index', [AppointmentController::class, 'index'])->name('appointment.index');
		    Route::get('{patientId}/{qId}/create', [AppointmentController::class, 'create'])->name('appointment.create');
		    Route::post('store', [AppointmentController::class, 'store'])->name('appointment.store');
		    Route::get('{appointment}/show', [AppointmentController::class, 'show'])->name('appointment.show');
		    Route::get('loadform', [AppointmentController::class, 'loadform'])->name('appointment.loadform');
			Route::get('{appointment}/edit', [AppointmentController::class, 'getFormEdit'])->name('appointment.getFormEdit');
			Route::patch('{appointment}/',[AppointmentController::class,'saveUpdate'])->name('appointment.update');

	    });

	    /*
	     * Appointment
	     */

	    Route::group(['prefix' => 'prescription'], function () {
			Route::get('index', [PrescriptionController::class, 'index'])->name('prescription.index');
	    	Route::get('{prescription}/show', [PrescriptionController::class, 'show'])->name('prescription.show');
	    	Route::get('{prescription}/edit', [PrescriptionController::class, 'edit'])->name('prescription.edit');
			Route::get('create/{prescription}', [PrescriptionController::class, 'create'])->name('prescription.create');
	    	Route::patch('{prescription}/', [PrescriptionController::class, 'update'])->name('prescription.update');
		    Route::post('store', [PrescriptionController::class, 'store'])->name('prescription.store');
	    });

	    /*
	     * Treatment
	     */

	    Route::group(['prefix' => 'treatment'], function () {
			Route::get('index', [TreatmentController::class, 'index'])->name('treatment.index');
		    Route::get('{patientId}/{qId}/create', [TreatmentController::class, 'create'])->name('treatment.create');
		    Route::post('store', [TreatmentController::class, 'store'])->name('treatment.store');
	    	Route::get('{treatment}/show', [TreatmentController::class, 'show'])->name('treatment.show');
	    	Route::post('update', [TreatmentController::class, 'update'])->name('treatment.update');
	    });
	});

	Route::group([
	    'prefix'     => 'reporting',
	    'as'         => 'reporting.',
	    'namespace'  => 'Reporting',
	    'middleware' => 'can:view backend',
	], function () {
		// Route::get('nextappointment', [AppointmentController::class, 'nextappointment'])->name('nextappointment');
		Route::get('reportingpatient', [PatientController::class, 'reportingpatient'])->name('reportingpatient');
	});

});

use App\Modules\Patient\Http\Controllers\DiagnoseItemController;
Route::group(['namespace' => 'Backend', 'prefix' => 'admin', 'as' => 'admin.', 'middleware' => 'admin'], function () {
	Route::group([
	    'prefix'     => 'masterdata',
	    'as'         => 'masterdata.',
	    'namespace'  => 'Patient',
	    'middleware' => 'can:view backend',
	], function () {

	    /*
	     * Treatment
	     */

	    Route::group(['prefix' => 'diagnoseitem'], function () {
			Route::get('index', [DiagnoseItemController::class, 'index'])->name('diagnoseitem.index');
			Route::get('create', [DiagnoseItemController::class, 'create'])->name('diagnoseitem.create');
			Route::post('store', [DiagnoseItemController::class, 'store'])->name('diagnoseitem.store');

			Route::group(['prefix' => 'group/{group}'], function () {
	            Route::get('edit', [DiagnoseItemController::class, 'edit'])->name('diagnoseitem.edit');
	            Route::patch('/', [DiagnoseItemController::class, 'update'])->name('diagnoseitem.update');
	            Route::delete('/', [DiagnoseItemController::class, 'destroy'])->name('diagnoseitem.destroy');
	        });
	    });
	});

});

Route::get('patient/register', [PatientController::class, 'register'])->name('patient.register')->middleware('log_front_end');
Route::post('patient/register', [PatientController::class, 'storeadmission'])->name('patient.register')->middleware('log_front_end');
Route::get('patient/register-success', [PatientController::class, 'storesuccess'])->name('patient.registersuccess')->middleware('log_front_end');
Route::post('patient/check', [PatientController::class, 'checkpatient'])->name('patient.check')->middleware('log_front_end');
Route::get('patient/{patient}/get', [PatientController::class, 'getpatient'])->name('patient.get')->middleware('log_front_end');
Route::get('patient/{patient}/getflag', [PatientController::class, 'getflag'])->name('patient.getflag')->middleware('log_front_end');
