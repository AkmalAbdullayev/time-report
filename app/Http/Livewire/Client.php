<?php

namespace App\Http\Livewire;

use Illuminate\Database\Eloquent\Collection;
use Livewire\Component;
use Livewire\WithPagination;

class Client extends Component
{
    use WithPagination;

    public array $edit = [];
    public Collection $employeeClients;

    public function getListeners()
    {
        return [
            "destroy" => "destroy",
            "clientInfo" => "clientInfo"
        ];
    }

    public function render()
    {
        $this->dispatchBrowserEvent("select2");

        $guest_types = ["guest", "client", "other"];
        return view('livewire.client', [
            "employees" => \App\Models\Employee::all(),
            "guest_types" => $guest_types,
            "clients" => \App\Models\Client::with("employee")->paginate(10)
        ]);
    }

    public function clear()
    {
        $this->reset("edit");
    }

    public function edit($client)
    {
        $this->edit = $client;
    }

    public function update()
    {
        $isUpdated = \App\Models\Client::findOrFail($this->edit["id"])->update([
            "guest_name" => $this->edit["guest_name"],
            "type" => $this->edit["type"],
            "description" => $this->edit["description"]
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
        $isDeleted = \App\Models\Client::findOrFail($id)->delete();

        if ($isDeleted) {
            $this->dispatchBrowserEvent("sweety:deleted", [
                "type" => "success",
                "title" => "Информация успешно удалено!!!",
                "text" => ""
            ]);
        }
    }

    public function clientInfo($id)
    {
        $this->dispatchBrowserEvent("showModal");
        $client = \App\Models\Employee::findOrFail($id);
        $this->employeeClients = $client->clients;
    }
}
