<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreChapterRequest extends FormRequest
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
            'name' => ['required', 'string', 'max:255'],
            'position' => [
                'required',
                'integer',
                'min:1',
                Rule::unique('chapters')->where(function ($query) {
                    return $query->where('course_id', $this->route('course')->id);
                }),
            ],
        ];
    }
}
