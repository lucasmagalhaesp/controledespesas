<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login(Request $request)
    {
       /*  $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]); */
 
        try{
            if (!Auth::attempt(["email" => $request->email, "password" => $request->password])) 
                return response()->json(["status" => false, "message" => "Usuário ou senha inválido"], 400);
    
            $token = $request->user()->createToken("api_despesas")->plainTextToken;
        }catch(\Exception $e){
            return response()->json(["status" => false, "message" => "Erro ao fazer login"], 400);
        }

        return response()->json(["status" => true, "token" => $token, "user" => Auth::user()], 200);
    }

    public function logout(Request $request)
    {
        try{
            $request->user()->tokens()->delete();
        }catch(\Exception $e){
            return response()->json(["status" => false, "message" => "Erro ao fazer o logout"], 400);
        }

        return response()->json(["status" => true, "message" => "Logout efetuado com sucesso"], 200);
    }
}
