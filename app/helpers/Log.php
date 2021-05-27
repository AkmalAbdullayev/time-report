<?php

namespace App\helpers;

use App\Models\CronTask;

class Log
{
    public function create($task, $status, $description)
    {
        CronTask::create([
            "task" => json_encode($task),
            "status" => $status,
            "description" => $description
        ]);
    }
}
