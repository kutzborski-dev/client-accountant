<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Offer;
use App\Customer;
use Illuminate\Support\Facades\Auth;

class OffersController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $hl = ($request->get('hl') && !empty($request->get('hl')) ? $request->get('hl') : 'en');
        
        $user = Auth::user();

        $offers = Offer::where('user_id', $user->id)->orderBy('id', 'desc')->get();

        $found_customers = array();

        foreach($offers as $offer)
        {
            if(!array_key_exists($offer->customer_id, $found_customers))
            {
                $customer = Customer::find($offer->customer_id);

                $offer->customer = $customer->company;

                $found_customers[$offer->customer_id] = $customer->company;
            }
            else
            {
                $offer->customer = $found_customers[$offer->customer_id];
            }
        }

        return view('pages.offers', ['hl' => $hl, 'json' => $this->json, 'offers' => $offers]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
