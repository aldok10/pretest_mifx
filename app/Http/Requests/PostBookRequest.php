<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PostBookRequest extends FormRequest
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
        // @TODO implement
        //Validation Request POST from Book
        return [
            'isbn' => ['regex:/^[0-9]+$/',"min:13",'max:13','unique:App\Book,isbn'],
            'title' => ['string'],
            'description' => ['string'],
            'authors' => ['array','min:1'],
            'authors.*' => ['exists:authors,id','numeric'],
            'published_year' => ['integer','between:1900,2020']
        ];
    }
}
