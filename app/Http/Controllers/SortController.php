<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Bill;
use App\Customer;
use App\Offer;
use Illuminate\Support\Facades\Auth;

class SortController extends BaseController
{
    protected $sortable = array(
        "pages" => array("bills", "customers", "offers"),
        "columns" => array("bill_id", "customer", "offer", "entry_date", "contact")
    );

    protected $classes = array(
        "bills" => Bill::class,
        "customers" => Customer::class,
        "offers" => Offer::class
    );

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $page = $request->get("sort_page");
        $sort_by = $request->get("sort_by");
        $sort_dir = $request->get("sort_dir");

        if(in_array($page, $this->sortable["pages"]) && in_array($sort_by, $this->sortable["columns"]))
        {
            $class = $this->classes[$page];
            $user = Auth::user();
            $sort_by = (($page == "bills" || $page == "offers") && $sort_by == "customer" ? "customer_id" : $sort_by);

            $sorted = $class::where('user_id', $user->id)->orderBy($sort_by, $sort_dir)->paginate($this->defaultPagiLen);

            $customers = [];

            if($page == "bills" || $page == "offers")
            {
                foreach($sorted as $sort)
                {
                    if(!array_key_exists($sort->customer_id, $customers))
                    {
                        $customer = Customer::find($sort->customer_id)->company;
                        $customers[$sort->customer_id] = $customer;

                        $sort->company = $customer;
                    }
                    else
                    {
                        $sort->company = $customers[$sort->customer_id];
                    }
                }
            }

            return $sorted;
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
