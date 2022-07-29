<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\Booking;
use App\Models\Employee;
use App\Models\Product;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($type)
    {
        $users = null;
        if($type === "user"){
            $users = User::whereHas('role', function (Builder $query){
                $query->where('name', 'like', 'user');
            })->get();
        }else if($type === "admin"){
            $users = User::whereHas('role', function (Builder $query){
                $query->where('name', 'like', 'admin');
            })->get();
        }else if($type === "employee"){
            $users = User::whereHas('role', function (Builder $query){
                $query->where('name', 'like', 'employee');
            })->get();
        }
        return $users;
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
    public function store(Request $request, $type)
    {
        $request->validate([
            'firstName' => 'required|min:3',
            'lastName' => 'required|min:3',
            'contact' => 'required|regex:/(09)[0-9]{9}/',
            'email' => 'required|email'
        ]);
        if($request->password > 1 && $request->password === $request->confirmPassword){
            $role_id = 1;
            if($type == "user"){
                $role_id = 1;
            }else if($type == "admin"){
                $role_id = 2;
            }else if($type == "employee"){
                $role_id = 3;
            }
            User::create([
                'first_name' => $request->firstName,
                'last_name' => $request->lastName,
                'contact' => $request->contact,
                'email' => $request->email,
                'role_id' => $role_id,
                'password' => Hash::make($request->password),
                'created_at' => $request->created_at,
            ]);
            return response()->json(['res' => 'ok', 'msg' => "Success"]);
        }else{
            return response()->json(['res' => 'fail', 'msg' => "Password Not Match"]);
        }
    }
    /**
     * Display the speci0fied resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($type, $id)
    {   
        return User::find($id);
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
    public function update(Request $request, $type, $id)
    {
        $user = User::find($id);
        $request->validate([
            'firstName' => 'required|min:3',
            'lastName' => 'required|min:3',
            'contact' => 'required|min:11',
            'email' => 'required|email',
        ]);
        $newUser = [
            'first_name' => $request->firstName,
            'last_name' => $request->lastName,
            'contact' => $request->contact,
            'email' => $request->email,
        ];
        if($request->password){
            if($request->password > 1 && $request->password === $request->confirmPassword){
                $user->update($newUser);
                return response()->json(['res' => 'ok', 'msg' => "Success"]);
            }else{
                return response()->json(['res' => 'fail', 'msg' => "Password Not Match"]);
            }
        }else{
            $user->update($newUser);
            return response()->json(['res' => 'ok', 'msg' => "Success"]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($type, $id)
    {
        try {
            $user = null;
            if($type === "user"){
                $user = User::find($id);
                $bookings = $user->bookings;
                foreach($bookings as $book){
                    $current_book = Booking::find($book->id);
                    $products = $current_book->products;
                    $table = $current_book->tables[0];
                    $current_book->tables()->detach($table->id);
                    foreach($products as $product){
                        $current_product = Product::find($product->id);
                        $current_product->bookings()->detach($book->id);
                    }
                    $current_book->delete();
                }
            }else{
                $user = User::find($id);
            }
            $user->delete();
            return response()->json(['res' => 'ok', 'msg' => "Success"]);
        } catch (\Throwable $th) {
            return $th;
            return response()->json(['res' => "fail", 'msg' => "User Not Found"]);
        }
    }
}
