<?php

namespace App\Modules\Humanresource\Http\Controllers;

use Illuminate\Http\Request;
use App\Modules\Humanresource\Models\Designation;
use App\Modules\Humanresource\Http\Requests\Designation\StoreDesignationRequest;

use App\Http\Controllers\Controller;
use App\Exceptions\GeneralException;
use DataTables;

class DesignationController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            return DataTables::eloquent(Designation::query())
            ->addIndexColumn()
            ->addColumn('action', function ($data) {
                $button = $data->action_buttons;
                return $button;
            })
            ->editColumn('id', '{{$id}}')
            ->rawColumns(['action'])
            ->make(true);
        }

        return view('humanresource::designation.index');
    }
    
    public function create()
    {
    	if (auth()->user()->cannot('create designation')) {
            throw new GeneralException(trans('exceptions.cannot_create'));
        }

        return view('humanresource::designation.create');
    }

    public function store(StoreDesignationRequest $request)
    {
        $Designation = new Designation;
        $Designation->name = $request->name;
        $Designation->description = $request->description;
        $Designation->save();

        return redirect()->route('admin.humanresource.designation.index')->withFlashSuccess('Berhasil menambahkan data.');
    }

    public function edit(Designation $designation)
    {
        if (auth()->user()->cannot('update designation')) {
            throw new GeneralException(trans('exceptions.cannot_edit'));
        }
        
        return view('humanresource::designation.edit')->withDesignation($designation);
    }

    public function update(Request $request, $id)
    {
        if (auth()->user()->cannot('update designation')) {
            throw new GeneralException(trans('exceptions.cannot_delete'));
        }

        $Designation = Designation::findOrFail($id);

        if($Designation->name <> $request->name){
            $check = Designation::where('name', '=', $request->name)->first();
            if ($check) {
                throw new GeneralException('Nama Departemen '.$request->name.' sudah ada!');
            }
        }

        $Designation->name = $request->name;
        $Designation->description = $request->description;
        $Designation->save();

        return redirect()->route('admin.humanresource.designation.index')->withFlashSuccess('Berhasil mengubah data.');
    }

    public function destroy($id)
    {
        if (auth()->user()->cannot('delete designation')) {
            throw new GeneralException(trans('exceptions.cannot_delete'));
        }

        $Designation = Designation::findOrFail($id);

        $Designation->destroy($Designation->id);

        return redirect()->route('admin.humanresource.designation.index')->withFlashSuccess('Data berhasil di hapus.');
    }
}
