<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = Admin::all();
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
    public function store(Request $request)
    {
        $request->validate([
            'firstName' => 'required|min:3',
            'lastName' => 'required|min:3',
            'contact' => 'required|regex:/(09)[0-9]{9}/',
            'email' => 'required|email'
        ]);
        if($request->password > 1 && $request->password === $request->confirmPassword){
            Admin::create([
                'first_name' => $request->firstName,
                'last_name' => $request->lastName,
                'contact' => $request->contact,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'created_at' => $request->created_at,
            ]);
            return response()->json(['res' => 'ok', 'msg' => "Success"]);
        }else{
            return response()->json(['res' => 'fail', 'msg' => "Password Not Match"]);
        }
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return Admin::find($id);
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
        $user = Admin::find($id);
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
    public function destroy($id)
    {
        try {
            $user = Admin::find($id);
            $user->delete();
            return response()->json(['res' => 'ok', 'msg' => "Success"]);
        } catch (\Throwable $th) {
            return response()->json(['res' => "fail", 'msg' => "Admin Not Found"]);
        }
    }
}
