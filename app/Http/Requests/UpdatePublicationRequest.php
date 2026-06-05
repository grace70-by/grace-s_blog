<?php

namespace App\Http\Requests;

use App\Models\PublicationBlock;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdatePublicationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->isAdmin() ?? false;
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'status' => ['required', Rule::in(['draft', 'published'])],
            'published_at' => ['nullable', 'date_format:Y-m-d'],
            'blocks' => ['nullable', 'array'],
            'blocks.*.type' => ['required', Rule::in(PublicationBlock::TYPES)],
            'blocks.*.content' => ['nullable', 'array'],
            'blocks.*.content.text' => ['nullable', 'string'],
            'blocks.*.content.caption' => ['nullable', 'string', 'max:500'],
            'blocks.*.content.url' => ['nullable', 'url', 'max:2000'],
            'blocks.*.file' => ['nullable', 'file', 'max:10240'],
            'blocks.*.existing_file_path' => ['nullable', 'string'],
            'tagged_users' => ['nullable', 'array'],
            'tagged_users.*' => ['exists:users,id'],
        ];
    }
}
