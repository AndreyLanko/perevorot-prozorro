<?php namespace App\Http\Requests;

use App\Http\Requests\Request;

class FeedbackRequest extends Request
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $rules=[
            'name' => 'required|regex:/^[(\w\s)]+$/u',
            'email' => 'required|email',
            'phone' => 'regex:(38\d{10})',
            'g-recaptcha-response' => 'required|recaptcha',
        ];
        
        if(env('APP_DEBUG'))
            unset($rules['g-recaptcha-response']);

        return $rules;
    }
}