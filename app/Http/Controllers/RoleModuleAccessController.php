<?php

namespace App\Http\Controllers;

use App\Models\Auth\Role;
use App\Models\Auth\User;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use function foo\func;

class RoleModuleAccessController extends Controller
{
    public function index() {
        return view('pages.role.role_view');
    }

    public function getData(Request $request) {
        $model = Role::orderBy('id');
        return DataTables::eloquent($model)
            ->addIndexColumn()
            ->addColumn('modul',function ($data) {
                return count($data->modul);
            })
            ->addColumn('action', function ($data) {
                $button = '<div class="d-flex justify-content-end">
                <a class="btn btn-sm btn-warning" data-toggle="tooltip" data-placement="top" title="Ubah" href="'. route('role-module.edit', $data->id) .'"><i class="si si-pencil"></i></a>
                &nbsp;
                </div>
                ';
                return $button;
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function edit($id) {
        $data['user'] = Role::find($id);
        $data['modul'] = \App\ModelAccess::where('id_user',$id)->get();
        if(count($data['modul']) == 0){
            $data['modul'] = array();
        } else {
            $row = array();
            foreach ($data['modul'] as $index => $item) {
                $row[$index] = $item->id_modul;
            }
            $data['modul']  = $row;
        }
        return view('pages.modules.index')->with($data);
    }
}
