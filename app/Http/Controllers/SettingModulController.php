<?php

namespace App\Http\Controllers;

use App\Modul;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class SettingModulController extends Controller
{
    public function index() {
        return view('pages.setting-modul.setting_modul_view');
    }

    public function create() {
        return view('pages.setting-modul.setting_modul_form');
    }

    public function edit($id) {
        $data['data'] = \App\Modul::find($id);
        $data['isEdit'] = true;
        return view('pages.setting-modul.setting_modul_form')->with($data);
    }

    public function update(Request $request,$id) {
        $update = \App\Modul::find($id)->update($request->all());
        if($update){
            return redirect()->back()->with('flash_success','Update Modul Berhasil');
        } else {
            return redirect()->back()->with('flash_error','Update Modul Gagal');

        }
    }

    public function destroy($id) {
        $delete = \App\Modul::find($id)->delete();
        return redirect()->back()->with('flash_success','Menu Berhasil dihapus');
    }

    public function getData(Request $request) {
        $model = Modul::orderBy('id_modul');
        return DataTables::eloquent($model)
            ->addIndexColumn()
            ->addColumn('action', function ($data) {
                $button = '<div class="d-flex justify-content-end">
                <a class="btn btn-sm btn-warning" data-toggle="tooltip" data-placement="top" title="Lihat" href="'. route('setting-modul.edit', $data->id_modul) .'"><i class="si si-doc"></i></a>
                &nbsp;
                <form action="'. route('setting-modul.destroy',$data->id_modul) .'" method="POST">
                <input type="hidden" name="_method" value="DELETE">
                <input type="hidden" name="_token" value="'.csrf_token().'">
                 <button type="button" class="btn btn-sm btn-danger btnHapus" data-toggle="tooltip" data-placement="top" title="Lihat"><i class="si si-trash"></i></button></form>
                </div>
                ';
                return $button;
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function store(Request $request) {
        $create = \App\Modul::create($request->all());
        return redirect()->back()->with('success','Data Berhasil Di Masukkan');
    }

}

