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

    public function destroy($id) {

    }

    public function getData(Request $request) {
        $model = Modul::orderBy('id_modul');
        return DataTables::eloquent($model)
            ->addIndexColumn()
//            ->addColumn('button',function ($data) {
//                $button = '-';
//                if($data->status == "cabang") {
//                    $button = '<button class="btn btn-sm btn-warning btnUpdate" value="'.$data->id_klinik.'" data-toggle="tooltip" data-placement="top" title="Lihat"><i class="si si-book-open"></i> Ubah Menjadi Pusat</button>';
//                }
//                return $button;
//            })
            ->addColumn('action', function ($data) {
                $button = '<div class="d-flex justify-content-end">
                &nbsp;
                <form action="'. route('setting-modul.destroy',$data->id_modul) .'" method="POST">
                <input type="hidden" name="_method" value="DELETE">
                <input type="hidden" name="_token" value="'.csrf_token().'">
                 <button type="button" class="btn btn-sm btn-danger btnHapus" data-toggle="tooltip" data-placement="top" title="Lihat"><i class="si si-trash"></i></button></form>
                </div>
                ';
                return $button;
            })
//            ->rawColumns(['button','action'])
            ->make(true);
    }

    public function store(Request $request) {
        $create = \App\Modul::create($request->all());
        return redirect()->back()->with('success','Data Berhasil Di Masukkan');
    }

}

