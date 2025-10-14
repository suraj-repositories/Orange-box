<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProjectModuleRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'type' => 'required|exists:project_module_types,id',
            'description' => 'nullable|string|max:3000',
            'user' => 'nullable|array',
            'user.*' => 'exists:users,id',
            'media_files' => 'nullable|array',
            'media_files.*' => 'file|max:' . config('validation_rules.max_file_size'),
            'project_board' => 'nullable|exists:project_boards,id',
            'start_date' => [
                'nullable',
                'date',
                function ($attribute, $value, $fail) {
                    $endDate = $this->input('end_date');
                    if ($endDate && $value > $endDate) {
                        $fail('The start date must be before or equal to the end date.');
                    }
                },
            ],
            'end_date' => [
                'nullable',
                'date',
                function ($attribute, $value, $fail) {
                    $startDate = $this->input('start_date');
                    if ($startDate && $value < $startDate) {
                        $fail('The end date must be after or equal to the start date.');
                    }
                },
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'The module name is required.',
            'type.required' => 'Please select a valid module type.',
            'type.exists' => 'The selected module type is invalid.',
            'user.*.exists' => 'One or more selected users are invalid.',
            'media_files.*.file' => 'Each uploaded file must be a valid file.',
            'media_files.*.max' => 'Each uploaded file must not exceed the allowed size limit.',
        ];
    }
}
