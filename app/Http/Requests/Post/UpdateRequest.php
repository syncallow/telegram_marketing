<?php

namespace App\Http\Requests\Post;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateRequest extends FormRequest
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
            'image' => 'nullable|image',
            'description' => 'required|string',
            'frequency' => ['required', Rule::in(['daily', 'weekly', 'monthly'])],
            'enable_to_send' => 'required|boolean',
            'chat_ids' => 'required|array',
            'chat_ids.*' => 'integer|exists:chats,id'
        ];
    }
}
