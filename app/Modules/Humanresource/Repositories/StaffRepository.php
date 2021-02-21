<?php

namespace App\Modules\Humanresource\Repositories;

use App\Helpers\Auth\Auth;
use App\Modules\Humanresource\Models\Staff;
use Illuminate\Support\Facades\DB;
use App\Exceptions\GeneralException;
use App\Repositories\BaseRepository;
use App\Models\Auth\User;

class StaffRepository extends BaseRepository
{
    /**
     * @return string
     */
    public function model()
    {
        return Staff::class;
    }

    public function create(array $data) : Staff
    {
        return DB::transaction(function () use ($data) {
            //SPlit Name
            $user = new User;
            $splitname = $user->split_name($data['full_name']);

        	$dataStaff = false;
            $dataUser = [
                'full_name' => $data['full_name'],
                'first_name' => $splitname[0],
                'last_name' => $splitname[1] ?? $splitname[1],
                'username' => $data['username'],
                'email' => $data['email'],
                'password' => $data['password'],
                'active' => 1,
                'confirmation_code' => md5(uniqid(mt_rand(), true)),
                'confirmed' => 1,
                'id_klinik' => Auth()->user()->klinik->id_klinik
            ];
            // die(json_encode($data));
            $user = User::create($dataUser);

            if ($user) {
                // Register Staff
                $dataStaff = [
	                'employee_id' => $data['employee_id'],
	                'department_id' => $data['department_id'],
	                'designation_id' => $data['designation_id'],
	                'phone_number' => $data['phone_number'],
	                'religion_id' => $data['religion_id'],
	                'blood_id' => $data['blood_id'],
	                'address' => $data['address'],
	                'place_of_birth' => $data['place_of_birth'],
	                'date_of_birth' => $data['date_of_birth'],
	                'marital_status' => $data['marital_status'],
	                'date_of_joining' => $data['date_of_joining'],
	                'gender' => $data['gender'],
	                'note' => $data['note'],
	                'user_id' => $user->id,
	            ];
	            // die(print_r($dataStaff));
	            $staff = parent::create($dataStaff);

	            if($staff){
	            	\Bouncer::assign($data['role_id'])->to($user);
	            	return $staff;
	            }

            }

            throw new GeneralException(__('exceptions.staff.create_error'));
        });
    }

    public function update(Staff $staff, array $data)
    {
        if(strtolower($staff->user->email) !== strtolower($data['email'])){
            if ($this->checkExists($data['email'])) {
                throw new GeneralException('Email '.$data['email'].' sudah terdaftar!');
            }
        }

        return DB::transaction(function () use ($staff, $data) {
            $user = User::find($staff->user->id);
            // die(json_encode($data));
            $userUpdate = $user->update([
                'full_name' => $data['full_name'],
                'email' => $data['email'],
            ]);

            if ($userUpdate) {
                //Update Data Staff
                $dataStaff = [
                    'employee_id' => $data['employee_id'],
                    'department_id' => $data['department_id'],
                    'designation_id' => $data['designation_id'],
                    'phone_number' => $data['phone_number'],
                    'religion_id' => $data['religion_id'],
                    'blood_id' => $data['blood_id'],
                    'address' => $data['address'],
                    'place_of_birth' => $data['place_of_birth'],
                    'date_of_birth' => $data['date_of_birth'],
                    'marital_status' => $data['marital_status'],
                    'date_of_joining' => $data['date_of_joining'],
                    'gender' => $data['gender'],
                    'note' => $data['note'],
                    'user_id' => $staff->user->id
                ];
                // die(print_r($dataStaff));
                $staffData = $staff->update($dataStaff);

                if($staffData){
                    // $roles = $staff->user->roles()->pluck('name');
                    // die(print_r($roles));

                    $staff->user->roles()->sync($data['role_id']);
                    // \Bouncer::sync($user)->roles(['kasir']);
                    return $staff;
                }

                return $staff;
            }

            throw new GeneralException(trans('staff::exceptions.staff.update_error'));
        });

    }

    protected function checkExists($email) : bool
    {
        return User::where('email', strtolower($email))
                ->count() > 0;
    }
}
