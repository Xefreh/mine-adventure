<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class ReorderChaptersRequest extends FormRequest
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
        return [
            'chapters' => ['required', 'array'],
            'chapters.*.id' => ['required', 'integer', 'exists:chapters,id'],
            'chapters.*.position' => ['required', 'integer', 'min:1'],
        ];
    }
}
