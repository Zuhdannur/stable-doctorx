<?php

namespace App\Modules\Room\Http\Controllers;

use App\Helpers\Auth\Auth;
use Illuminate\Http\Request;

use App\Modules\Room\Models\Room;
use App\Modules\Room\Models\RoomGroup;
use App\Modules\Room\Repositories\RoomRepository;

use App\Modules\Room\Http\Requests\Room\StoreRoomRequest;
use App\Modules\Room\Http\Requests\Room\ManageRoomRequest;
use App\Modules\Room\Http\Requests\Room\UpdateRoomRequest;

use App\Http\Controllers\Controller;
use App\Exceptions\GeneralException;
use Yajra\Datatables\Datatables;

class RoomController extends Controller
{
    protected $roomRepository;

    public function __construct(RoomRepository $roomRepository)
    {
        $this->roomRepository = $roomRepository;
    }

    public function index(ManageRoomRequest $request, Datatables $datatables)
    {
        if ($datatables->getRequest()->ajax()) {
            return $datatables->of($this->roomRepository->where('id_klinik',Auth()->user()->klinik->id_klinik)->get())
            ->addIndexColumn()
            ->addColumn('group', function ($data) {
                $group = $data->group;
                return $group->name;
            })
            ->addColumn('floor', function ($data) {
                $floor = $data->group;
                return $floor->floor->name;
            })
            ->addColumn('action', function ($data) {
                $button = $data->action_buttons;
                return $button;
            })
            ->editColumn('id', '{{$id}}')
            ->rawColumns(['action'])
            ->make(true);
        }

        return view('room::room.index');
    }

    public function create()
    {
    	if (auth()->user()->cannot('create room')) {
            throw new GeneralException(trans('exceptions.cannot_create'));
        }

        $RoomGroup = RoomGroup::where('id_klinik',Auth()->user()->klinik->id_klinik)->get();

        return view('room::room.create')->withRoomgroup($RoomGroup);
    }

    public function store(StoreRoomRequest $request)
    {
        $this->roomRepository->create($request->only('name', 'room_group_id'));

        return redirect()->route('admin.room.index')->withFlashSuccess(__('room::alerts.room.created'));
    }

    public function edit(Room $room)
    {
        if (auth()->user()->cannot('update room')) {
            throw new GeneralException(trans('exceptions.cannot_edit'));
        }

		$RoomGroup = RoomGroup::all();

        return view('room::room.edit')->withRoom($room)->withRoomgroup($RoomGroup);
    }

    public function update(UpdateRoomRequest $request, Room $room)
    {
        $this->roomRepository->update($room, $request->only('name', 'room_group_id'));

        return redirect()->route('admin.room.index')->withFlashSuccess(__('room::alerts.room.updated'));
    }

    public function destroy(ManageRoomRequest $request, Room $room)
    {
        if (auth()->user()->cannot('delete room group')) {
            throw new GeneralException(trans('exceptions.cannot_delete'));
        }

        $this->roomRepository->deleteById($room->id);

        return redirect()->route('admin.room.index')->withFlashSuccess(__('room::alerts.room.deleted'));
    }

    public function getByGroupId($id)
    {
        $data = $this->roomRepository->getByGroupId($id);

        $status = false;
        $message = 'Room/Area Not Found!';

        $newdata = array();
        if(count($data)){
            $status = true;
            $message = 'OK';

            //filter
            foreach ($data as $key => $value) {
                $newdata[$key]['room_id'] = $value->id;
                $newdata[$key]['room_name'] = $value->name;
                $newdata[$key]['group_name'] = $value->group->name;
            }
        }

        return response()->json(array('status' => $status, 'message' => $message, 'data' => $newdata));
    }
}
