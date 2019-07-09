<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\UserData;
use App\UserSettings;
use DB;

class SettingsController extends BaseController
{
    /*function __construct(Request $request)
    {
        parent::__construct($request);
    }*/

    function provider($page = false, Request $request)
    {
        switch($page)
        {
            case false:
                return $this->index($request);
            break;

            case "profile":
                return $this->index($request);
            break;

            default:
                return $this->$page($request);
        }
    }

    public function index(Request $request)
    {
        $hl = ($request->get('hl') && !empty($request->get('hl')) ? $request->get('hl') : 'en');
        $this->route = "settings.profile";      

        $udid = $request->get("udid");
        $company = $request->get("company");
        $title = $request->get("title");
        $contact = $request->get("contact");
        $street = $request->get("street");
        $no = $request->get("housenumber");
        $city = $request->get("city");
        $postcode = $request->get("postcode");
        $logo = ($request->file('logo') ? $request->file('logo') : $request->get('logo'));

        $sArray = ['json' => $this->json, 'hl' => $hl, 'page' => $this->json->$hl->general->profile, 'route' => $this->route];

        if($udid && !empty($udid) && $company && !empty($company) && $title && !empty($title) && $contact && !empty($contact) && $street && !empty($street) && $no && !empty($no) && $city && !empty($city) && $postcode && !empty($postcode))
        {
            $udata = new UserData;

            $udata->exists = true;
            $udata->id = $udid;
            $udata->company = $company;
            $udata->title = $title;
            $udata->contact = $contact;
            $udata->street = $street;
            $udata->housenumber = $no;
            $udata->city = $city;
            $udata->postcode = $postcode;

            if($logo)
            {
                if($logo !== 'dummy.png')
                {
                    $udata->logo = $this->user['userData']->user_id ."_". time() .".". $logo->getClientOriginalExtension();
                    
                    if($this->user['userData']->logo_raw !== 'dummy.png')
                    {
                        $this->delFile($this->user['userData']->logo_raw, "/logos/");
                    }
                    
                    $logo->storeAs('logos', $udata->logo);
                }
                else if($logo === 'dummy.png' && $this->user['userData']->logo_raw !== 'dummy.png')
                {
                    $udata->logo = "";
                    $this->delFile($this->user['userData']->logo_raw, "/logos/");
                }
            }

            $udata->save();

            return redirect()->route('settings')->with($sArray);
        }

        return view('pages.settings.profile', $sArray);
    }

    public function bills(Request $request)
    {
        $hl = ($request->get('hl') && !empty($request->get('hl')) ? $request->get('hl') : 'en');
        $this->route = "settings.bills";

        if(count($request->input()) > 2)
        {
            foreach($request->input() as $rkey => $rval)
            {
                if($rkey !== "_token" && $rkey !== "_method")
                {
                    if(!empty($rval))
                    {
                        DB::table('user_settings')->where('user_id', $this->user->id)->where('setting_name', $rkey)->update([
                            'setting_value' => $rval
                        ]);
                    }
                }
            }

            return redirect('settings/bills');
        }

        return view('pages.settings.bills', ['json' => $this->json, 'hl' => $hl, 'page' => $this->json->$hl->accounting->bills, 'route' => $this->route]);
    }
}
