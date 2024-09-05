<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\ExpensesRequest;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Models\Expense;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use App\Notifications\ExpenseRecordNotification;
use Illuminate\Support\Facades\Notification;

class ExpensesController extends Controller
{
    /**
     * Listar despesas
     * @return JsonResponse - Retorna a listagem de despesas do usuário logado ou uma mensagem de erro
     */
    public function index() : JsonResponse
    {
        try{
            //retorna apenas as depesas do usuário logado
            $expenses = Auth::user()->expenses;
        }catch(\Exception $e){
            return response()->json(["status" => false, "message" => "Erro ao listar despesas"], 400);
        }

        return response()->json(["status" => true, "expenses" => $expenses], 200);
    }

    /**
     * Cadastra uma nova despesa
     * @param ExpensesRequest $request - Objeto que faz a validação do cadastro e passa os dados para cadastro
     * @return JsonResponse - Retorna uma mensagem de acordo com o sucesso do registro
     */
    public function store(ExpensesRequest $request) : JsonResponse
    {
        try{
            Expense::create([
                "description"   => $request->description,
                "date"          => $request->date,
                "user_id"       => Auth::user()->id,
                "value"         => $request->value
            ]);
            
            //enviando o e-mail de confirmação do cadastro da despesa
            $this->sendEmail();

        }catch(\Exception $e){
            return response()->json(["status" => false, "message" => "Erro ao cadastrar despesa"], 400);
        }

        return response()->json(["status" => true, "message" => "Despesa cadastrada com sucesso"], 201);
    }

    /**
     * Envia um e-mail para o usuário após o mesmo cadastrar uma despesa
     * @return void
     */
    private function sendEmail() : void
    {
        $user = User::find(Auth::user()->id);
        $user->notify(new ExpenseRecordNotification(Auth::user()));
    }

    /**
     * Pesquisa por uma despesa
     * @param Request $request - Objeto que chamará a Policy para verificar se o usuário tem permissão de acesso
     * @param int $id - Código da despesa
     * @return JsonResponse - Retorna os dados de uma despesa ou uma mensagem de erro 
     */
    public function show(Request $request, int $id) : JsonResponse
    {
        if (is_null($id)) return response()->json(["status" => false, "message" => "O Código informado é inválido"], 400);
        try{
            $expense = Expense::find($id);
            if (is_null($expense)) return response()->json(["status" => false, "message" => "Despesa não encontrada"], 400);

            if ($request->user()->cannot("view", $expense)) {
                return response()->json(["status" => false, "message" => "Não é possível exibir despesa de outro usuário"], 400);
            }
        }catch(\Exception $e){
            return response()->json(["status" => false, "message" => "Erro ao cadastrar despesa"], 400);
        }

        return response()->json(["status" => true, "expense" => $expense], 200);
    }


    /**
     * Atualiza os dados da despesa
     * @param Request $request - Objeto que chamará a Policy para verificar se o usuário tem permissão de acesso
     * e passar os dados para a atualização da despesa
     * @param int $id - Código da despesa
     * @return JsonResponse - Retorna uma mensagem de acordo com o sucesso da atualização
     */
    public function update(Request $request, int $id) : JsonResponse
    {
        $expense = Expense::find($id);
        if (is_null($expense)) 
            return response()->json(["status" => false, "message" => "Despesa não cadastrada"], 400);

        if ($request->user()->cannot("update", $expense)) 
            return response()->json(["status" => false, "message" => "Não é possível atualizar despesa de outro usuário"], 400);

        if (is_null($id)) return response()->json(["status" => false, "message" => "O Código informado é inválido"], 400);
        try{
            $expense->update([
                "description"   => $request->description,
                "date"          => $request->date,
                "value"         => $request->value
            ]);
        }catch(\Exception $e){
            return response()->json(["status" => false, "message" => "Erro ao atualizar despesa"], 400);
        }

        return response()->json(["status" => true, "message" => "Despesa atualizada com sucesso"], 200);
    }

    /**
     * Exclui uma despesa
     * @param Request $request - Objeto que chamará a Policy para verificar se o usuário tem permissão de acesso
     * @param int $id - Código da despesa
     * @return JsonResponse - Retorna uma mensagem de acordo com o sucesso da exclusão
     */
    public function destroy(Request $request, int $id) : JsonResponse
    {
        $expense = Expense::find($id);
        if ($request->user()->cannot("delete", $expense)) 
            return response()->json(["status" => false, "message" => "Não é possível excluir despesa de outro usuário"], 400);
        
        if (is_null($id)) return response()->json(["status" => false, "message" => "O Código informado é inválido"], 400);
        try{
            $expense->delete();
        }catch(\Exception $e){
            return response()->json(["status" => false, "message" => "Erro ao excluir despesa"], 400);
        }

        return response()->json(["status" => true, "message" => "Despesa excluída com sucesso"], 200);
    }
}
