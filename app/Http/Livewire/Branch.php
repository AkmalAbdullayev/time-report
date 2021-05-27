<?php

namespace App\Http\Livewire;

use App\Models\Company;
use Livewire\Component;

class Branch extends Component
{
    public array $create = [];
    public $model, $edit_name, $position_id;
    protected $listeners = ["destroy"];

    public function mount(\App\Models\Branch $branch)
    {
        $this->model = $branch;
    }

    public function render()
    {
        return view('livewire.branch', [
            "branches" => \App\Models\Branch::all(),
            "companies" => Company::all()
        ]);
    }

    public function cancel()
    {
        $this->reset("edit_name");
    }

    public function edit($id)
    {
        $position = $this->model->where("id", $id)->first();
        $this->position_id = $position->id;
        $this->edit_name = $position->name;
    }

    public function update()
    {
        $this->validate([
            "edit_name" => "required|string"
        ]);

        if (isset($this->position_id)) {
            $position = $this->model->find($this->position_id);
            $isUpdated = $position->update([
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
            session()->flash("message", "Удалено Успешно");
        }
    }
}
