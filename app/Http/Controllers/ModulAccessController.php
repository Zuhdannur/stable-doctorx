<?php

namespace App\Http\Controllers;

use App\Models\Auth\User;
use Illuminate\Http\Request;

class ModulAccessController extends Controller
{
    public function edit($id) {
        $data['user'] = User::find($id);
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

    public function index() {

    }

    public function update(Request $request , $id) {
        if($request->has('modul')) {

            $query = \App\ModelAccess::where('id_user',$id)->get();
            if(count($query) > 0 ) {
                $delete = \App\ModelAccess::where('id_user',$id)->delete();
            }

            foreach ($request->modul as $item) {
                $input['id_modul'] = $item;
                $input['id_user'] = $id;
                \App\ModelAccess::create($input);
            }
        }
        return redirect()->back();
    }
}
