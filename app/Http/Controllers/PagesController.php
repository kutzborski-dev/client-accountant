<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Customer;
use App\Offer;
use App\Bill;
use App\Vat;
use Illuminate\Support\Facades\Auth;

class PagesController extends BaseController
{
    public function index(Request $request)
    {
        $hl = ($request->get('hl') && !empty($request->get('hl')) ? $request->get('hl') : 'en');

        $user = Auth::user();

        $offers = Offer::where('user_id', $user->id)->orderBy('id', 'desc')->take(5)->get();
        $bills = Bill::where('user_id', $user->id)->orderBy('id', 'desc')->take(5)->get();
        $customers = Customer::where('user_id', $user->id)->orderBy('id', 'desc')->take(5)->get();

        $bills->count = Bill::where('user_id', $user->id)->count();
        $offers->count = Offer::where('user_id', $user->id)->count();
        $customers->count = Customer::where('user_id', $user->id)->count();

        $bills->percentage = (100 / 50 * $bills->count);
        $offers->percentage = (100 / 50 * $offers->count);
        $customers->percentage = (100 / 25 * $customers->count);

        $bills->colour = $this->getColour($bills->count, 50);
        $offers->colour = $this->getColour($offers->count, 50);
        $customers->colour = $this->getColour($customers->count, 25);

        $bills->colourClass = $this->colourToClass($bills->colour);
        $offers->colourClass = $this->colourToClass($offers->colour);
        $customers->colourClass = $this->colourToClass($customers->colour);

        $found_o_customers = array();
        $found_b_customers = array();

        //$offers = $this->addToArrayObj($offers, ["company" => "customer"], "customer_id", new Customer);

        foreach($offers as $offer)
        {
            if(!array_key_exists($offer->customer_id, $found_o_customers))
            {
                $customer = Customer::find($offer->customer_id);

                $offer->customer = $customer->company;

                $found_o_customers[$offer->customer_id] = $customer->company;
            }
            else
            {
                $offer->customer = $found_o_customers[$offer->customer_id];
            }
        }

        foreach($bills as $bill)
        {
            if(!array_key_exists($bill->customer_id, $found_b_customers))
            {
                $customer = Customer::find($bill->customer_id);

                $bill->customer = $customer->company;

                $found_b_customers[$bill->customer_id] = $customer->company;
            }
            else
            {
                $bill->customer = $found_b_customers[$bill->customer_id];
            }
        }

        return view('pages.dashboard', ['hl' => $hl, 'json' => $this->json, 'offers' => $offers, 'bills' => $bills, 'customers' => $customers, 'user' => $user]);
    }

    public function documents(Request $request)
    {
        $hl = ($request->get('hl') && !empty($request->get('hl')) ? $request->get('hl') : 'en');

        return view('pages.documents')->with('hl', $hl)->with('json', $this->json);
    }

    public function login(Request $request)
    {
        $hl = ($request->get('hl') && !empty($request->get('hl')) ? $request->get('hl') : 'en');

        return view('auth.login', ['hl' => $hl, 'json' => $this->json]);
    }
}
