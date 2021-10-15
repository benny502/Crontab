<?php
namespace Crontab\Crontab;

use Crontab\TaskList;
use Psr\Log\LoggerInterface;
use Crontab\Contract\TaskInterface;
use Psr\SimpleCache\CacheInterface;
use Crontab\Contract\CronExpressionInterface;

class Crontab  {

    protected $log;

    protected $cache;

    protected $cacheKey = "benny/crontab";


    /**
     * @param CacheInterface $cache
     * @param LoggerInterface $log
     * @return void
     */
    public function __construct(CacheInterface $cache = null, LoggerInterface $log = null)
    {
        $this->cache = $cache;
        $this->log = $log;

        //Todo:初始化taskList
        TaskList::getInstance()->setTasks([]);
        if(!is_null($this->cache)) {
            $taskList = $this->cache->get($this->cacheKey);
            TaskList::getInstance()->setTasks($taskList);
        }
    }

    public function addTask(TaskInterface $task, CronExpressionInterface $expression = null) {
        
        if(!is_null($expression)) {
            $task->setExpression($expression->render());
        }

        $exist = false;
        foreach(TaskList::getInstance()->getTasks() as $currentTask) {
            if($task->getTaskId() == $currentTask->getTaskId()) {
                $exist = true;
            }
        }
        if(!$exist) {
            TaskList::getInstance()->addTask($task);
            if(!is_null($this->cache)) {
                $taskList = $this->cache->get($this->cacheKey);
                TaskList::getInstance()->setTasks($taskList);
            }
            return $task->getTaskId();
        }

    }

    public function removeTask($taskId) {
        $taskList = [];
        foreach(TaskList::getInstance()->getTasks() as $task) {
            if($task->getTaskId() != $taskId) {
                $taskList[] = $task;
            }
        }
        TaskList::getInstance()->setTasks($taskList);
        if(!is_null($this->cache)) {
            $taskList = $this->cache->get($this->cacheKey);
            TaskList::getInstance()->setTasks($taskList);
        }
    }

    public function run() {
        TaskList::getInstance()->run();
    } 
}