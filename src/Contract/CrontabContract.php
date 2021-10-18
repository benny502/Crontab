<?php
    
namespace Crontab\Contract;

use Crontask\Interfaces\TaskInterface;

interface CrontabInterface {
    public static function addTask(TaskInterface $task);
    public static function removeTask(TaskInterface $task);
}

?>