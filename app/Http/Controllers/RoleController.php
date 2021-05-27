<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    protected Role $role;

    public function __construct(Role $role)
    {
        $this->role = $role;
    }

    public function index()
    {
        return view("fp.roles.index");
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        $role = $this->role->create([
            "name" => $request->input("role"),
        ]);
        $role->syncPermissions($request->input("permission"));

        return back()->with("success", "Роль создано успешно");
    }

    public function show(Role $role)
    {
        //
    }

    public function edit(Role $role)
    {
        //
    }

    public function update(Request $request, Role $role)
    {
        //
    }

    public function destroy(Role $role)
    {
        $this->role->findOrFail($role->id)->delete();

        return back()->with("success", "{$role["name"]} Удалено Успешно");
    }
}
