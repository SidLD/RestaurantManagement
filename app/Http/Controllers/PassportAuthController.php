<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class PassportAuthController extends Controller
{
    /**
     * Registration
     */
    public function register(Request $request)
    {
        $this->validate($request, [
            'first_name' => 'required|min:3',
            'last_name' => 'required|min:3',
            'email' => 'required|email',
            'password' => 'required|min:8',
            'role_id' => 'required'
        ]);
 
        $user = User::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'role_id' => $request->role_id,
            'email' => $request->email,
            'password' => Hash::make($request->password)
        ]);
       
        $token = $user->createToken('LaravelAuthApp')->accessToken;
 
        return response()->json(['token' => $token], 200);
    }
 
    /**
     * Login
     */
    public function login(Request $request)
    {
        $data = [
            'email' => $request->email,
            'password' => $request->password
        ];
        
        if (auth()->attempt($data)) {
            $user = User::where('email' , $request->email)->first();
            if($user->role->name === "admin"){
                $token = auth()->user()->createToken('LaravelAuthApp')->accessToken;
                return response()->json(['token' => $token], 200);
            }else {
                return response()->json(['error' => 'Unauthorised User'], 401);
            }
        } else {
            return response()->json(['error' => 'Unauthorised'], 401);
    }
    }   
}