<?php

namespace Tests\Feature;

use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;
use App\Models\User;
use App\Models\Expense;
use Illuminate\Support\Str;

class ExpensesControllerTest extends TestCase
{
    private array $userData = [
        "id" => null,
        "token" => null
    ];

    /**
     * Busca um usuário no banco de dados e armazena o id e o token em atributos
     */
    private function getUser() : void
    {
        $user = User::first();
        $this->userData["id"] = $user->id;
        $this->userData["token"] = $this->createToken($user);
    }

    /**
     * Cria o token para o usuário
     */
    private function createToken(User $user) : string
    {
        return $user->createToken("api_despesas_".Str::random(2))->plainTextToken;
    }

    /**
     * Exclui os tokens atribuídos ao usuário de teste
     */
    private function deleteToken() : void
    {
        User::first()->tokens()->delete();
    }

    /**
     * Buscar todos as despesas
     */
    public function testShowExpenses() : void
    {
        $this->getUser();

        $response = $this->withHeader("Authorization", "Bearer " . $this->userData["token"])->getJson("/api/expenses/");
        $response->assertStatus(200);
        $response->assertJson(function (AssertableJson $json){
            $json->hasAll(["expenses.0.id", "expenses.0.description", "expenses.0.date", "expenses.0.user_id", "expenses.0.value", "expenses.0.created_at", "expenses.0.updated_at"]);
            $json->whereAllType([
                "status"                    => "boolean",
                "expenses.0.id"             => "integer",
                "expenses.0.description"    => "string",
                "expenses.0.date"           => "string",
                "expenses.0.user_id"        => "integer",
                "expenses.0.value"          => "double",
            ]);
        });

        $this->deleteToken();
    }

     /**
     * Buscar uma despesa
     */
    public function testShowExpense() : void
    {
        $this->getUser();

        $expenseID = Expense::where("user_id", $this->userData["id"])->first()->id;
        $response = $this->withHeader("Authorization", "Bearer " . $this->userData["token"])->getJson("/api/expenses/".$expenseID);
        $response->assertStatus(200);
        $response->assertJson(function (AssertableJson $json){
            $json->hasAll(["expense.id", "expense.description", "expense.date", "expense.user_id", "expense.value", "expense.created_at", "expense.updated_at"]);
            $json->whereAllType([
                "status"                => "boolean",
                "expense.id"            => "integer",
                "expense.description"   => "string",
                "expense.date"          => "string",
                "expense.user_id"       => "integer",
                "expense.value"         => "double",
            ]);
        });

        $this->deleteToken();
    }

     /**
     * Inserir uma despesa
     */
    public function testCreateExpense() : void
    {
        $this->getUser();
        $expense = Expense::factory()->make()->toArray();

        $response = $this->withHeader("Authorization", "Bearer " . $this->userData["token"])->postJson("/api/expenses/", $expense);
        $response->assertStatus(201);
        $this->assertTrue($response["status"]);

        $this->deleteToken();
    }

     /**
     * Atualizar uma despesa
     */
    public function testUpdateExpense() : void
    {
        $this->getUser();

        $expense = Expense::latest()->first();
        $updateData = [
            "description"   => $expense->description. " (editada)",
            "date"          => date("Y-m-d", strtotime($expense->date. " + 1 day")),
            "user_id"       => $this->userData["id"],
            "value"         => ++$expense->value
        ];

        $response = $this->withHeader("Authorization", "Bearer " . $this->userData["token"])->putJson("/api/expenses/".$expense->id, $updateData);
        $response->assertStatus(200);
        $this->assertTrue($response["status"]);

        $this->deleteToken();
    }

     /**
     * Excluir um usuário
     */
    public function testDeleteUser() : void
    {
        $this->getUser();

        $expense = Expense::latest()->first();
        $response = $this->withHeader("Authorization", "Bearer " . $this->userData["token"])->deleteJson("/api/expenses/".$expense->id);
        $response->assertStatus(200);
        $this->assertTrue($response["status"]);
        
        $this->deleteToken();
    }
}
