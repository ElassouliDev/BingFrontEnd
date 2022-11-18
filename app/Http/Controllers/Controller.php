<?php

namespace App\Http\Controllers;

use App\Models\AgentTapFile;
use App\Models\AgentTapFileJoin;
use App\Models\Merchant;
use App\Models\SmsCode;
use Carbon\Carbon;
use GuzzleHttp\Client;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Client\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected $validationRules = [];
    protected $validationMessages = [];
    protected $perPage;
    protected $title = null;
    const MSUCCESS = 'success';
    const MERROR = 'success';

    public function __construct()
    {
        $this->perPage = (integer) request()->get('perPage',15);
    }

    public function lang($local)
    {
        session(['lang'=>$local]);
        if(Auth::guard('manager')->check()){
            $user = Auth::guard('manager')->user();
            $user->update([
                'lang' => $local,
            ]);
        }elseif(Auth::guard('web')->check()){
            $user = Auth::guard('web')->user();
            $user->update([
                'lang' => $local,
            ]);
        }
        app()->setLocale($local);
        return back();
    }

    protected function sendResponse($result, $message = 'success',$code = 200)
    {
        $response = [
            'success' => true,
            'data'    => $result,
            'message' => $message,
            'status' => $code,
        ];
        return response()->json($response, 200);
    }

    protected function sendError($error, $code = 200, $errorMessages = [])
    {
        $response = [
            'success' => false,
            'message' => $error,
            'status' => $code,
        ];

        if(!empty($errorMessages)){
            $response['data'] = $errorMessages;
        }

        return response()->json($response, $code);
    }

    protected function uploadImage($file,$path = ''){
        $fileName = $file->getClientOriginalName();
        $file_exe = $file->getClientOriginalExtension();
        $new_name = uniqid().'.'.$file_exe;
        $directory = 'uploads'.'/'.$path;//.'/'.date("Y").'/'.date("m").'/'.date("d");
        $destienation = public_path($directory);
        $file->move($destienation , $new_name);
        return $directory.'/'.$new_name;
    }

    protected function deleteImage($image)
    {
        if(!is_null($image) && file_exists(public_path().$image)){
            unlink(public_path().$image);
            return true;
        }
        return false;
    }

    public function generation_code()
    {
        $code = mt_rand(1000,9999);
        $is_set = SmsCode::where('code',$code)->where('used', 0)->get();
        while(count($is_set) > 0){
            $code = mt_rand(1000,9999);
            $is_set = SmsCode::where('code',$code)->where('used', 0)->get();
        }
        return $code;
    }

    function send_sms_message($number, $message)
    {
        $res = [];
        $number = str_replace("+", "", $number);
//        if (!empty($number) && !empty($message)) {
//            $curl = curl_init();
//            $username = "966535798692";
//            $password = "Hus@159357564";
//            $sender = "Antaderk";
//            $link = "https://www.hisms.ws/api.php?send_sms&username=".$username."&password=".$password."&numbers=".$number."&sender=".$sender."&message=".$message;
//            $client = new Client();
//            $res = $client->request('GET', $link, []);
//            $log = self::saveLog($message, $number, $res);
////            Log::info(json_encode($res));
//        }

        return $res;
    }

    public static function saveLog($message, $mobileNumber, $response_number)
    {
        $statusList = [
            403 => "لا يوجد رصيد كاف في حسابك",
            500 => "مزود خدمة الرسائل النصية لا يعمل",
            503 => "لا يوجد رصيد كاف عند مزود الخدمة",
        ];
        $log = [];
        $log['message'] = $message;
        $log['type'] = "sms";
        $log['fail_reason'] = "";
        $log['to_number'] = $mobileNumber;
        $log['send_date'] = date("Y-m-d H:i:s");
        $log['status'] = "ok";
        $log['fail_reason'] = $response_number;
        $log['created_at'] = date("Y-m-d H:i:s");
        Log::info(json_encode($log));
        return true;
    }

    protected function redirectWith(string $route, string $message, $status = 'success'): \Illuminate\Http\RedirectResponse
    {
        return redirect()->route($route)->with('message', t($message))->with('m-class', $status);
    }

    public static function createAgentBusinessAccount($merchant, $identify_document, $cr_document)
    {
        $user_mobile = str_replace("+966","", $merchant->user->mobile);
        $data = array (
            'name' =>
                array (
                    'en' => $merchant->translate('en')->name ? $merchant->translate('en')->name:$merchant->name,
                    'ar' => $merchant->translate('ar')->name ? $merchant->translate('en')->name:$merchant->name,
                ),
            'type' => 'corp',
            'entity' =>
                array (
                    'legal_name' =>
                        array (
                            'en' => $merchant->translate('en')->name ? $merchant->translate('en')->name:$merchant->name,
                            'ar' => $merchant->translate('ar')->name ? $merchant->translate('en')->name:$merchant->name,
                        ),
                    'is_licensed' => true,
                    'license_number' => '2134342SE',
                    'not_for_profit' => false,
                    'country' => 'KW',
                    'settlement_by' => 'Acquirer',
                    'documents' =>
                        array (
                            0 =>
                                array (
                                    'type' => 'Commercial Registration',
                                    'number' => '1234567890',
                                    'issuing_country' => 'SA',
                                    'issuing_date' => '2019-07-09',
                                    'expiry_date' => '2024-07-09',
                                    'images' =>
                                        array (
                                            0 => $cr_document->tap_file_id,
                                        ),
                                ),
                            1 =>
                                array (
                                    'type' => 'Identity Document',
                                    'number' => '1234567890',
                                    'issuing_country' => 'SA',
                                    'issuing_date' => '2019-07-09',
                                    'expiry_date' => '2024-07-09',
                                    'images' =>
                                        array (
                                            0 => $identify_document->tap_file_id,
                                        ),
                                ),
                        ),
                    'bank_account' =>
                        array (
                            'iban' => $merchant->i_ban,
                        ),
                ),
            'contact_person' =>
                array (
                    'name' =>
                        array (
                            'title' => 'Mr',
                            'first' => $merchant->user->name,
                            'middle' => $merchant->user->name,
                            'last' => $merchant->user->name,
                        ),
                    'contact_info' =>
                        array (
                            'primary' =>
                                array (
                                    'email' => $merchant->user->email,
                                    'phone' =>
                                        array (
                                            'country_code' => '965',
                                            'number' => $user_mobile,
                                        ),
                                ),
                        ),
                    'is_authorized' => true,
                ),
            'brands' =>
                array (
                    0 =>
                        array (
                            'name' =>
                                array (
                                    'en' => $merchant->translate('en')->name ? $merchant->translate('en')->name:$merchant->name,
                                    'ar' => $merchant->translate('ar')->name ? $merchant->translate('en')->name:$merchant->name,
                                ),
                            'sector' =>
                                array (
                                    0 => 'Sec 1',
                                    1 => 'Sec 2',
                                ),
                            'website' => 'https://antaderk.com/',
                            'social' =>
                                array (
                                    0 => 'https://antaderk.com/',
                                    1 => 'https://antaderk.com/',
                                ),
                        ),
                ),
            'post' =>
                array (
                    'url' => 'https://antaderk.com/',
                ),
            'metadata' =>
                array (
                    'mtd' => 'metadata',
                ),
        );

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://api.tap.company/v2/business",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => json_encode($data),
            CURLOPT_HTTPHEADER => array(
                "authorization: Bearer sk_live_yeKxkRhGAuSBiE8CvdDZcOQY",
                "content-type: application/json"
            ),
        ));
// sk_test_JKaisERVAN5q8OedPmGWXr9j
        $response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);
        if ($err) {
            Log::alert($err);
            return [
                'status' => false,
                'error' => $err
            ];
        }

        $res = json_decode($response);
        if(isset($res->destination_id) && isset($res->id)){
            $merchant->tap_activate = 1;
            $merchant->destination_id = $res->destination_id;
            $merchant->business_id = $res->id;
            $merchant->json_response = $response;
            $merchant->save();
        }

        Log::emergency($response);
        return  $res;
    }

    public static function uploadAgentFileFromRequest($user, $identity_document , $type = "identity_document")
    {
        $curl = curl_init();
        if(function_exists('curl_file_create')){
            $filePath = curl_file_create($identity_document);
        } else{
            $filePath = '@' . realpath($identity_document);
        }
        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://api.tap.company/v2/files",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => array('purpose' => $type,'file_link_create' => 'true','file'=> $filePath),
            CURLOPT_HTTPHEADER => array(
                "Authorization: Bearer sk_live_yeKxkRhGAuSBiE8CvdDZcOQY"
            ),
        ));
        //sk_test_JKaisERVAN5q8OedPmGWXr9j
        $response = curl_exec($curl);

        curl_close($curl);

        $dataResponse = json_decode($response, true);

        if (!isset($dataResponse['id'])) {
            return back()->with('danger', 'عذراً عزيزي التاجر: لم تتم العملية تأكد من البيانات المدخلة.');
        }


        if ($user instanceof Merchant)
        {
                $agent_file = new AgentTapFile();
                $agent_file->merchant_id = $user->id;
                $agent_file->tap_file_id = $dataResponse['id'];
                $agent_file->file_type = $type;
                $agent_file->file_json = json_encode($dataResponse);
                $agent_file->save();
        }else{
                $agent_file = new AgentTapFileJoin();
                $agent_file->join_us_id = $user->id;
                $agent_file->tap_file_id = $dataResponse['id'];
                $agent_file->file_type = $type;
                $agent_file->file_json = json_encode($dataResponse);
                $agent_file->save();
        }
        return  $dataResponse;
    }



}
