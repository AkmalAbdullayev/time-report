<?php

namespace App\Http\Livewire;

use Livewire\Component;

class Schedule extends Component
{
    public \App\Models\Schedule $schedule;
    public array $property = [];
    public array $edit = [];
    public array $flexWorkHour = [];
    protected $listeners = ["destroy"];

    ## VALIDATION ##
    protected $rules = [
        "property.name" => "required|string",
        "property.range_from" => "required|string",
        "property.range_to" => "required|string",
        "property.description" => "string|nullable",
        "property.type" => "nullable",
    ];

    protected array $flexRules = [
        "flexWorkHour.name" => "required|string"
    ];

    protected $messages = [
        "property.name.required" => "Название графика не должно быть пустым.",
        "property.range_from.required" => "Интервал не должно быть пустым.",
        "property.range_to.required" => "Интервал не должно быть пустым.",

        "flexWorkHour.name.required" => "Название графика не должно быть пустым."
    ];

    public function mount(\App\Models\Schedule $schedule)
    {
        $this->schedule = $schedule;
    }

    public function render()
    {
        return view('livewire.schedule.schedule', [
            "schedules" => $this->schedule::all()
        ]);
    }

    public function store()
    {
        $this->validate($this->rules);

        $is_created = $this->schedule->create([
            "name" => $this->property["name"],
            "range_from" => $this->property["range_from"],
            "range_to" => $this->property["range_to"],
            "description" => $this->property["description"]
        ]);

        $this->reset("property");

        if ($is_created) {
            $this->dispatchBrowserEvent("sweety:create", [
                "type" => "success",
                "title" => "График Добавлен Успешно",
                "text" => ""
            ]);
        }
    }

    public function edit($id)
    {
        $model = $this->schedule->findOrFail($id);

        $this->edit["id"] = $model->id;
        $this->edit["name"] = $model->name;
        $this->edit["range_from"] = $model->range_from;
        $this->edit["range_to"] = $model->range_to;
        $this->edit["description"] = $model->description;
    }

    public function update()
    {
        if (!empty($this->edit["id"])) {
            $model = $this->schedule->findOrFail($this->edit["id"]);

            $isUpdated = $model->update([
                "name" => $this->edit["name"],
                "range_from" => $this->edit["range_from"],
                "range_to" => $this->edit["range_to"],
                "description" => $this->edit["description"]
            ]);

            if ($isUpdated) {
                $this->dispatchBrowserEvent("sweety:updated", [
                    "type" => "success",
                    "title" => "График успешно обновлен!!!",
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
            $is_deleted = $this->schedule->findOrFail($id)->delete();

            if ($is_deleted) {
                $this->dispatchBrowserEvent("sweety:deleted", [
                    "type" => "success",
                    "title" => "График успешно удален!!!",
                    "text" => ""
                ]);
            }
        }
    }

    public function storeFlexWorkHour()
    {
        $this->validate($this->flexRules);
        dd($this->flexWorkHour);
    }
}
