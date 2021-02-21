<?php

namespace App\Modules\Room\Http\Controllers;

use Illuminate\Http\Request;
use App\Modules\Room\Models\RoomGroup;
use App\Modules\Room\Models\Floor;
use App\Modules\Room\Repositories\RoomGroupRepository;

use App\Modules\Room\Http\Requests\RoomGroup\StoreRoomGroupRequest;
use App\Modules\Room\Http\Requests\RoomGroup\ManageRoomGroupRequest;
use App\Modules\Room\Http\Requests\RoomGroup\UpdateRoomGroupRequest;

use App\Http\Controllers\Controller;
use App\Exceptions\GeneralException;
use Yajra\Datatables\Datatables;

class GroupController extends Controller
{
    protected $roomGroupRepository;

    public function __construct(RoomGroupRepository $roomGroupRepository)
    {
        $this->roomGroupRepository = $roomGroupRepository;
    }

    public function index(ManageRoomGroupRequest $request, Datatables $datatables)
    {
        if ($datatables->getRequest()->ajax()) {
            return $datatables->of($this->roomGroupRepository->where('id_klinik',Auth()->user()->klinik->id_klinik)->get())
            ->addIndexColumn()
            ->addColumn('floor', function ($data) {
                $floor = $data->floor;
                return $floor->name;
            })
            ->addColumn('action', function ($data) {
                $button = $data->action_buttons;
                return $button;
            })
            ->editColumn('id', '{{$id}}')
            ->rawColumns(['action'])
            ->make(true);
        }

        return view('room::group.index');
    }

    public function create()
    {
    	if (auth()->user()->cannot('create room group')) {
            throw new GeneralException(trans('exceptions.cannot_create'));
        }

        $floor = Floor::where('id_klinik',Auth()->user()->klinik->id_klinik)->get();

        return view('room::group.create')->withFloor($floor);
    }

    public function store(StoreRoomGroupRequest $request)
    {
        $this->roomGroupRepository->create($request->only('name', 'description', 'floor_id', 'type'));

        return redirect()->route('admin.room.group.index')->withFlashSuccess(__('room::alerts.group.created'));
    }

    public function edit($id)
    {
        if (auth()->user()->cannot('update room group')) {
            throw new GeneralException(trans('exceptions.cannot_edit'));
        }
        $floor = Floor::all();
        $roomGroup = RoomGroup::findOrfail($id);

        return view('room::group.edit')->withFloor($floor)->withRoomgroup($roomGroup);
    }

    public function update(UpdateRoomGroupRequest $request, $id)
    {
    	$roomGroup = RoomGroup::findOrfail($id);
        $this->roomGroupRepository->update($roomGroup, $request->only('name', 'description', 'floor_id'));

        return redirect()->route('admin.room.group.index')->withFlashSuccess(__('room::alerts.group.updated'));
    }

    public function destroy(ManageRoomGroupRequest $request, $id)
    {
        if (auth()->user()->cannot('delete room group')) {
            throw new GeneralException(trans('exceptions.cannot_delete'));
        }

        $roomGroup = RoomGroup::findOrfail($id);

        $this->roomGroupRepository->deleteById($roomGroup->id);

        return redirect()->route('admin.room.group.index')->withFlashSuccess(__('room::alerts.group.deleted'));
    }
}
