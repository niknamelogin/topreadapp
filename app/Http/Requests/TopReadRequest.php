<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TopReadRequest extends FormRequest {
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool {
        // return false;
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array {
        return [
            'author' => 'nullable|string',
            'isbn'   => 'nullable|array',
            'isbn.*' => 'string',
            'title'  => 'nullable|string',
            'offset' => 'nullable|integer|min:0',
        ];
    }
}
