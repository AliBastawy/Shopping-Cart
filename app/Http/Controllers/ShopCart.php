<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class ShopCart extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
      // For GET API
      // $products = DB::select('select * from products');
      $products = DB::table('products')->get();
      return ['products'=>$products];
    }

    // View Records for Laravel Application
    public function viewRecords()
    {
      //
      $products = DB::table('products')->get();
      return view('products_view',['products'=>$products]);
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
      $name = $request->input('name');
      $price = $request->input('price');
      $body = $request->input('body');
      // Add New Created Product to Stripe Products
      $stripe = new \Stripe\StripeClient("sk_test_51LEwj8KwHhxzhn4RYAYqHKfTweb3x3j4CakFdUjuZRfbOD4babCyWdyIuLjUPqTrzcMB2k55veW5ONzoYkzuaPT900VD53FjTx");
      $check = $stripe->products->create([
        'name' => $name,
        'description' => $body,
        'default_price_data' => [
          'currency' => 'USD',
          'unit_amount' => $price * 100
        ]
      ]);
      $data=array('name'=>$name,"price"=>$price,"body"=>$body, "priceID" => $check->default_price);
      DB::table('products')->insert($data);
      return to_route('view-records');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
      $product = DB::select('select * from products where id = ?', [$id]);
      return view('products-create', ['product'=>$product]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id, Request $request)
    {
      //
      $name = $request->input('name');
      $price = $request->input('price');
      $body = $request->input('body');
      DB::update('update products set name = ?, price = ?, body = ? where id = ?', [$name, $price, $body, $id]);
      return to_route('view-records');
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
      DB::delete('delete from products where id = ?',[$id]);
      return to_route('view-records');
    }
}
