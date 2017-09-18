<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Validator;

class UserController extends Controller
{
    public function create()
    {
        return view('user.create');
    }

    public function store(Request $request)
    {
        $user_data = $request->all();

        $user = [];
        $user['username'] = $user_data['username'];
        $user['password'] = $user_data['password'];
        $user['email'] = $user_data['email'];
        $user['profile'] = $user_data['profile'];
        $user['status'] = "user";

        if($this->checkData($user) === "pass")
        {
            User::created($user);

            return "register successfully";
        }
        else
        {
            return "register fails";
        }
    }

    public function edit($id)
    {
        $user = User::where('id', $id)->get()->first();

        return view('user.update')->with('user', $user);
    }

    public function update($request)
    {
        $user_data = $request->all();

        $user = [];
        $user['username'] = $user_data['username'];
        $user['password'] = $user_data['password'];
        $user['email'] = $user_data['email'];
        $user['profile'] = $user_data['profile'];

        if($this->checkData($user) === "pass")
        {
            User::where('username', $user['username'])->update('password', md5($user['password']),
                'email', $user['email'], 'profile', $user['profile']);

            return "update successfully";
        }
        else
        {
            return "fails";
        }
    }

    public function delete($id)
    {
        User::where('id', $id)->delete();

        return "delete successfully";
    }

    public function checkData($user)
    {
        $rule = array
        (
            'username' => 'required|unique:users|min:6|max:16',
            'password' => 'required|min:6|max:80',
            'confirm_password' => 'min:6|max:80|same:password',
            'email' => 'required|unique:users,email',
        );

        $validator = Validator::make($user, $rule);

        if($validator->fails())
        {
            return "fails";
        }
        else
        {
            return "pass";
        }
    }

}
