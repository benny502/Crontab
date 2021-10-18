<?php

namespace Crontab\Contract;

use Crontask\Interfaces\TaskInterface as InterfacesTaskInterface;

interface TaskInterface extends InterfacesTaskInterface {
    public function getTaskId();
}