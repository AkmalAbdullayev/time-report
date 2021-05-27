<?php

namespace App\Http\Livewire;

use Livewire\Component;

class User extends Component
{
    protected $listeners = ["destroy"];
    public array $edit = [];

    public function render()
    {
        return view('livewire.user', [
            "users" => \App\Models\User::with("roles")->get(),
            "roles" => \Spatie\Permission\Models\Role::with("permissions")->get()
        ]);
    }

    public function edit($user)
    {
        $this->edit = $user;
        foreach ($this->edit["roles"] as $role) {
            $this->edit["roles"] = $role["id"];
        }
    }

    public function update()
    {
        $user = \App\Models\User::findOrFail($this->edit["id"]);
        $role = \Spatie\Permission\Models\Role::findOrFail($this->edit["roles"]);

        if ($user->roles->first() == null) {
            $user->assignRole($role);
        } else {
            $user->removeRole($user->roles->first()->name);
        }

        $user->name = $this->edit["name"];
        $user->assignRole($role);

        if ($user->isDirty()) {
            $user->save();

            $this->dispatchBrowserEvent("sweety:update", [
                "type" => "success",
                "title" => "Информация успешно обновлено!!!",
                "text" => ""
            ]);
        }

        $this->dispatchBrowserEvent("closeModal");
    }

    public function deleteConfirm($id)
    {
        $this->dispatchBrowserEvent("sweety:confirm-delete", [
            "type" => "warning",
            "title" => "Вы уверены?",
            "text" => "",
            "id" => $id
        ]);
    }

    public function destroy($id)
    {
        $user = \App\Models\User::findOrFail($id)->delete();

        if ($user) {
            $this->dispatchBrowserEvent("sweety:deleted", [
                "type" => "success",
                "title" => "Пользователь успешно удалено!!!",
                "text" => ""
            ]);
        }
    }
}
