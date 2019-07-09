<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Bill;
use App\Customer;
use App\Offer;
use Illuminate\Support\Facades\Auth;
use DB;

class SearchController extends BaseController
{
    //protected $searchable = ["Bill" => Bill::class, "Customer" => Customer::class, "Offer" => Offer::class];
    protected $searchable = ["bill_id", "customer_id", "company", "contact"];
    protected $classes = ["Bill", "Customer", "Offer"];

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index(Request $request)
    {
        $mKey = (in_array($request->get("t"), $this->classes) ? $request->get("t") : null);
        $s = ($request->get("q") == "customer" ? "company" : $request->get("q"));
        $sort = $request->get("sort");
        $sort_dir = $request->get("sort_dir");

        if(in_array($mKey, $this->classes))
        {
            //$search_result = DB::table('customers')->join('bills', 'customers.id', '=', 'bills.customer_id')->join('offers', 'customers.id', '=', 'bills.customer_id')->where('company', 'LIKE', '%'. $s .'%')->get();
            
            $search_result = DB::table('users');

            switch($mKey)
            {
                case "Bill":
                    $search_result = $search_result->join('customers', function($join)
                    {
                        $join->on('users.id', '=', 'customers.user_id')->where('customers.user_id', Auth::user()->id);
                    })->join('bills', function($join)
                    {
                        $join->on('customers.id', '=', 'bills.customer_id')->where('bills.user_id', Auth::user()->id);
                    })->select('customers.company', 'bills.*');
                break;

                case "Customer":
                break;

                case "Offer":
                    $search_result = $search_result->join('customers', 'users.id', '=', 'customers.user_id')->join('bills', 'customers.id', '=', 'offers.customer_id')->select('customers.company', 'offers.*');
                break;
            }

            $i = 0;

            foreach($this->searchable as $searchable)
            {
                if($i = 0)
                {
                    $search_result = $search_result->where($searchable, "LIKE", "%". $s ."%");
                }
                else
                {
                    $search_result = $search_result->orWhere($searchable, "LIKE", "%". $s ."%");
                }

                $i++;
            }

            if($sort != false && $sort != "false" && $sort_dir != false && $sort_dir != "false")
            {
                return $search_result->orderBy($sort, $sort_dir)->paginate($this->defaultPagiLen);
            }
            else
            {
                return $search_result->orderBy('id', 'desc')->paginate($this->defaultPagiLen);
            }
        }
        else
        {
            return "Error: 901";
        }
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
