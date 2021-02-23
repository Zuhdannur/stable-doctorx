<?php

namespace App\Modules\Humanresource\Http\Controllers;

use Illuminate\Http\Request;
use App\Modules\Humanresource\Models\Department;
use App\Modules\Humanresource\Http\Requests\Staff\ManageStaffRequest;
use App\Modules\Humanresource\Http\Requests\Department\StoreDepartmentRequest;

use App\Http\Controllers\Controller;
use App\Exceptions\GeneralException;
use DataTables;

class DepartmentController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            return DataTables::eloquent(Department::query())
            ->addIndexColumn()
            ->addColumn('action', function ($data) {
                $button = $data->action_buttons;
                return $button;
            })
            ->editColumn('id', '{{$id}}')
            ->rawColumns(['action'])
            ->make(true);
        }

        return view('humanresource::department.index');
    }
    
    public function create()
    {
    	if (auth()->user()->cannot('create department')) {
            throw new GeneralException(trans('exceptions.cannot_create'));
        }

        return view('humanresource::department.create');
    }

    public function store(StoreDepartmentRequest $request)
    {
        $department = new Department;
        $department->name = $request->name;
        $department->description = $request->description;
        $department->save();

        return redirect()->route('admin.humanresource.department.index')->withFlashSuccess('Berhasil menambahkan data.');
    }

    public function edit(Department $department)
    {
        if (auth()->user()->cannot('update department')) {
            throw new GeneralException(trans('exceptions.cannot_edit'));
        }
        
        return view('humanresource::department.edit')->withDepartment($department);
    }

    public function update(Request $request, $id)
    {
        if (auth()->user()->cannot('update department')) {
            throw new GeneralException(trans('exceptions.cannot_delete'));
        }

        $Department = Department::findOrFail($id);

        if($Department->name <> $request->name){
            $check = Department::where('name', '=', $request->name)->first();
            if ($check) {
                throw new GeneralException('Nama Departemen '.$request->name.' sudah ada!');
            }
        }

        $Department->name = $request->name;
        $Department->description = $request->description;
        $Department->save();

        return redirect()->route('admin.humanresource.department.index')->withFlashSuccess('Berhasil mengubah data.');
    }

    public function destroy($id)
    {
        if (auth()->user()->cannot('delete department')) {
            throw new GeneralException(trans('exceptions.cannot_delete'));
        }

        $Department = Department::findOrFail($id);

        $Department->destroy($Department->id);

        return redirect()->route('admin.humanresource.department.index')->withFlashSuccess('Data berhasil di hapus.');
    }
}
