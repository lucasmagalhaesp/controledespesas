<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class ExpensesRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            "status"    => false,
            "errors"     => $validator->errors(),
        ], 422));
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            "description"   => "required|max:191",
            "date"          => "required|date|before_or_equal:".date("Y-m-d"),
            "user_id"       => "required|exists:users,id",
            "value"         => "required|gte:0",
        ];
    }

    public function messages(): array
    {
        return [
            "description.required"  => "Descrição da despesa não informada",
            "description.max"       => "A descrição da despesa deve ter no máximo 191 caracteres",
            "date.required"         => "Data da despesa não informada",
            "date.date"             => "A data da despesa é inválida",
            "date.before_or_equal"  => "A data da despesa deve ser de hoje ou de dias anteriores",
            "user_id.required"      => "Código do usuário não informado",
            "user_id.exists"        => "Usuário inexistente",
            "value.required"        => "Valor da despesa não informado",
            "value.gte"             => "Valor negativo informado para a despesa",
        ];
    }
}
