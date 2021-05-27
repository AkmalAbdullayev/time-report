<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public User $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function index()
    {
        return view("fp.users.index");
    }

    public function store(Request $request)
    {
        $role = \Spatie\Permission\Models\Role::findOrFail($request->input("role"));

        $model = $this->user->firstOrCreate([
            "name" => $request->input("name"),
            "email" => $request->input("email"),
            "password" => Hash::make($request->input("password"))
        ]);

        $model->assignRole($role);

        return back()->with("success", "Добавлено Успешно");
    }

    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        //
    }

    public function update(Request $request, $id)
    {
        //
    }

    public function destroy($id)
    {
        //
    }
}
