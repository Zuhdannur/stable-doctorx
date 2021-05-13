<?php

namespace App\Http\Controllers;

use App\Klinik;
use App\Models\Auth\User;
use App\Modules\Billing\Models\Billing;
use App\Modules\Patient\Models\Patient;
use App\Modules\Product\Models\Product;
use App\Modules\Product\Models\Service;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class KlinikController extends Controller
{

    public function index()
    {
        $data['cabang'] = count(\App\Klinik::where('status', 'cabang')->get());
        $data['pusat'] = \App\Klinik::where('status', 'pusat')->first();
        return view('pages.klinik.klinik_view')->with($data);
    }

    public function create()
    {
        return view('pages.klinik.klinik_form');
    }

    public function getData(Request $request)
    {
        $model = Klinik::orderBy('id_klinik');
        return DataTables::eloquent($model)
            ->addIndexColumn()
            ->addColumn('button', function ($data) {
                $button = '-';
                if ($data->status == "cabang") {
                    $button = '<button class="btn btn-sm btn-warning btnUpdate" value="' . $data->id_klinik . '" data-toggle="tooltip" data-placement="top" title="Lihat"><i class="si si-book-open"></i> Ubah Menjadi Pusat</button>';
                }
                return $button;
            })
            ->addColumn('action', function ($data) {
                $button = '<div class="d-flex justify-content-end">
                <a href="' . route('klinik.show', $data->id_klinik) . '" class="btn btn-sm btn-success" data-toggle="tooltip" data-placement="top" title="Lihat"><i class="si si-docs"></i>&nbsp;Lihat Laporan</a>
                &nbsp;
                <a href="' . route('klinik.edit', $data->id_klinik) . '" class="btn btn-sm btn-info" data-toggle="tooltip" data-placement="top" title="Edit"><i class="si si-pencil"></i></a>
                &nbsp;
                <form action="' . route('klinik.destroy', $data->id_klinik) . '" method="POST">
                <input type="hidden" name="_method" value="DELETE">
                <input type="hidden" name="_token" value="' . csrf_token() . '">
                 <button type="button" class="btn btn-sm btn-danger btnHapus" data-toggle="tooltip" data-placement="top" title="Lihat"><i class="si si-trash"></i></button></form>
                </div>
                ';
                return $button;
            })
            ->rawColumns(['button', 'action'])
            ->make(true);
    }

    public function store(Request $request)
    {
        $input = $request->all();
        $input['status'] = "cabang";
        $create = \App\Klinik::create($input);

        return redirect()->back()->with('flash_success', 'Klinik Berhasil Dibuat');
    }

    public function edit($id)
    {
        $data['data'] = \App\Klinik::find($id);
        $data['isEdit'] = true;
        return view('pages.klinik.klinik_form')->with($data);
    }

    public function simpan(Request $request)
    {
        $find = \App\Klinik::where('status', "pusat")->first();

        if (!empty($find)) {
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

    public function update(Request $request, $id)
    {
        $update = \App\Klinik::find($id)->update([
            "nama_klinik" => $request->nama_klinik,
            "alamat" => $request->alamat,
        ]);

        if ($update) {
            return redirect()->back()->with('flash_success', 'Data Berhasil ditambahkan');
        } else {
            return redirect()->back()->with('flash_error', 'Data Berhasil ditambahkan');
        }
    }

    public function destroy($id)
    {
        $delete = \App\Klinik::find($id)->delete();
        return redirect()->back()->with('flash_success', 'Data Berhasil Dihapus');
    }

    public function show($id)
    {
        $data['user'] = User::where('id_klinik', $id)->get();

        $data['baru'] = Patient::where('id_klinik', $id)->where('old_patient', 'y')->count();
        $data['lama'] = Patient::where('id_klinik', $id)->where('old_patient', 'n')->count();

        $data['produk'] = Product::where('id_klinik',auth()->user()->id_klinik)->count();
        $data['service'] = Service::where('id_klinik',auth()->user()->id_klinik)->count();
        $data['id'] = $id;

        return view('pages.klinik.klinik_report')->with($data);
    }

    public function getReport(Request $request) {
        $input = $request->all();
        $data = new Collection;

        $accounting = Billing::where('id_klinik',$request->id_klinik)->where('status',1)->sum('total_ammount');

        $title = ["omset","Gross Profit"];
        $nominal = [$accounting,0];

        for($i = 0; $i < 2; $i++) {
            $data->push([
                "DT_RowIndex" => ($i + 1),
                "komponen" => $title[$i],
                "nominal" => $nominal[$i]
            ]);
        }

        return DataTables::of($data)
            ->make(true);
    }

}
