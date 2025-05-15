<?php

declare(strict_types=1);

namespace App\Http\Requests\Analytics;

use App\Enums\Period;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class GetTokenSentimentRequest extends FormRequest
{
    /**
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return string[]
     */
    public function rules(): array
    {
        return [
            'symbol' => [
                'required',
                'string',
                'max:10',
                'exists:tokens,symbol'
            ],
            'period' => [
                'required',
                'string',
                Rule::in(Period::toArray()),
            ],
        ];
    }
}
