<?php

declare(strict_types=1);

namespace App\Http\Requests\Article;

use App\Enums\ArticleStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class UpdateArticleRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, array<int, mixed>>
     */
    public function rules(): array
    {
        return [
            'title' => ['sometimes', 'string', 'max:255'],
            'category_id' => ['sometimes', 'integer', 'exists:categories,id'],
            'thumbnail' => ['sometimes', 'string', 'max:255'],
            'html' => ['sometimes', 'string'],
            'markdown' => ['sometimes', 'string'],
            'status' => ['sometimes', new Enum(ArticleStatus::class)],
            'tags' => ['sometimes', 'array'],
            'tags.*' => ['string'],
        ];
    }
} 