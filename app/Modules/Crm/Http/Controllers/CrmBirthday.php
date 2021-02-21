<?php

namespace App\Modules\Crm\Http\Controllers;

use DB;

use DataTables;
use Twilio\Rest\Client;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Modules\Patient\Models\Patient;
use App\Modules\Crm\Models\CrmSettingWa;
use App\Modules\Crm\Models\CrmMembership;
use App\Modules\Crm\Models\CrmMsMembership;
use App\Modules\Attribute\Models\LogActivity;

class CrmBirthday extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = CrmMembership::with(['patient', 'ms_membership'])
            ->whereHas('patient', function($q) use($request) {
                return $q->whereRaw('DAYOFYEAR("'.\Carbon\Carbon::createFromFormat(setting()->get('date_format'), $request->startDate)->format('Y-m-d').'") <= DAYOFYEAR(dob) AND DAYOFYEAR("'.\Carbon\Carbon::createFromFormat(setting()->get('date_format'), $request->endDate)->format('Y-m-d').'") >=  dayofyear(dob)')->orderByRaw('DAYOFYEAR(dob)');

            });

            return DataTables::eloquent($data)
            ->addIndexColumn()
            ->editColumn('patient.dob', function ($data) {
                $dob = $data->patient->dob_string;
                return $dob;
            })
            ->editColumn('patient.last_wa', function($data){
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
            ->addColumn('action', function ($data) {
                $button = $data->patient->send_wa_buttons;
                $button .= $data->patient->edit_wa_buttons;
                return $button;
            })
            ->editColumn('id', '{{$id}}')
            ->rawColumns(['action', 'patient.last_wa'])
            ->make(true);
        }

        return view('crm::birthday.index');
    }

    public function sendWa(Request $request)
    {
        // prepare data
        $patient = Patient::findOrfail($request->patient);
        $configWa = CrmSettingWa::where('is_active', 1)->first();

        // return error if wa_number empty !
        if(empty($patient->wa_number) || $patient->wa_number == null){
            return response()->json(array('status' => FALSE, 'message' => "No Whatsapp Pasien Tidak Tersedia !"));
        }

        // vendor Woo Wandroid
        if($configWa->id == 1){
            $setting = json_decode($configWa->settings);
            $msg = str_replace("[patient_name]", $patient->patient_name, $configWa->wa_message);
            $msg = str_replace("[age]", $patient->age, $msg);
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

            $msg = str_replace("[patient_name]", $patient->patient_name, $configWa->wa_message);
            $msg = str_replace("[age]", $patient->age, $msg);

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
    
    public function editNoWA(Patient $patient)
    {
        return view('crm::birthday.edit-wa')
        ->withPatient($patient);
    }

    public function storeWa(Request $request)
    {
        $status = false;
        $message = trans('patient::exceptions.patient.update_error');

        $patient = Patient::findOrFail($request->id);

        $patient->wa_number = $request->wa_number;

        DB::beginTransaction();
        try {
            
            $patient->save();
            $log = new LogActivity();
            $log->module_id = config('my-modules.crm');
            $log->action = "Save WA Number Membership";
            $log->desc = "PID : $patient->patient_unique_id, Name :". $patient->patient_name;

            $log->save();
            
            DB::commit();
            $status = true;
            $message = __('patient::alerts.patient.updated');

        } catch (\Exception $e) {
            DB::rollback();

            if(env("APP_DEBUG") == true){
                dd($e);
            }
        }
       
        return response()->json(array('status' => $status, 'message' => $message));

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
