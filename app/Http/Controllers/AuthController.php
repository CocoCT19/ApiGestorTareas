<?php
namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $request->validate([
           'name'=>'required|string|max:255',
           'email'=>'required|email|unique:users',
           'password'=>'required|string|confirmed'
        ]);

        $user = User::create([
           'name'=>$request->name,
           'email'=>$request->email,
           'password'=>Hash::make($request->password),
        ]);

        return response()->json(['message'=>'Usuario registrado'], 201);
    }

    public function login(Request $request)
    {
        $request->validate(['email'=>'required|email','password'=>'required']);

        $user = User::where('email',$request->email)->first();
        if (! $user || ! Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['Credenciales incorrectas.']
            ]);
        }

        $token = $user->createToken('api_token')->plainTextToken;

        return response()->json(['token'=>$token,'user'=>$user], 200);
    }

    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();
        return response()->json(['message'=>'Logged out'], 200);
    }
}
