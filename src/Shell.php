<?php
namespace Crontab;

use Ramsey\Uuid\Uuid;
use Psr\Log\LoggerInterface;
use Crontask\Tasks\Shell as TasksShell;

class Shell extends TasksShell{

    protected $taskId;

    protected $runable;

    protected $startTime;

    protected $log;

    /**
     * @param RunableInterface $runable
     * @param string $startTime
     * @param LoggerInterface $log
     * @return void
     */
    public function __construct(LoggerInterface $log)
    {
        $this->log = $log;
        $this->taskId = Uuid::uuid1()->toString();
    }

    /**
     * @return string
     */
    public function getTaskId() {
        return $this->taskId;
    }

}

?>