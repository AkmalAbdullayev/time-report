<?php

namespace App\Http\Livewire;

use Livewire\Component;

class Permission extends Component
{
    public \Spatie\Permission\Models\Role $role;
    public \Spatie\Permission\Models\Permission $permission;
    public array $edit;
    protected $listeners = ["destroy"];

    public function mount(\Spatie\Permission\Models\Role $role, \Spatie\Permission\Models\Permission $permission)
    {
        $this->role = $role;
        $this->permission = $permission;
    }

    public function render()
    {
        return view('livewire.permission', [
            "roles" => \Spatie\Permission\Models\Role::all(),
            "permissions" => \Spatie\Permission\Models\Permission::with("roles")->get()
        ]);
    }

    public function edit($permission)
    {
        $this->edit = $permission;
    }

    public function update()
    {
        if (!empty($this->edit)) {
            $updatedPermission = $this->permission->findOrFail($this->edit["id"])->update([
                "name" => $this->edit["name"]
            ]);

            if ($updatedPermission) {
                $this->dispatchBrowserEvent("sweety:update", [
                    "type" => "success",
                    "title" => "Permission успешно обновлено!!!",
                    "text" => ""
                ]);
            }

            $this->dispatchBrowserEvent("closeModal");
        }
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
        if (isset($id)) {
            $isDeleted = $this->permission->findOrFail($id)->delete();

            if ($isDeleted) {
                $this->dispatchBrowserEvent("sweety:deleted", [
                    "type" => "success",
                    "title" => "Permission успешно удалено!!!",
                    "text" => ""
                ]);
            }
        }

    }
}
