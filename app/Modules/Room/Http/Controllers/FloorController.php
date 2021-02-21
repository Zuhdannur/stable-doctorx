<?php

namespace App\Modules\Room\Http\Controllers;

use Illuminate\Http\Request;
use App\Modules\Room\Models\Floor;
use App\Modules\Room\Repositories\FloorRepository;

use App\Modules\Room\Http\Requests\Floor\StoreFloorRequest;
use App\Modules\Room\Http\Requests\Floor\ManageFloorRequest;
use App\Modules\Room\Http\Requests\Floor\UpdateFloorRequest;

use App\Http\Controllers\Controller;
use App\Exceptions\GeneralException;
use Yajra\Datatables\Datatables;

class FloorController extends Controller
{
    protected $floorRepository;

    public function __construct(FloorRepository $floorRepository)
    {
        $this->floorRepository = $floorRepository;
    }

    public function index(ManageFloorRequest $request, Datatables $datatables)
    {
    	$columns = ['id', 'name', 'created_at', 'updated_at'];

        if ($datatables->getRequest()->ajax()) {
            return $datatables->of($this->floorRepository->where('id_klinik',Auth()->user()->klinik->id_klinik)->select($columns)->get())
            ->addIndexColumn()
            ->addColumn('action', function ($data) {
                $button = $data->action_buttons;
                return $button;
            })
            ->editColumn('id', '{{$id}}')
            ->rawColumns(['action'])
            ->make(true);
        }

        return view('room::floor.index');
    }

    public function create()
    {
    	if (auth()->user()->cannot('create floor')) {
            throw new GeneralException(trans('exceptions.cannot_create'));
        }

        return view('room::floor.create');
    }

    public function store(StoreFloorRequest $request)
    {
        $this->floorRepository->create($request->only('name'));

        return redirect()->route('admin.room.floor.index')->withFlashSuccess(__('room::alerts.floor.created'));
    }

    public function edit(Floor $floor)
    {
        if (auth()->user()->cannot('update floor')) {
            throw new GeneralException(trans('exceptions.cannot_edit'));
        }

        return view('room::floor.edit')->with('floor', $floor);
    }

    public function update(UpdateFloorRequest $request, Floor $floor)
    {
        $this->floorRepository->update($floor, $request->only('name'));

        return redirect()->route('admin.room.floor.index')->withFlashSuccess(__('room::alerts.floor.updated'));
    }

    public function destroy(ManageFloorRequest $request, Floor $floor)
    {
        if (auth()->user()->cannot('delete floor')) {
            throw new GeneralException(trans('exceptions.cannot_delete'));
        }

        $this->floorRepository->deleteById($floor->id);

        return redirect()->route('admin.room.floor.index')->withFlashSuccess(__('room::alerts.floor.deleted'));
    }
}
