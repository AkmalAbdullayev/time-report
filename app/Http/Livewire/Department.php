<?php

namespace App\Http\Livewire;

use App\Models\Company;
use Livewire\Component;

class Department extends Component
{
    public \App\Models\Department $model;

    public $department_id;
    public $department_name;
    public $company_id;
    protected $listeners = ["destroy"];

    public function mount(\App\Models\Department $department)
    {
        $this->model = $department;
    }

    public function render()
    {
        return view('livewire.department', [
            "companies" => Company::all(),
            "departments" => \App\Models\Department::all()
        ]);
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
        $res = $this->model->find($id);
        if ($res->positions->count() && $res->employees->count()) {
            session()->flash("message", "Ошибка удаление: Удалите связанные данные");
        } else {
            $isDeleted = $res->delete();
            if ($isDeleted) {
                $this->dispatchBrowserEvent("sweety:deleted", [
                    "type" => "success",
                    "title" => "Информация успешно удалено!!!",
                    "text" => ""
                ]);
            }
        }
        return back();
    }

    public function edit($id)
    {
        $department = $this->model->find($id);

        $this->department_id = $id;
        $this->department_name = $department->name;
        $this->company_id = $department->company_id;
    }

    public function cancel()
    {
        $this->reset("department_name");
    }

    public function update()
    {
        $this->validate([
            "department_name" => "required|string",
            "department_id" => "required",
            "company_id" => "required",
        ]);

        $department = $this->model->find($this->department_id);
        $isUpdated = $department->update([
            "name" => $this->department_name,
            "company_id" => $this->company_id,
        ]);

        if ($isUpdated) {
            $this->dispatchBrowserEvent("sweety:update", [
                "type" => "success",
                "title" => "Информация успешно обновлено!!!",
                "text" => ""
            ]);
        }

        $this->cancel();
        $this->dispatchBrowserEvent("closeModal");
    }
}
