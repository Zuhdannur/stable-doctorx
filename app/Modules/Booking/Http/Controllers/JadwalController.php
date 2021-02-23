<?php

namespace App\Modules\Booking\Http\Controllers;

use DataTables;

use Twilio\Rest\Client;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Modules\Patient\Models\Patient;
use App\Modules\Crm\Models\CrmSettingWa;
use App\Modules\Patient\Models\Treatment;
use App\Modules\Patient\Models\Appointment;

class JadwalController extends Controller
{

    public function appointment(Request $request)
    {
        // date for view
        $date_1 = \Carbon\Carbon::now()->format('d/m/Y');
        $date_2 = \Carbon\Carbon::now()->addDays(7)->format('d/m/Y');     

        if($request->isMethod('POST')){
            $date_1 = $request->date_1;
            $date_2 = $request->date_2;
        }

        if($request->ajax()){
            $start_date = \Carbon\Carbon::createFromFormat('d/m/Y', $request->date_1); 
            $end_date = \Carbon\Carbon::createFromFormat('d/m/Y', $request->date_2);   

            $model = Appointment::with(['patient', 'room', 'staff', 'status'])
                    ->whereBetween('date', [$start_date, $end_date])
                    // ->with('user')
                    ->orderBy('date','DESC');

            return DataTables::eloquent($model)
            ->addIndexColumn()
            ->editColumn('patient', function($data){
                return '';
            })
            ->editColumn('room', function($data){
                return $data->room->name;
            })
            ->editColumn('staff', function($data){
                return $data->staff->user->full_name;
            })
            ->editColumn('date', function($data){
                return \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $data->date)->format('d F Y');
            })
            ->addColumn('time', function($data){
                return \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $data->date)->format('H:i:s');
            })
            ->addColumn('pid', function($data){
                return $data->patient->patient_unique_id;
            })
            ->addColumn('patient_name', function($data){
                return $data->patient->patient_name;
            })
            ->addColumn('status', function($data){
                $html = '<span class="badge badge-'.$data->status->class_style.'" >';
                $html .= $data->status->name;
                $html .= '</span>';

                return $html;
            })
            ->addColumn('reminder', function($data){
                $lastWa = '';
                if($data->patient->last_wa != null){
                    $lastWa = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $data->patient->last_wa)->format('Y');
                };
                $now = \Carbon\Carbon::now()->format('Y');

                if($now == $lastWa){
                    $badge = '<span class="badge badge-success">Sudah dikirim</span>';
                }else{
                    $badge = '<span class="badge badge-danger">Belum dikirim</span>';   
                }

                return $badge;
            })
            ->addColumn('action', function($data){
                if($data->status->id == 3){
                    return '';
                }
                $action = '<button class="btn btn-sm btn-success" onClick="sendWa('."'".$data->patient->id."'".','."'".$data->date->format("Y-m-d H:i:s")."'".')" data-toggle="tooltip" data-placement="top" title="Kirim Reminder Wa"><i class="fa fa-whatsapp"></i></button> &nbsp;';
                $action .= '<a href="'.route('admin.crm.birthday.editNoWA', $data->patient).'" class="btn btn-sm btn-primary" data-toggle="tooltip" data-placement="top" title="Edit/Tambah"><i class="fa fa-edit"></i></a> &nbsp;';
                
                return $action;
            })
            ->rawColumns(['status','action', 'reminder'])
            ->make(true);
        }

        return view('booking::jadwal.appointment')
        ->withDate(array('date_1' => $date_1, 'date_2' => $date_2));
    }

    public function treatment(Request $request)
    {
        // date for view
        $date_1 = \Carbon\Carbon::now()->format('d/m/Y');
        $date_2 = \Carbon\Carbon::now()->addDays(7)->format('d/m/Y');

        if($request->isMethod('POST')){
            $date_1 = $request->date_1;
            $date_2 = $request->date_2;
        }

        if($request->ajax()){
            $start_date = \Carbon\Carbon::createFromFormat('d/m/Y', $request->date_1); 
            $end_date = \Carbon\Carbon::createFromFormat('d/m/Y', $request->date_2);   

            $model = Treatment::with(['patient', 'room', 'staff', 'status'])
                    ->whereBetween('date', [$start_date, $end_date])
                    // ->with('user')
                    ->orderBy('date','DESC');

            return DataTables::eloquent($model)
            ->addIndexColumn()
            ->editColumn('patient', function($data){
                return '';
            })
            ->editColumn('room', function($data){
                return (isset($data->room->name) ? $data->room->name : '');
            })
            ->editColumn('staff', function($data){
                return $data->staff->user->full_name;
            })
            ->editColumn('date', function($data){
                return \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $data->date)->format('d F Y');
            })
            ->addColumn('time', function($data){
                return \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $data->date)->format('H:i:s');
            })
            ->addColumn('pid', function($data){
                return $data->patient->patient_unique_id;
            })
            ->addColumn('patient_name', function($data){
                return $data->patient->patient_name;
            })
            ->addColumn('status', function($data){
                $html = '<span class="badge badge-'.$data->status->class_style.'" >';
                $html .= $data->status->name;
                $html .= '</span>';

                return $html;
            })
            ->addColumn('reminder', function($data){
                $lastWa = '';
                if($data->patient->last_wa != null){
                    $lastWa = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $data->patient->last_wa)->format('Y');
                };
                $now = \Carbon\Carbon::now()->format('Y');

                if($now == $lastWa){
                    $badge = '<span class="badge badge-success">Sudah dikirim</span>';
                }else{
                    $badge = '<span class="badge badge-danger">Belum dikirim</span>';   
                }

                return $badge;
            })
            ->addColumn('action', function($data){
                if($data->status->id == 3){
                    return '';
                }
                $action = '<button class="btn btn-sm btn-success" onClick="sendWa('."'".$data->patient->id."'".','."'".$data->date->format("Y-m-d H:i:s")."'".')" data-toggle="tooltip" data-placement="top" title="Kirim Reminder Wa"><i class="fa fa-whatsapp"></i></button> &nbsp;';
                $action .= '<a href="'.route('admin.crm.birthday.editNoWA', $data->patient).'" class="btn btn-sm btn-primary" data-toggle="tooltip" data-placement="top" title="Edit/Tambah"><i class="fa fa-edit"></i></a> &nbsp;';
                
                return $action;
            })
            ->rawColumns(['status','action', 'reminder'])
            ->make(true);
        }

        return view('booking::jadwal.treatment')
        ->withDate(array('date_1' => $date_1, 'date_2' => $date_2));
    }

    // public function nextSchedule()
    // {
    //     dd('test');
    // }

    public function sendWa(Request $request)
    {
        // prepare data
        $patient = Patient::findOrfail($request->patient);
        $configWa = CrmSettingWa::where('is_active', 1)->first();
        $date = $request->date;

        try{
            $date = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s',$date)->format('d F Y, H:i:s');
        }catch(\Exception $e){
            if(env("APP_DEBUG") == true){
                dd($e);
            }

            return response()->json(array('status' => FALSE, 'message' => "Ada kesalahan dalam parameter, silahkan refresh halaman !"));
        }

        // return error if wa_number empty !
        if(empty($patient->wa_number) || $patient->wa_number == null){
            return response()->json(array('status' => FALSE, 'message' => "No Whatsapp Pasien Tidak Tersedia !"));
        }

        // vendor Woo Wandroid
        if($configWa->id == 1){
            $setting = json_decode($configWa->settings);
            $msg = str_replace("[patient_name]", $patient->patient_name, $configWa->wa_message_reminder);
            $msg = str_replace("[app_name]", app_name(), $msg);
            $msg = str_replace("[date]", $date, $msg);

            $data = [
                'cs_id' => $setting->cs_id,
                'msg'   => $msg,
                'wa_number'   => $patient->wa_number,
                'url'   => $configWa->api_url,
            ];
            
            $patient->lastWa();
            return response()->json($this->_sendWooWa($data));
        
        }

        //vendor twillio 
        else if ($configWa->id == 2){
            $setting = json_decode($configWa->settings);

            $msg = str_replace("[patient_name]", $patient->patient_name, $configWa->wa_message_reminder);
            $msg = str_replace("[app_name]", app_name(), $msg);
            $msg = str_replace("[date]", $date, $msg);

            $check_wa = stristr($patient->wa_number, '+62');
            $wa_number = $patient->wa_number;

            if($check_wa == false){
                $wa_number = '+62'.ltrim($wa_number, 0);
            }

            $data = [
                'msg'   => $msg,
                'wa_number'   => $wa_number,
                'sid'   => $setting->sid,
                'token'   => $setting->token,
                'sender'   => $setting->senders,
            ];
            $patient->lastWa();
            return response()->json($this->_sendTwilio($data));
        }
    }

     // this method to send whatsapp message and use vendor twilio
     private function _sendTwilio(array $data){

        // this is only for development
        // $sid    = "AC94e4f2a113a215433ca4b839cb9dc16e";
        // $token  = "cc6b91a2db8c76450947666231ad5871";
        // $number_from = '+14155238886';
        
        $twilio = new Client($data['sid'], $data['token']);

        $resp = array(
            'status' => TRUE,
            'message' => 'PESAN BERHASIL DIKIRIM'
        );

        try {
            $msg = $twilio->messages
            ->create("whatsapp:".$data['wa_number'], // to
            [
                "from" => "whatsapp:".$data['sender'],
                "body" => $data['msg'],
                ]
            );
            
        } catch (\Exception $e) {
            $resp['status'] = FALSE;
            $resp['message'] = $e->getMessage();
        }

        return $resp;
     
    }

    // this method to send whatsapp message and use vendor Woo Wandroid
    public function _sendWooWa(array $data)
    {
        //this is only for development
        // $cs_id = '61d6c8c9-36ec-4709-800b-c3766dbd0fa6';
        
        // param for send
        $param = array(
            'app_id' => '429d3472-da0f-4b2b-a63e-4644050caf8f', //app id don't change
            'include_player_ids' => [$data['cs_id']], //you can take Player id from Woowandroid App CS ID menu.
            'data' => array(
                "type"      => 'Reminder', //opsional Reminder/After Checkout/Pending Payment/dll editable
                "message"   => $data['msg'],
                "no_wa"     => $data['wa_number']
            ),
            'contents'  => array(
                "en"    => app_name()
            ),
            "headings"  =>  array(
                "en"    => app_name()
            )
        );
        $data_json = json_encode($param);

        // curl
        $ch = curl_init();
        // curl_setopt($ch, CURLOPT_URL, 'https://onesignal.com/api/v1/notifications');
        curl_setopt($ch, CURLOPT_URL, $data['url']);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data_json);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_HEADER, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json; charset=utf-8',
            'Authorization: Basic NjY0NzE3MTYtMzc3ZC00YmY5LWJhNzQtOGRiMWM1ZTNhNzBh')); //os_auth don't change
        $response = curl_exec($ch);
        $status = curl_getinfo($ch,  CURLINFO_RESPONSE_CODE );
        curl_close($ch);

        $resp = array(
            'status' => TRUE,
            'message' => "PESAN BERHASIL DIKIRIM"
        );

        if($status == 400){
            $ressp['status'] = FALSE;
            $ressp['message'] = substr($response, strpos($response, "errors") + 10, -3);
        }

        return $resp;

    }
    
}
