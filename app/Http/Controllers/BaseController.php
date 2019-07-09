<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\UserData;
use App\UserSettings;
use DB;

class BaseController extends Controller
{
    protected $json;
    protected $colours = ["#3fd3ce", "#f9b343", "#fa4351"];
    protected $defaultPagiLen = 10;
    protected $user;
    protected $data = [];
    protected $millionLen = 1000000;
    protected $redirectURL = 'bhaltungtest';
    protected $route = null;

    public function getFile($file, $dir = "/")
    {
        if(!Storage::exists($dir.$file))
        {
            return false;
        }

        return asset('storage/app/'. $dir.$file);
    }

    public function delFile($file, $dir = "/")
    {
        if(!Storage::exists($dir.$file))
        {
            return false;
        }

        Storage::delete($dir.$file);
    }

    public function saveFile($filename, $dir, $file)
    {
        if(!Storage::exists($dir))
        {
            Storage::makeDirectory($dir);
        }

        Storage::put($filename, $file);
    }

    public function __construct(Request $request)
    {
        $this->middleware(function ($request, $next) {
            $this->user = Auth::user()->toArray();

            $this->user['userData'] = UserData::where('user_id', $this->user['id'])->first();
            $this->user['userData']['logo_raw'] = (empty($this->user['userData']['logo']) ? "dummy.png" : $this->user['userData']['logo']);
            $this->user['userData']['logo'] = $this->getFile((empty($this->user['userData']['logo']) ? "dummy.png" : $this->user['userData']['logo']), "logos/");
            $this->user['settings'] = [];

            $this->user['userData']['contact'] = (isset($this->user['userData']['contact']) ? $this->user['userData']['contact'] : $this->user['name']);

            $settings = json_decode(UserSettings::where('user_id', $this->user['id'])->get());

            for($i = 0; $i < count($settings); $i++)
            {
                $setting = $settings[$i];
                $setting_name = $setting->setting_name;

                $this->user['settings'][$setting_name] = $setting->setting_value;
            }

            $this->user['settings']['currency'] = (isset($this->user['settings']['currency']) ? $this->user['settings']['currency'] : '£');
            $this->user['settings']['monthly_goal'] = (isset($this->user['settings']['monthly_goal']) ? $this->user['settings']['monthly_goal'] : 0);

            $this->user = json_decode(json_encode($this->user), false);

            $this->json = json_decode(file_get_contents(storage_path() . '/includes/translation.json'));
            $hl = ($request->get('hl') && !empty($request->get('hl')) ? $request->get('hl') : 'en');

            if($this->user->settings)
            {
                $this->curMY = date("m.Y");

                $this->monthly_goal = DB::table('monthly_goals')->where('user_id', $this->user->id)->where('date', $this->curMY)->first();

                if(!$this->monthly_goal || $this->monthly_goal === null)
                {
                    DB::table('monthly_goals')->insert([
                        'user_id' => $this->user->id,
                        'goal' => $this->user->settings->monthly_goal,
                        'date' => $this->curMY,
                        'gained' => 0
                    ]);

                    DB::table('user_settings')->insert([
                        'user_id' => $this->user->id,
                        'setting_name' => 'monthly_goal',
                        'setting_value' => 0
                    ]);
                }
                else if($this->user->settings->monthly_goal !== $this->monthly_goal->goal)
                {
                    DB::table('monthly_goals')->where('id', $this->monthly_goal->id)->update([
                        'goal' => $this->user->settings->monthly_goal
                    ]);
                }
            }

            View::share(['json' => $this->json, 'hl' => $request->get('hl'), 'defaultPagiLen' => $this->defaultPagiLen, 'userData' => $this->user->userData, 'settings' => $this->user->settings, 'route' => $this->route]);

            return $next($request);
        });
    }

    public function currency($num)
    {
        switch($this->user->settings->currency)
        {
            case "£":
                return $this->user->settings->currency . $num;
            break;

            case "$":
                return $this->user->settings->currency . $num;
            break;

            default:
                return $num . $this->user->settings->currency;
        }
    }

    public function nformat($number, $dec = false)
    {
        $dec = (!$dec ? "." : $dec);
        $thou = ($dec === "." ? "," : ".");

        $format = number_format($number, 2, $dec, $thou);

        return $format;
    }

    public function getColour($num, $max)
    {   
        for($i = 0; $i < count($this->colours); $i++)
        {
            $part = $max / count($this->colours) + ($max / count($this->colours)) * $i;

            if($num < $part)
            {
                return $this->colours[$i];
            }
        }
    }

    public function colourToClass($colour)
    {
        switch($colour)
        {
            case "#3fd3ce":
                return "blue";
            break;

            case "#f9b343":
                return "orange";
            break;

            case "#fa4351":
                return "red";
            break;
        }
    }

    public function addToArrayObj($aryObj, $add, $keys, $class)
    {
        $found = [];

        foreach($aryObj as $data)
        {
            if(!array_key_exists($data->$keys, $found))
            {
                $add_data = $class->find($data->$keys);

                if(!is_array($add))
                {
                    $data->$add = $add_data->$add;
                }
                else
                {
                    foreach($add as $key => $value)
                    {
                        $data->$value = $add_data->$key;
                    }
                }

                $found[$data->$keys] = $data->$keys;
            }
            else
            {
                if(!is_array($add))
                {
                    $data->add = $found[$data->$keys];
                }
                else
                {
                    foreach($add as $key => $value)
                    {
                        $data->$value = $found[$data->$keys];
                    }
                }
            }
        }

        return $aryObj;
    }

    /*
    addToArrayObj($offers, ["company" => "customer"], "customer_id", "Offer");

    foreach($offers as $offer)
    {
        if(array_key_exists($offer->customer_id, $found))
        {
            $customer = App\Customer::find($offer->customer_id);

            $offer->customer = $customer->customer;
        }
    }
    */
}
