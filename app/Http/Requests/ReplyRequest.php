<?php

namespace App\Http\Requests;

class ReplyRequest extends Request
{
    public function rules()
    {
        return [
            'contents' => 'required|min:2',
        ];
    }

    public function messages()
    {
        return [

            'contents.required' => '内容不能为空',
            'contents.min' => '内容不能小于两个字',
        ];
    }
}
