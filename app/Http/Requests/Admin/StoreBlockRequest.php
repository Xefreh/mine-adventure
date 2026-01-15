<?php

namespace App\Http\Requests\Admin;

use App\Enums\BlockType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreBlockRequest extends FormRequest
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
            'type' => ['required', Rule::enum(BlockType::class)],
            'position' => ['required', 'integer', 'min:1'],
        ];

        // Add type-specific validation rules
        $type = $this->input('type');

        if ($type === BlockType::Video->value) {
            $rules['url'] = ['nullable', 'string', 'max:500'];
            $rules['duration'] = ['nullable', 'integer', 'min:0'];
        }

        if ($type === BlockType::Text->value) {
            $rules['content'] = ['nullable', 'string'];
        }

        if ($type === BlockType::Resources->value) {
            $rules['links'] = ['nullable', 'array'];
            $rules['links.*.title'] = ['required', 'string', 'max:255'];
            $rules['links.*.url'] = ['required', 'string', 'max:500'];
        }

        if ($type === BlockType::Assignment->value) {
            $rules['instructions'] = ['nullable', 'string'];
            $rules['starter_code'] = ['nullable', 'string'];
            $rules['language'] = ['nullable', 'string', 'max:50'];
            $rules['test_class_name'] = ['nullable', 'string', 'max:255'];
            $rules['test_file_content'] = ['nullable', 'string'];
        }

        return $rules;
    }
}
