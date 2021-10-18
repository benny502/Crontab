<?php
    
namespace Crontab;

use Crontask\TaskList as CrontaskTaskList;

class TaskList extends CrontaskTaskList {

    private static $instance;

    /**
     * @param mixed ...$args
     * @return static
     */
    static function getInstance(...$args)
    {
        if(!isset(static::$instance)){
            static::$instance = new static(...$args);
        }
        return static::$instance;
    }

    protected function __construct() {

    }
}
?>