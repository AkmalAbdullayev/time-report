<?php

namespace App\Http\Livewire\Report;

use App\Models\Company;
use App\Models\Department;
use App\Models\Employee;
use Livewire\Component;
use Livewire\WithPagination;

class Presences extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public $selectedCompany = null;
    public $selectedDepartment = null;
    public string $search = '';
    public $companies;
    public $departments;

    public function mount()
    {
        $this->companies = Company::all();
        $this->departments = collect();
    }

    public function render()
    {
        $query = Employee::query();
        $query->with(["schedule", "companies", "branches", "departments", "positions", "comeOuts" => function ($query) {
            return $query->with("doorDevice")->whereDate("action_time", now()->format("Y-m-d"))->latest("action_time");
        }])
            ->has("comeOuts");

        $query->when(!empty($this->search), function ($query) {
            return $query->where("first_name", "like", "{$this->search}%")
                ->orWhere("last_name", "like", "{$this->search}%");
        });

        $query->when(!is_null($this->selectedCompany), function ($query) {
            $query->whereHas("companies", function ($query) {
                return $query->where("id", $this->selectedCompany);
            });
        });

        $query->when(!is_null($this->selectedDepartment), function ($query) {
            $query->whereHas("companies", function ($query) {
                return $query->where("id", $this->selectedCompany);
            })
                ->whereHas("departments", function ($q) {
                    return $q->where("id", $this->selectedDepartment);
                });
        });

        $currentlyIn = $query->get();

        return view('livewire.report.presences', [
            "currentlyIn" => $currentlyIn,
        ]);
    }

    public function updatedSelectedCompany($company)
    {
        if (!is_null($company)) {
            $this->departments = Department::where("company_id", $company)->get();
            $this->selectedDepartment = null;
        }
    }
}
