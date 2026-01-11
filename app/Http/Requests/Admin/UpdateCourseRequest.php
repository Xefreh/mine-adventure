<?php

namespace App\Http\Requests\Admin;

use App\Enums\CourseDifficulty;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateCourseRequest extends FormRequest
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
            'name' => ['required', 'string', 'max:255', Rule::unique('courses', 'name')->ignore($this->route('course'))],
            'thumbnail' => ['required', 'string', 'max:500'],
            'difficulty' => ['required', Rule::enum(CourseDifficulty::class)],
        ];
    }
}
