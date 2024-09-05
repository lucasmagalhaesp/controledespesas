<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Models\Expense;

class ExpensesController extends Controller
{
    /**
     * Listar despesas
     * @return JsonResponse - Retorna a listagem de despesas ou uma mensagem de erro
     */
    public function index() : JsonResponse
    {
        try{
            $expenses = Expense::all();
        }catch(\Exception $e){
            return response()->json(["status" => false, "message" => "Erro ao listar despesas"], 400);
        }

        return response()->json(["status" => true, "expenses" => $expenses], 200);
    }

    /**
     * Cadastra uma nova despesa
     * @param Request $request - Objeto que passará os dados para cadastro
     * @return JsonResponse - Retorna uma mensagem de acordo com o sucesso do registro
     */
    public function store(Request $request) : JsonResponse
    {
        try{
            Expense::create([
                "description"   => $request->description,
                "date"          => $request->date,
                "user_id"       => $request->user_id,
                "value"         => $request->value
            ]);
        }catch(\Exception $e){
            // return $e->getMessage();
            return response()->json(["status" => false, "message" => "Erro ao cadastrar despesa"], 400);
        }

        return response()->json(["status" => true, "message" => "Despesa cadastrada com sucesso"], 201);
    }

    /**
     * Pesquisa por uma despesa
     * @param int $id - Código da despesa
     * @return JsonResponse - Retorna os dados de uma despesa ou uma mensagem de erro 
     */
    public function show(int $id) : JsonResponse
    {
        if (is_null($id)) return response()->json(["status" => false, "message" => "O Código informado é inválido"], 400);
        try{
            $user = Expense::find($id);
            if (is_null($user)) return response()->json(["status" => false, "message" => "Despesa não encontrada"], 400);
        }catch(\Exception $e){
            return response()->json(["status" => false, "message" => "Erro ao cadastrar despesa"], 400);
        }

        return response()->json(["status" => true, "user" => $user], 200);
    }


    /**
     * Atualiza os dados da despesa
     * @param Request $request - Objeto que passará os dados para atualização
     * @param int $id - Código da despesa
     * @return JsonResponse - Retorna uma mensagem de acordo com o sucesso da atualização
     */
    public function update(Request $request, int $id) : JsonResponse
    {
        if (is_null($id)) return response()->json(["status" => false, "message" => "O Código informado é inválido"], 400);
        try{
            Expense::find($id)->update([
                "description"   => $request->description,
                "date"          => $request->date,
                "user_id"       => $request->user_id,
                "value"         => $request->value
            ]);
        }catch(\Exception $e){
            return response()->json(["status" => false, "message" => "Erro ao atualizar despesa"], 400);
        }

        return response()->json(["status" => true, "message" => "Despesa atualizada com sucesso"], 200);
    }

    /**
     * Exclui uma despesa
     * @param int $id - Código da despesa
     * @return JsonResponse - Retorna uma mensagem de acordo com o sucesso da exclusão
     */
    public function destroy(int $id) : JsonResponse
    {
        if (is_null($id)) return response()->json(["status" => false, "message" => "O Código informado é inválido"], 400);
        try{
            Expense::find($id)->delete();
        }catch(\Exception $e){
            return response()->json(["status" => false, "message" => "Erro ao excluir despesa"], 400);
        }

        return response()->json(["status" => true, "message" => "Despesa excluída com sucesso"], 200);
    }
}
