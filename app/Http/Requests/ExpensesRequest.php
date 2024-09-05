<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class ExpensesRequest extends FormRequest
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
            "description"   => "required|max:191",
            "date"          => "required|date|before_or_equal:".date("Y-m-d"),
            "value"         => "required|gte:0",
        ];
    }

    /**
     * Função para retornar as mensagens personalizadas para cada possível erro
     */
    public function messages(): array
    {
        return [
            "description.required"  => "Descrição da despesa não informada",
            "description.max"       => "A descrição da despesa deve ter no máximo 191 caracteres",
            "date.required"         => "Data da despesa não informada",
            "date.date"             => "A data da despesa é inválida",
            "date.before_or_equal"  => "A data da despesa deve ser de hoje ou de dias anteriores",
            "value.required"        => "Valor da despesa não informado",
            "value.gte"             => "Valor negativo informado para a despesa",
        ];
    }
}
