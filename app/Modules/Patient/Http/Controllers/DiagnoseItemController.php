<?php

namespace App\Modules\Patient\Http\Controllers;

use Illuminate\Http\Request;
use App\Modules\Patient\Models\DiagnoseItem;

use App\Http\Controllers\Controller;
use App\Exceptions\GeneralException;
use Yajra\Datatables\Datatables;

use App\Modules\Patient\Http\Requests\DiagnoseItem\ManageDiagnoseItemRequest;
use App\Modules\Patient\Http\Requests\DiagnoseItem\StoreDiagnoseItemRequest;
use App\Modules\Patient\Http\Requests\DiagnoseItem\UpdateDiagnoseItemRequest;

class DiagnoseItemController extends Controller
{
    public function index(Datatables $datatables)
    {
    	$columns = ['id', 'code', 'created_at', 'updated_at'];

        if ($datatables->getRequest()->ajax()) {
            return $datatables->of(DiagnoseItem::get())
            ->addIndexColumn()
            ->addColumn('action', function ($data) {
                $button = $data->action_buttons;
                return $button;
            })
            ->editColumn('id', '{{$id}}')
            ->rawColumns(['action'])
            ->make(true);
        }

        return view('patient::diagnoseitem.index');
    }

    public function create()
    {
    	if (auth()->user()->cannot('create diagnoseitem')) {
            throw new GeneralException(trans('exceptions.cannot_create'));
        }

        return view('patient::diagnoseitem.create');
    }

    public function store(StoreDiagnoseItemRequest $request)
    {
        $DiagnoseItem = new DiagnoseItem;

        $DiagnoseItem->code = $request->code;
        $DiagnoseItem->name = $request->name;
        $DiagnoseItem->description = $request->description;

        if ($DiagnoseItem->save()) {
            return redirect()->route('admin.masterdata.diagnoseitem.index')->withFlashSuccess('Berhasil menambahkan data.');
        }
        

        return redirect()->route('admin.masterdata.diagnoseitem.index')->withFlashSuccess('Gagal menambahkan data.');
    }

    public function edit($id)
    {
        if (auth()->user()->cannot('update diagnoseitem')) {
            throw new GeneralException(trans('exceptions.cannot_edit'));
        }

        $diagnoseItem = DiagnoseItem::findOrFail($id);

        return view('patient::diagnoseitem.edit')->with('diagnoseitem', $diagnoseItem);
    }

    public function update(UpdateDiagnoseItemRequest $request, $id)
    {
        $DiagnoseItem = DiagnoseItem::findOrFail($id);

        if($DiagnoseItem->code <> $request->code){
            $check = DiagnoseItem::where('code', '=', $request->code)->first();
            if ($check) {
                throw new GeneralException('Kode '.$request->code.' sudah ada!');
            }
        }

        if($DiagnoseItem->name <> $request->name){
            $check = DiagnoseItem::where('name', '=', $request->name)->first();
            if ($check) {
                throw new GeneralException('Nama '.$request->name.' sudah ada!');
            }
        }

        $DiagnoseItem->code = $request->code;
        $DiagnoseItem->name = $request->name;
        $DiagnoseItem->description = $request->description;

        if ($DiagnoseItem->save()) {
            return redirect()->route('admin.masterdata.diagnoseitem.index')->withFlashSuccess('Berhasil mengubah data.');
        }

        return redirect()->route('admin.masterdata.diagnoseitem.index')->withFlashSuccess('Gagagl mengubah data!');
    }

    public function destroy($id)
    {
        if (auth()->user()->cannot('delete diagnoseitem')) {
            throw new GeneralException(trans('exceptions.cannot_delete'));
        }

        $diagnoseItem = DiagnoseItem::findOrFail($id);

        $diagnoseItem->destroy($diagnoseItem->id);

        return redirect()->route('admin.masterdata.diagnoseitem.index')->withFlashSuccess('Data berhasil di hapus.');
    }
}
