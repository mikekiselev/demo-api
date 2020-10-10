<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PostRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [
            'title' => 'required|string|unique:posts,title',
            'tags' => 'array|required_with:tags:*.id'
        ];

        switch ($this->getMethod()) {
            case 'POST':
                return $rules;
            case 'PUT':
                return [
                        'id' => 'required|integer|exists:posts,id',
                        'title' => [
                            'required',
                            Rule::unique('posts')->ignore($this->title, 'title')
                        ]
                    ] + $rules;
            // case 'PATCH':
            case 'DELETE':
                return [
                    'id' => 'required|integer|exists:posts,id'
                ];
        }
    }
}
