<?php

namespace App\Http\Livewire;

use App\Models\Company;
use Livewire\Component;

class Position extends Component
{
    public \App\Models\Position $model;
    public $department_id = null;
    public $model_id;
    protected $listeners = ["destroy"];
    protected $rules = [
        "model.name" => "required|string",
        "model.department_id" => "required"
    ];

    public function mount(\App\Models\Position $position)
    {
        $this->model = $position;
    }

    public function render()
    {
        return view('livewire.position', [
            "companies" => Company::all(),
            "departments" => \App\Models\Department::all(),
            "positions" => \App\Models\Position::with("departments")->get()
        ]);
    }

    public function cancel()
    {
        $this->reset(["department_id", "model_id"]);
    }

    public function edit($id)
    {
        $position = $this->model->findOrFail($id);

        $this->model_id = $position->id;
        $this->model->name = $position->name;
    }

    public function update()
    {
        $position = $this->model->findOrFail($this->model_id);

        $isUpdated = $position->update([
            "name" => $this->model->name,
            "department_id" => $this->department_id ?? $position->department_id
        ]);

        if ($isUpdated) {
            $this->dispatchBrowserEvent("sweety:update", [
                "type" => "success",
                "title" => "Информация успешно обновлено!!!",
                "text" => ""
            ]);
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
