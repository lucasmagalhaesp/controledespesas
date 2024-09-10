<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;
use App\Models\User;
use Illuminate\Support\Str;

class UsersControllerTest extends TestCase
{
    /**
     * Buscar todos os usuários
     */
    public function testShowUsers() : void
    {
        $response = $this->getJson("/api/users/");
        $response->assertStatus(200);
        $response->assertJson(function (AssertableJson $json){
            $json->hasAll(["users.0.id", "users.0.name", "users.0.email", "users.0.created_at", "users.0.updated_at"]);
            $json->whereAllType([
                "status"        => "boolean",
                "users.0.id"    => "integer",
                "users.0.name"  => "string",
                "users.0.email" => "string"
            ]);
        });
    }

     /**
     * Buscar um usuário
     */
    public function testShowUser() : void
    {
        $userID = User::first()->id;
        $response = $this->getJson("/api/users/".$userID);
        $response->assertStatus(200);
        $response->assertJson(function (AssertableJson $json){
            $json->hasAll(["user.id", "user.name", "user.email", "user.created_at", "user.updated_at"]);
            $json->whereAllType([
                "status"        => "boolean",
                "user.id"       => "integer",
                "user.name"     => "string",
                "user.email"    => "string"
            ]);
        });
    }

     /**
     * Inserir um usuário
     */
    public function testCreateUser() : void
    {
        $user = User::factory()->make()->toArray();
        $user["password"] = Str::random(6);

        $response = $this->postJson("/api/users/", $user);
        $response->assertStatus(201);
        $this->assertTrue($response["status"]);
    }

     /**
     * Atualizar um usuário
     */
    public function testUpdateUser() : void
    {
        $user = User::latest()->first();
        $updateData = [
            "name"      => $user->name." dos Santos",
            "email"     => $user->email.".teste",
            "password"  => Str::random(6)
        ];

        $response = $this->putJson("/api/users/".$user->id, $updateData);
        $response->assertStatus(200);
        $this->assertTrue($response["status"]);
    }

     /**
     * Excluir um usuário
     */
    public function testDeleteUser() : void
    {
        $user = User::latest()->first();
        $response = $this->deleteJson("/api/users/".$user->id);
        $response->assertStatus(200);
        $this->assertTrue($response["status"]);
    }

}
