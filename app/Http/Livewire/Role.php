<?php

namespace App\Http\Livewire;

use Livewire\Component;

class Role extends Component
{
    public \Spatie\Permission\Models\Role $role;
    public array $edit = [];
    public array $permission_ids = [];
    public string $action_name;
    public ?int $role_id = null;
    protected $listeners = ["destroy"];

    public function mount(\Spatie\Permission\Models\Role $role)
    {
        $this->role = $role;
    }

    public function render()
    {
        $this->dispatchBrowserEvent("select2", [
            "id" => "select2"
        ]);

        return view('livewire.roles.role', [
            "roles" => \Spatie\Permission\Models\Role::withCount("permissions")->get(),
            "permissions" => \Spatie\Permission\Models\Permission::all()
        ]);
    }

    public function edit($role)
    {
        $this->edit = $role;
        foreach ($this->edit["permissions"] as $permission) {
            $this->edit["permissions"][$permission["id"]] = $permission["id"];
        }
    }

    public function update()
    {
        $role = $this->role->findOrFail($this->edit["id"]);
        $role->name = $this->edit["name"];

        if ($role->isDirty()) {
            $role->save();

            $this->dispatchBrowserEvent("sweety:updated", [
                "type" => "success",
                "title" => "Информация обновлено!!!",
                "text" => ""
            ]);
        }
        if (!empty($this->permission_ids)) {
            $permission = \Spatie\Permission\Models\Permission::findOrFail($this->permission_ids);
            $role->givePermissionTo($permission);
        }

        $this->dispatchBrowserEvent("closeModal");
    }

    public function deleteConfirm($id, $action_name, $role_id = null)
    {
        $this->action_name = $action_name;
        $this->role_id = $role_id;
        $this->dispatchBrowserEvent("sweety:confirm-delete", [
            "type" => "warning",
            "title" => "Вы уверены?",
            "text" => "",
            "id" => $id,
        ]);
    }

    public function destroy($id)
    {
        switch ($this->action_name) {
            case 'permission':
                $permission = \Spatie\Permission\Models\Permission::findOrFail($id);
                $role = $this->role->findOrFail($this->role_id);
                $role->revokePermissionTo($permission);
                break;
            case 'role':
                $isDeleted = $this->role->findOrFail($id)->delete();

                if ($isDeleted) {
                    $this->dispatchBrowserEvent("sweety:deleted", [
                        "type" => "success",
                        "title" => "Permission успешно удалено!!!",
                        "text" => ""
                    ]);
                }
                break;
        }
    }
}
