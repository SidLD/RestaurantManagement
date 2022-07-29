<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Carbon\Carbon;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $categories = Category::all();
        foreach($categories as $category){
            $products = $category->products;
        }
        return $categories;
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
        Category::create([
            'name' => $request->name,
            'created_at' => Carbon::now()->format('Y-m-d')
        ]);
        return response()->json(['res' => 'ok', 'msg' => "Success"]);
    }

    public function addProduct($id, Request $request){
        $category = Category::find($id);
        $category->products()->attach($request->ids);
        return response()->json(['res' => 'ok', 'msg' => "Success"]);

    }
    public function removeProduct($id, $product_id){
        $category = Category::find($id);
        $product = Product::find($product_id);
        $category->products()->detach($product->id);
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
        $category = Category::find($id);
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
            $category = Category::find($id);
            $products = $category->products;
            foreach($products as $product){
                $current_product = Product::find($product->id);
                $current_product->categories()->detach($category->id);
            }
            $category->delete();
            return response()->json(['res' => 'ok', 'msg' => "Success"]);
        } catch (\Throwable $th) {
            return response()->json(['res' => 'fail', 'msg' => "Something Went Wrong"]);
        }

    }
}
