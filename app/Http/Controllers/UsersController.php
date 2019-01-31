<?php

namespace App\Http\Controllers;

use App\Handlers\ImageUploadHandler;
use App\Models\User;
use App\Http\Requests\UserRequest;

class UsersController extends Controller
{
    //

    public function show(User $user)
    {
        return view('users.show',compact('user'));
    }

    public function edit(User $user)
    {
        return view('users.edit',compact('user'));
    }

    public function update(User $user,UserRequest $userRequest,ImageUploadHandler $imageUpload)
    {
        $data = $userRequest->all();

        if($userRequest->avatar)
        {
           $request = $imageUpload->save($userRequest->avatar,'avatars',$user->id,362);

            if($request)
            {
                $data['avatar'] = $request['path'];
            }
        }

        $user->update($data);

        return redirect()->route('users.show',$user->id)->with('success','个人资料更新成功!');
    }

}
