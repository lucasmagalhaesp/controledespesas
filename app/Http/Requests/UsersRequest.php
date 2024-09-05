<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class UsersRequest extends FormRequest
{
     /**
     * Informa se a validação está ativa ou não
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Função que retornará os erros, caso a validação os acuse
     */
    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            "status"    => false,
            "errors"     => $validator->errors(),
        ], 422));
    }

   /**
     * Função onde as regras de validação são inseridas
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            "name"      => "required|min:2",
            "email"     => "required|email|unique:users",
            "password"  => "required|min:6",
        ];
    }

    /**
     * Função para retornar as mensagens personalizadas para cada possível erro
     */
    public function messages() : array
    {
        return [
            "name.required"     => "Nome do usuário não informado",
            "name.min"          => "O nome do usuário deve ter pelo menos 2 caracteres",
            "email.required"    => "E-mail do usuário não informado",
            "email.email"       => "E-mail inválido",
            "email.unique"      => "Esse e-mail já foi cadastrado por outro usuário",
            "password.required" => "Senha do usuário não informada",
            "password.min"      => "A senha do usuário deve ter pelos menos 6 caracteres"
        ];
    }
}
