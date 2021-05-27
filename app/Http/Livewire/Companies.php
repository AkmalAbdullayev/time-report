<?php

namespace App\Http\Livewire;

use App\Models\Company;
use Livewire\Component;

class Companies extends Component
{
    public $companies, $departments, $department_id, $model, $create_name, $edit_name;
    protected $listeners = ["destroy"];

    ## states
    public string $tab = "company";

    ##

    public function mount(Company $model)
    {
        $this->model = $model;
    }

    public function render()
    {
        $this->companies = Company::all();
        return view('livewire.companies');
    }

    public function store()
    {
        $messages = [
            "create_name.required" => "Field is required"
        ];

        $this->validate([
            "create_name" => "required|string"
        ], $messages);

        $this->model->create([
            "name" => $this->create_name
        ]);

        $this->dispatchBrowserEvent("sweety:create", [
            "type" => "success",
            "title" => "Информация успешно добавлено!",
            "text" => ""
        ]);

        $this->create_name = "";

        return back();
    }

    public function cancel()
    {
        $this->reset("edit_name");
    }

    public function edit($id)
    {
        $department = $this->model->where("id", $id)->first();
        $this->department_id = $department->id;
        $this->edit_name = $department->name;
    }

    public function update()
    {
        $this->validate([
            "edit_name" => "required|string"
        ]);

        if (isset($this->department_id)) {
            $department = $this->model->find($this->department_id);
            $isUpdated = $department->update([
                "name" => $this->edit_name
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

        return back();
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
            $isDeleted = $this->model->where("id", $id)->delete();

            if ($isDeleted) {
                $this->dispatchBrowserEvent("sweety:deleted", [
                    "type" => "success",
                    "title" => "Информация успешно удалено!!!",
                    "text" => ""
                ]);
            }
        }
    }
}
