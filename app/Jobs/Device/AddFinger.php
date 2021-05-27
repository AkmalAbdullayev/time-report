<?php

namespace App\Jobs\Device;

use App\helpers\UserManagement;
use App\Models\Device;
use App\Models\Employee;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class AddFinger implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private Employee $model;
    private Device $device;
    protected string $type, $fingerNo, $targetDevice;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Employee $model, Device $device, string $type, string $fingerNo, string $targetDevice)
    {
        $this->model = $model;
        $this->device = $device;
        $this->type = $type;
        $this->fingerNo = $fingerNo;
        $this->targetDevice = $targetDevice;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(UserManagement $userManagement)
    {
        if ($this->type === "new") {
            $userManagement->addFPSecond($this->model, $this->device->ip, $this->fingerNo);
        } else {
            $userManagement->copyFP($this->model, $this->targetDevice, $this->fingerNo);
        }
    }
}
