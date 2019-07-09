<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Bill;
use App\Customer;
use App\Product;
use App\Vat;
use App\UserSettings;
use Illuminate\Support\Facades\Auth;
use PDF;
use DB;

class BillsController extends BaseController
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

        $bills = Bill::where('user_id', $user->id)->orderBy(($request->get('sort') ? $request->get('sort') : 'id'), ($request->get('sort_dir') ? $request->get('sort_dir') : 'desc'))->paginate($this->defaultPagiLen);
        $bills->count = Bill::where('user_id', $user->id)->count();

        if($bills->count > 0)
        {
            $bills->curPageCount = $bills->count();
            $bills->highestId = Bill::where('user_id', $user->id)->max('id');
            $bills->highestId = ($bills->highestId !== null ? $bills->highestId + 1 : null);
            $bills->customers = Customer::where('user_id', $user->id)->get();
            $bills->percentage = (100 / 50 * $bills->count);
            $bills->colour = $this->getColour($bills->count, 50);
            $bills->colourClass = $this->colourToClass($bills->colour);
            $bills->total_price = 0;
            $bills->vat = 0;
            $bills->price = 0;
            $bills->monthly = false;
            $bills->month_price = 0;

            if($this->user->settings->monthly_goal)
            {
                $bills->monthly = true;
                $bills->monthly_value = $this->user->settings->monthly_goal;
            }

            $found_customers = [];
            
            foreach($bills as $bill)
            {
                if(!array_key_exists($bill->customer_id, $found_customers))
                {
                    $customer = Customer::find($bill->customer_id);
                    $products = Product::where('bill_id', $bill->id)->get();
                    $bills->product_count = Product::where('bill_id', $bill->id)->count();

                    $bill_date = explode("-", $bill->entry_date);
                    $bill_date = $bill_date[1] .".". $bill_date[0];

                    foreach($products as $product)
                    {
                        if($product->vat_id != 0)
                        {
                            $product->vat = Vat::find($product->vat_id)->vat_value;
                        }

                        if($product->product_price != 0)
                        {
                            $bills->total_price += $product->product_price;
                            $bills->price += ($product->product_price - (($product->vat / 100) * $product->product_price));
                            $bills->vat += ($product->vat / 100) * $product->product_price;
                        }

                        if($bill_date == $this->curMY)
                        {
                            $bills->month_price += $product->product_price;
                        }
                    }

                    $bill->customer = $customer->company;

                    $found_customers[$bill->customer_id] = $customer->company;
                }
                else
                {
                    $bill->customer = $found_customers[$bill->customer_id];
                }
            }

            $bills->monthly_togo = ($bills->monthly_value - $bills->month_price > 0 ? $this->currency($this->nformat($bills->monthly_value - $bills->month_price)) : "Goal reached");
            $bills->avg_price = $this->currency($this->nformat($bills->price / $bills->count));
            $bills->total_price = $this->currency(($bills->total_price > $this->millionLen ? round($this->nformat($bills->total_price), 2) ."M" : $this->nformat($bills->total_price)));
            $bills->vat = $this->currency($this->nformat($bills->vat));
            $bills->price = $this->currency(($bills->price > $this->millionLen ? round($this->nformat($bills->price), 2) ."M" : $this->nformat($bills->price)));
            
            if($bills->monthly)
            {
                $bills->monthly_value = $this->currency($this->nformat($bills->monthly_value));
                $bills->monthly_days_left = (date("t") - date("d")) + 1;
            }

            $bills->monthly_percentage = round((100 / (date("t") + 1)) * date("d"));
            $bills->monthly_colour = $this->getColour(date("d"), date("t") + 1);
        }
        else
        {
            $bills->highestId = 1;
            $bills->customers = array();
            $bills->percentage = (100 / 50 * $bills->count);
            $bills->colour = $this->getColour($bills->count, 50);
            $bills->colourClass = $this->colourToClass($bills->colour);
            $bills->total_price = $this->currency($this->nformat(0));
            $bills->vat = $this->currency($this->nformat(0));
            $bills->price = $this->currency($this->nformat(0));
            $bills->avg_price = $this->currency($this->nformat(0));
            $bills->monthly = false;
            $bills->month_price = 0;
            $bills->customers = Customer::where('user_id', $user->id)->get();

            if($this->user->settings->monthly_goal)
            {
                $bills->monthly = true;
                $bills->monthly_value = $this->user->settings->monthly_goal;
            }

            if($bills->monthly)
            {
                $bills->monthly_days_left = (date("t") - date("d")) + 1;
            }

            $bills->monthly_togo = ($bills->monthly_value - $bills->month_price > 0 ? $this->currency($this->nformat($bills->monthly_value - $bills->month_price)) : "Goal reached");
            $bills->monthly_percentage = round((100 / (date("t") + 1)) * date("d"));
            $bills->monthly_colour = $this->getColour(date("d"), date("t") + 1);
            $bills->monthly_value = $this->currency($this->nformat($bills->monthly_value));
        }

        $vats = Vat::where('user_id', $user->id)->get();

        if(!$request->get("page") && !$request->get("sort") && !$request->get("sort_dir"))
        {
            return view('pages.bills', ['hl' => $hl, 'json' => $this->json, 'bills' => $bills, 'vats' => $vats]);
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
        $this->validate($request, [
            "bid" => "required",
            "cid" => "required",
            "pid" => "required",
            "product" => "required",
            "price" => "required",
            "vat" => "required"
        ]);

        $hl = ($request->get('hl') && !empty($request->get('hl')) ? $request->get('hl') : 'en');

        $bHighestId = Bill::all()->max('id');

        $bill = new Bill;
        $bill->bill_id = $request->input('bid');
        $bill->user_id = Auth::user()->id;
        $bill->customer_id = $request->input('cid');

        $products = $request->input('product');

        $i = 0;

        foreach($products as $product)
        {
            $prod = new Product;
            $prod->bill_id = $bHighestId + 1;
            $prod->product_id = $request->input('pid')[$i];
            $prod->product_name = $product;
            $prod->product_price = $request->input('price')[$i];
            $prod->vat_id = $request->input('vat')[$i];
            $prod->save();

            $this->monthly_goal->gained += $request->input('price')[$i];

            $i++;
        }
        
        $bill->save();

        DB::table('monthly_goals')->where('id', $this->monthly_goal->id)->update([
            'gained' => $this->monthly_goal->gained
        ]);

        return redirect()->route('bills.index')->with('success', $this->json->$hl->accounting->bill ." ". $this->json->$hl->accounting->create_success);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id, Request $request)
    {
        $hl = ($request->get('hl') && !empty($request->get('hl')) ? $request->get('hl') : 'en');
        
        if(isset($request->type) && $request->type === "GET")
        {
            $bill = Bill::find($id);
            $customer = Customer::find($bill->customer_id);
            $products = Product::where('bill_id', $id)->get();
            $bill->totalPrice = 0;
            $bill->totalVat = 0;

            $bill->customer = $customer->company;

            foreach($products as $product)
            {
                $bill->totalPrice += $product->product_price;

                if($product->vat_id != 0)
                {
                    $vat = Vat::find($product->vat_id);

                    $product->vat_value = $vat->vat_value;
                    $product->vat_name = $vat->vat_name;
                    $product->vat_id = $vat->id;
                    $bill->totalVat += $product->product_price * ($vat->vat_value / 100);
                }
                else
                {
                    $product->vat_value = null;
                    $product->vat_name = null;
                    $product->vat_id = null;
                }
            }

            $bill->products = $products;
            $bill->total = $bill->totalPrice + $bill->totalVat;

            return $bill;
        }
        else
        {
            Bill::where('id', $id)->delete();

            return redirect()->route('bills.index')->with('success', $this->json->$hl->accounting->bill ." ". $this->json->$hl->accounting->delete_success);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        
    }

    public function generateBill($id, Request $request)
    {
        $hl = ($request->get("hl") ? $request->get("hl") : "en");

        $bill = Bill::find($id);
        $customer = Customer::find($bill->customer_id);
        $products = Product::where('bill_id', $id)->get();

        $bill->customer = $customer->company;
        $bill->date = substr($bill->entry_date, 0, strpos($bill->entry_date, ":") - 3);
        $bill->totalPrice = 0;
        $bill->totalVat = 0;
        $bill->pre_text = $request->get("pre");
        $bill->suf_text = $request->get("suf");
        
        foreach($products as $product)
        {
            $vat = Vat::find($product->vat_id);

            $product->product_vat = ($vat ? $product->product_price * ($vat->vat_value / 100) : 0);
            $product->product_price = $product->product_price;

            $bill->totalPrice += $product->product_price;
            $bill->totalVat += $product->product_vat;

            $product->product_vat = $this->currency($this->nformat($product->product_vat, "."));
            $product->product_price = $this->currency($this->nformat($product->product_price, "."));
        }

        $bill->total = $this->currency(number_format($bill->totalPrice + $bill->totalVat, 2, ".", ","));
        $bill->totalPrice = $this->currency(number_format($bill->totalPrice, 2, ".", ","));
        $bill->totalVat = $this->currency(number_format($bill->totalVat, 2, ".", ","));

        $data = ["bill" => $bill, "products" => $products, "hl" => $hl];

        //return view('pages.bill', ["bill" => $bill, "products" => $products, "hl" => $hl]);
        $pdf = PDF::loadView('pages.bill', $data);
        return $pdf->download('bill_'. $bill->bill_id .'_'. $bill->date .'.pdf');
        //return $pdf->stream();
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
        $this->validate($request, [
            "bid" => "required",
            "cid" => "required",
            "pid" => "required",
            "product" => "required",
            "price" => "required",
            "vat" => "required",
            "prid" => "required"
        ]);

        $hl = ($request->get('hl') && !empty($request->get('hl')) ? $request->get('hl') : 'en');

        $bill = new Bill;
        $bill->exists = true;
        $bill->id = $id;
        $bill->bill_id = $request->input('bid');
        $bill->customer_id = $request->input('cid');
        $bill->file = "";

        $products = $request->input('product');

        $i = 0;

        $pFound = [];
        $eFound = [];

        $eProducts = Product::where("bill_id", $id)->get();

        foreach($eProducts as $product)
        {
            $eFound[] = $product->id;
        }

        foreach($products as $product)
        {
            $prod = new Product;

            if(!in_array($request->prid[$i], $eFound))
            {
                $prod->bill_id = $id;

                $prid = 0;
            }
            else
            {
                $prod->exists = true;
                $prod->id = $request->prid[$i];
                $prid = $request->prid[$i];
            }

            $prod->product_id = $request->input('pid')[$i];
            $prod->product_name = $product;
            $prod->product_price = $request->input('price')[$i];
            $prod->vat_id = $request->input('vat')[$i];
            
            $prod->save();

            $pFound[] = $prid;

            $i++;
        }

        foreach($eFound as $exist)
        {
            if(!in_array($exist, $pFound))
            {
                $prod = Product::find($exist)->delete();
            }
        }
        
        $bill->save();

        return redirect()->route('bills.index')->with('success', $this->json->$hl->accounting->bill ." ". $this->json->$hl->accounting->edit_success);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {

    }
}
