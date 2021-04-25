<?php

namespace App\Http\Controllers;

use App\Grade;
use App\Klinik;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class GradeController extends Controller
{
    public function index() {
        return view('pages.grade.grade_view');
    }

    public function create() {
        return view('pages.grade.grade_form');
    }

    public function store(Request $request) {
        $create = \App\Grade::create($request->all());
        if($create) {
            return redirect()->back()->with('flash_success','Grade Berhasil Dibuat');
        } else {
            return redirect()->back()->with('flash_error','Grade Gagal Dibuat');
        }
    }

    public function edit($id) {
        $data['isEdit'] = true;
        $data['data'] = \App\Grade::find($id);
        return view('pages.grade.grade_form')->with($data);
    }

    public function getData(Request $request) {
        $model = Grade::orderBy('id_grade');
        return DataTables::eloquent($model)
            ->addIndexColumn()
            ->addColumn('action', function ($data) {
                $button = '<div class="d-flex justify-content-end">
                <a href="'.route('grade.edit',$data->id_grade).'" class="btn btn-sm btn-info" data-toggle="tooltip" data-placement="top" title="Lihat"><i class="si si-pencil"></i></a>
                &nbsp;
                <form action="'. route('grade.destroy',$data->id_grade) .'" method="POST">
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

    public function update(Request $request,$id) {
        $update = \App\Grade::find($id)->update([
            "nama_grade" => $request->nama_grade,
            "keterangan" => $request->keterangan
        ]);
        return redirect()->back()->with('flash_success','Grade Berhasil Di Perbaharui');
    }

    public function destroy($id) {
        $delete = \App\Grade::find($id)->delete();
        return redirect()->back()->with('flash_success','Grade Berhasil Di Hapus');
    }
}
