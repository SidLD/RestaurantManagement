<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Product;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $bookings = Booking::all();
        foreach($bookings as $book){
            $username = $book->user->first_name ." ".$book->user->last_name;
            $book->username = $username;
            $book->products;
            $book->tables;
        }
        return $bookings;
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
        $request->validate([
            'userId' => 'required|numeric|min:0',
            'tableId' => 'required|numeric|min:0',
            'total' => 'required|numeric|min:1',
            'date' => 'required',
        ]);
        //User Carbon Now to track the time / This will make laravel accept the query since there
        //will be different time
        $newBook = Booking::create([
            'user_id' => $request->userId,
            'status' => "pending",
            'date' => $request->date,
            'total' => $request->total,
            'created_at' => Carbon::now(),
        ]);
        $newBook->tables()->attach($request->tableId);  
        for ($i=0; $i < count($request->products); $i++) {
            $currentProduct = Product::find($request->products[$i]['id']);
            $newBook->products()->attach($currentProduct, ['qty'=> $request->products[$i]['qty']]);
        }
        $newBook->save();
        return response()->json(['res' => 'ok', 'msg' => "Success"]);
    }
    public function book($id, Request $request)
    {
        $book = Booking::find($id);
        return $book;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $book = Booking::find($id);
        $book->products;
        $book->tables;
        $book->user = User::find($book->user_id);
        return $book;
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
            'userId' => 'required|numeric|min:0',
            'tableId' => 'required|numeric|min:0',
            'total' => 'required|numeric|min:1',
            'date' => 'required',
        ]);
        //User Carbon Now to track the time / This will make laravel accept the query since there
        //will be different time
        $book = Booking::find($id);
    
        $book->update([
            'user_id' => $request->userId,
            'status' => "pending",
            'date' => $request->date,
            'total' => $request->total,
            'created_at' => Carbon::now(),
        ]);
        //this will detach all tables and add new one
        $book->tables()->detach();
        $book->products()->detach();

        
        $book->tables()->attach($request->tableId);  
        for ($i=0; $i < count($request->products); $i++) {
            $currentProduct = Product::find($request->products[$i]['id']);
            $book->products()->attach($currentProduct, ['qty'=> $request->products[$i]['qty']]);
        }
        $book->save();
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
        $book = Booking::find($id);
        $products = $book->products;
        $table = $book->tables[0];
        $book->tables()->detach($table->id);
        foreach($products as $product){
            $current_product = Product::find($product->id);
            $current_product->bookings()->detach($book->id);
        }
        $book->delete();
        return response()->json(['res' => 'ok', 'msg' => "Success"]);
    }
}
