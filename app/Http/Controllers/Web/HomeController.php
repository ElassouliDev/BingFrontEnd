<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Bank;
use App\Models\Branch;
use App\Models\City;
use App\Models\ContactUs;
use App\Models\Item;
use App\Models\JoinUs;
use App\Models\Manager;
use App\Models\Merchant;
use App\Models\MerchantType;
use App\Models\Order;
use App\Models\Package;
use App\Models\Testimonial;
use App\Models\User;
use App\Notifications\ContactUsNotification;
use App\Rules\EmailRule;
use App\Rules\IntroMobile;
use App\Rules\MobileLengthRule;
use App\Rules\MobileRule;
use App\Rules\StartWith;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;
use Proengsoft\JsValidation\Facades\JsValidatorFacade as JsValidator;

class HomeController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->validationRules["owner_name"] = 'required|max:255';
        $this->validationRules["email"] = ['required', 'email', 'max:50', new EmailRule()];
        $this->validationRules["gender"] = 'nullable|in:' . MALE . ',' . FEMALE;
        $this->validationRules["phone"] = ['required', new StartWith('5'), new MobileRule(), new MobileLengthRule(9)];
        $this->validationRules["lat"] = 'nullable';
        $this->validationRules["lng"] = 'nullable';
        $this->validationRules["merchant_name"] = 'required|max:255';
        $this->validationRules["city"] = 'required|exists:cities,id';
        $this->validationRules["merchant_type"] = 'required|exists:merchant_types,id';
        $this->validationRules['id_no'] = 'required|numeric|digits:10';
        $this->validationRules['id_file'] = 'required|image';
        $this->validationRules['comm_registration_no'] = 'required';
        $this->validationRules['comm_registration_file'] = 'required|image';
        $this->validationRules['bank_id'] = 'required|exists:banks,id';
        $this->validationRules['i_ban'] = ['required', new StartWith('SA'), 'min:24', 'max:24'];
        $this->validationRules['swift_code'] = 'required';
//        dd(34);
    }

    public function welcome()
    {
        return view('web.index');
     }
     public function join_us()
    {
        return view('web.join_us');
     }  public function contact_us()
    {
        return view('web.contact');
     }

    public function contactUs(Request $request)
    {
        return 'in progress';

        $contValidationRules["name"] = 'required|max:255';
        $contValidationRules["email"] = 'required|email';
        $contValidationRules["message"] = 'required';
        $contValidationRules["mobile"] = 'required|max:13|min:13';
        $request->validate($contValidationRules);
        $data = $request->all();
        $data['source'] = 'Web App';
        $contact = ContactUs::create($data);
        Notification::send(Manager::query()->get(), new ContactUsNotification($contact));
        return redirect()->back()->with('message', w('Message Sent Successfully'))->with('m-class', 'success');
    }

    public function joinUs(Request $request)
    {
//        dd($this->validationRules);
        $request->validate($this->validationRules);

//        dd(checkRequestIsWorkingOrNot());
        $store = new JoinUs();
        $store->lat = $request->lat;
        $store->lng = $request->lng;
        $store->phone = '+966' . $request->phone;
        $store->email = $request->email;
        $store->owner_name = $request->owner_name;
        $store->merchant_name = [
            'ar' => $request->merchant_name,
            'en' => $request->merchant_name,
        ];;
        $store->city_id = $request->city;
        $store->merchant_type_id = $request->merchant_type;
        $store->bank_id = $request->bank_id;
        $store->i_ban = $request->i_ban;
        $store->swift_code = $request->swift_code;
        $store->id_no = $request->id_no;
        $store->comm_registration_no = $request->comm_registration_no;

        if ($request->hasFile('id_file')) {
            $store->id_file = $this->uploadImage($request->file('id_file'), 'restaurants');
        }
        if ($request->hasFile('comm_registration_file')) {
            $store->comm_registration_file = $this->uploadImage($request->file('comm_registration_file'), 'restaurants');
        }
        $store->save();
//        if ($request->hasFile('id_file')) {
//            $this->uploadAgentFileFromRequest($joinUs, $data['id_file'], "identity_document");
//        }
//        if ($request->hasFile('comm_registration_file')) {
//            $this->uploadAgentFileFromRequest($joinUs, $data['comm_registration_file'], 'tax_document_user_upload');
//        }
        return redirect()->back()->with('message', w('Date Sent Successfully'))->with('m-class', 'success');
    }
}
