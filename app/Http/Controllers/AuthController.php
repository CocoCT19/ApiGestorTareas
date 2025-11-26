<?php
namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:6'
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        return response()->json(['message' => 'Usuario registrado'], 201);
    }

    public function login(Request $request)
    {
        $request->validate(['email'=>'required|email','password'=>'required']);

        $user = User::where('email', $request->email)->first();

        if (! $user || ! Hash::check($request->password, $user->password)) {
            return response()->json(['message' => 'Credenciales incorrectas'], 401);
        }

        $token = $user->createToken('api_token')->plainTextToken;

        return response()->json(['token' => $token], 200);
    }

    public function logout(Request $request)
    {
        // Revoca todos los tokens del usuario
        $request->user()->tokens()->delete();
        return response()->json(['message' => 'Tokens revocados'], 200);
    }
}
