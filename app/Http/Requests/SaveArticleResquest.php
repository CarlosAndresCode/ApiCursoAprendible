<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SaveArticleResquest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        switch ($this->method()){
            case 'POST':{
                return [
                    'title' => ['required'],
                    'slug' => ['required', 'unique:articles,slug'],
                    'content' => ['required'],
                ];

            }
            case 'PUT':{
                return [
                    'title' => ['required'],
                    'slug' => ['required', 'unique:articles,slug,'.$this->article->id],
                    'content' => ['required'],
                ];
            }
        }
        return [];
    }
}
