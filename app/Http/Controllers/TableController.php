<?php

namespace App\Http\Controllers;

use App\Models\Table;
use Carbon\Carbon;
use Illuminate\Http\Request;

class TableController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $tables = Table::all();
        return $tables;
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
            'name' => 'required|min:3',
            'status' => 'required',
            'type' => 'required|min:3',
            'number_of_seats' => 'required|numeric|min:1' 
        ]);
        Table::create([
            'name' => $request->name,
            'status' => $request->status,
            'type' => $request->type,
            'number_of_seats' => $request->number_of_seats,
            'created_at' => Carbon::now()->format('Y-m-d')
        ]);
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
            'status' => 'required',
            'type' => 'required|min:3',
            'number_of_seats' => 'required|numeric|min:1' 
        ]);
        $table = Table::find($id);
        $table->update([
            'name' => $request->name,
            'status' => $request->status,
            'type' => $request->type,
            'number_of_seats' => $request->number_of_seats,
            'created_at' => Carbon::now()->format('Y-m-d')
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
        $table = Table::find($id);
        $bookings = $table->bookings;
        foreach($bookings as $book){
            $book->products()->detach($table->id);
        }
        $table->delete();
        return response()->json(['res' => 'ok', 'msg' => "Success"]);
    }
}
