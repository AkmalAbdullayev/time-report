<?php

namespace App\Jobs\Device;

use App\helpers\UserManagement;
use App\Models\Employee;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class AddUser implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private Employee $model;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Employee $model)
    {
        $this->model = $model;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(UserManagement $userManagement)
    {
        $userManagement->addUser($this->model);
    }
}
