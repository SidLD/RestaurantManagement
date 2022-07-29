<?php

namespace App\Http\Controllers;

use App\Models\Image;
use App\Models\Product;
use App\Models\Meal;
use Carbon\Carbon;
use Illuminate\Http\Request;

class MealController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $meals = Meal::all();
        foreach($meals as $meal){
            $meal->products;
        }
        return $meals;
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
        Meal::create([
            'name' => $request->name,
            'created_at' => Carbon::now()->format('Y-m-d')
        ]);
        return response()->json(['res' => 'ok', 'msg' => "Success"]);
    }

    public function addProduct($id, Request $request){
        $meal = Meal::find($id);
        $meal->products()->attach($request->ids);
        return response()->json(['res' => 'ok', 'msg' => "Success"]);

    }
    public function removeProduct($id, $product_id){
        $meal = Meal::find($id);
        $product = Product::find($product_id);
        $meal->products()->detach($product->id);
        return response()->json(['res' => 'ok', 'msg' => "Success"]);
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
        $request->validate([
            'name' => 'required|min:3',
        ]);
        $category = Meal::find($id);
        $category->update([
            'name' => $request->name
        ]);
        return response()->json(['res' => 'ok', 'msg' => "Success"]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $meal = Meal::find($id);
            $products = $meal->products;
            foreach($products as $product){
                $current_product = Product::find($product->id);
                $current_product->categories()->detach($meal->id);
            }
            $meal->delete();
            return response()->json(['res' => 'ok', 'msg' => "Success"]);
        } catch (\Throwable $th) {
            return response()->json(['res' => 'fail', 'msg' => "Something Went Wrong"]);
        }

    }
}
