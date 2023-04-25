<?php

namespace App\Http\Requests\Post;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class PostRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Auth::check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        $image = $this->image;
        $rules = [
            'title' => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'description' => 'required|string|max:2000'
        ];

        if($image && (preg_match('/^https?:\/\/\w+(\.\w+)*(:[0-9]+)?(\/.*)?$/', $image) === 0)) {
            $rules['image'] = 'nullable|image64:jpeg,jpg,png';
        }
        return $rules;
    }

    public function messages()
    {
        return [
            'image.image64' => 'The image must be a file of type: jpeg, jpg, png.'
        ];
    }
}
