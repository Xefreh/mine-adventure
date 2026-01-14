<?php

namespace App\Http\Requests\Admin;

use App\Enums\BlockType;
use Illuminate\Foundation\Http\FormRequest;

class UpdateBlockRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        $rules = [
            'position' => ['sometimes', 'integer', 'min:1'],
        ];

        // Get the block type from the route model binding
        $block = $this->route('block');
        $type = $block?->type;

        if ($type === BlockType::Video) {
            $rules['url'] = ['sometimes', 'string', 'max:500'];
            $rules['duration'] = ['nullable', 'integer', 'min:0'];
        }

        if ($type === BlockType::Text) {
            $rules['content'] = ['sometimes', 'string'];
        }

        if ($type === BlockType::Resources) {
            $rules['links'] = ['sometimes', 'array'];
            $rules['links.*.title'] = ['required', 'string', 'max:255'];
            $rules['links.*.url'] = ['required', 'string', 'max:500'];
        }

        if ($type === BlockType::Assignment) {
            $rules['instructions'] = ['sometimes', 'string'];
            $rules['starter_code'] = ['nullable', 'string'];
            $rules['language'] = ['sometimes', 'string', 'max:50'];
            $rules['test_class_name'] = ['nullable', 'string', 'max:255'];
            $rules['test_file_content'] = ['nullable', 'string'];
        }

        return $rules;
    }
}
