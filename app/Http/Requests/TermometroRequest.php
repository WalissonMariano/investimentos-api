<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TermometroRequest extends FormRequest
{

    public function authorize()
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'valor' => ['required', 'numeric', 'gt:0'],
            'dataInicio' => ['required', 'date', 'date_format:Y-m-d'],
            'dataFim' => ['required', 'date', 'after_or_equal:dataInicio', 'date_format:Y-m-d'],
        ];
    }

    public function messages(): array
    {
        return [
            'valor.required' => 'O valor é obrigatório.',
            'valor.numeric' => 'O valor deve ser numérico.',
            'valor.gt' => 'O valor deve ser maior que zero.',
            'dataInicio.required' => 'A data de início é obrigatória.',
            'dataInicio.date' => 'A data de início é inválida.',
            'dataInicio.date_format' => 'A data de início deve estar no formato YYYY-MM-DD.',
            'dataFim.required' => 'A data de fim é obrigatória.',
            'dataFim.date' => 'A data de fim é inválida.',
            'dataFim.date_format' => 'A data de fim deve estar no formato YYYY-MM-DD.',
            'dataFim.after_or_equal' => 'A data de fim deve ser igual ou posterior à data de início.',
        ];
    }
}
