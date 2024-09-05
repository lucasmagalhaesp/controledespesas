<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Requests\UsersRequest;
use Illuminate\Support\Facades\Hash;

class UsersController extends Controller
{
    /**
     * Listar usuários
     * @return JsonResponse - Retorna a listagem de usuários ou uma mensagem de erro
     */
    public function index() : JsonResponse
    {
        try{
            $users = User::all();
        }catch(\Exception $e){
            return response()->json(["status" => false, "message" => "Erro ao listar usuários"], 400);
        }

        return response()->json(["status" => true, "users" => $users], 200);
    }

    /**
     * Cadastra um novo usuário
     * @param Request $request - Objeto que passará os dados para cadastro
     * @return JsonResponse - Retorna uma mensagem de acordo com o sucesso do registro
     */
    public function store(UsersRequest $request) : JsonResponse
    {
        try{
            User::create([
                "name"      => $request->name,
                "email"     => $request->email,
                "password"  => Hash::make($request->password)
            ]);
        }catch(\Exception $e){
            return response()->json(["status" => false, "message" => "Erro ao cadastrar usuário"], 400);
        }

        return response()->json(["status" => true, "message" => "Usuário cadastrado com sucesso"], 201);
    }

    /**
     * Pesquisa por um usuário
     * @param int $id - Código do usuário
     * @return JsonResponse - Retorna os dados do usuário ou uma mensagem de erro 
     */
    public function show(int $id) : JsonResponse
    {
        if (is_null($id)) return response()->json(["status" => false, "message" => "O Código informado é inválido"], 400);
        try{
            $user = User::find($id);
            if (is_null($user)) return response()->json(["status" => false, "message" => "Usuário não encontrado"], 400);
        }catch(\Exception $e){
            return response()->json(["status" => false, "message" => "Erro ao cadastrar usuário"], 400);
        }

        return response()->json(["status" => true, "user" => $user], 200);
    }


    /**
     * Atualiza os dados do usuário
     * @param Request $request - Objeto que passará os dados para atualização
     * @param int $id - Código do usuário
     * @return JsonResponse - Retorna uma mensagem de acordo com o sucesso da atualização
     */
    public function update(Request $request, int $id) : JsonResponse
    {
        if (is_null($id)) return response()->json(["status" => false, "message" => "O Código informado é inválido"], 400);
        try{
            User::find($id)->update([
                "name" => $request->name,
                "email" => $request->email,
                "password" => Hash::make($request->password)
            ]);
        }catch(\Exception $e){
            return response()->json(["status" => false, "message" => "Erro ao atualizar usuário"], 400);
        }

        return response()->json(["status" => true, "message" => "Usuário atualizado com sucesso"], 200);
    }

    /**
     * Exclui um usuário
     * @param int $id - Código do usuário
     * @return JsonResponse - Retorna uma mensagem de acordo com o sucesso da exclusão
     */
    public function destroy(int $id) : JsonResponse
    {
        if (is_null($id)) return response()->json(["status" => false, "message" => "O Código informado é inválido"], 400);
        try{
            User::find($id)->delete();
        }catch(\Exception $e){
            return response()->json(["status" => false, "message" => "Erro ao excluir usuário"], 400);
        }

        return response()->json(["status" => true, "message" => "Usuário excluído com sucesso"], 200);
    }
}
