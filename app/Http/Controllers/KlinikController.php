<?php

namespace App\Http\Controllers;

use App\Klinik;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class KlinikController extends Controller
{

    public function index() {
        $data['cabang'] = count(\App\Klinik::where('status','cabang')->get());
        $data['pusat'] = \App\Klinik::where('status','pusat')->first();
        return view('pages.klinik.klinik_view')->with($data);
    }

    public function create() {
        return view('pages.klinik.klinik_form');
    }

    public function getData(Request $request) {
        $model = Klinik::orderBy('id_klinik');
        return DataTables::eloquent($model)
            ->addIndexColumn()
            ->addColumn('button',function ($data) {
                $button = '-';
                if($data->status == "cabang") {
                    $button = '<button class="btn btn-sm btn-warning btnUpdate" value="'.$data->id_klinik.'" data-toggle="tooltip" data-placement="top" title="Lihat"><i class="si si-book-open"></i> Ubah Menjadi Pusat</button>';
                }
                return $button;
            })
            ->addColumn('action', function ($data) {
                $button = '<div class="d-flex justify-content-end">
                <a href="'.route('klinik.edit',$data->id_klinik).'" class="btn btn-sm btn-info" data-toggle="tooltip" data-placement="top" title="Lihat"><i class="si si-pencil"></i></a>
                &nbsp;
                <form action="'. route('klinik.destroy',$data->id_klinik) .'" method="POST">
                <input type="hidden" name="_method" value="DELETE">
                <input type="hidden" name="_token" value="'.csrf_token().'">
                 <button type="button" class="btn btn-sm btn-danger btnHapus" data-toggle="tooltip" data-placement="top" title="Lihat"><i class="si si-trash"></i></button></form>
                </div>
                ';
                return $button;
            })
            ->rawColumns(['button','action'])
            ->make(true);
    }

    public function store(Request $request) {
        $input = $request->all();
        $input['status'] = "cabang";
        $create = \App\Klinik::create($input);

        return redirect()->back()->with('flash_success','Klinik Berhasil Dibuat');
    }

    public function edit($id) {
        $data['data'] = \App\Klinik::find($id);
        $data['isEdit'] = true;
        return view('pages.klinik.klinik_form')->with($data);
    }

    public function simpan(Request $request) {
        $find = \App\Klinik::where('status',"pusat")->first();

        if(!empty($find)) {
            $find->update([
                "status" => "cabang"
            ]);
        }

        $update = \App\Klinik::find($request->id);

        $update->update([
           "status" => "pusat"
        ]);

        return response()->json(["message" => "success"]);
    }

    public function update(Request $request,$id) {
        $update = \App\Klinik::find($id)->update([
            "nama_klinik" => $request->nama_klinik,
            "alamat" => $request->alamat,
        ]);

        if($update) {
            return redirect()->back()->with('flash_success','Data Berhasil ditambahkan');
        } else {
            return redirect()->back()->with('flash_error','Data Berhasil ditambahkan');
        }
    }

    public function destroy($id) {
        $delete = \App\Klinik::find($id)->delete();
        return redirect()->back()->with('flash_success','Data Berhasil Dihapus');
    }

}
