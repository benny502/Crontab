<?php

namespace Crontab;

use Crontab\Contract\CronExpressionInterface;

/**
 * Represent a cron job
 *
 * @author Benjamin Laugueux <benjamin@yzalis.com>
 */
class CronExpression implements CronExpressionInterface
{

    /**
     * @var $regex
     */
    static $_regex = array(
        'minute'     => '/[\*,\/\-0-9]+/',
        'hour'       => '/[\*,\/\-0-9]+/',
        'dayOfMonth' => '/[\*,\/\-\?LW0-9A-Za-z]+/',
        'month'      => '/[\*,\/\-0-9A-Z]+/',
        'dayOfWeek'  => '/[\*,\/\-0-9A-Z]+/',
    );

    /**
     * @var string
     */
    protected $minute = "0";

    /**
     * @var string
     */
    protected $hour = "*";

    /**
     * @var string
     */
    protected $dayOfMonth = "*";

    /**
     * @var string
     */
    protected $month = "*";

    /**
     * @var string
     */
    protected $dayOfWeek = "*";

    /**
     * @var DateTime
     */
    protected $lastRunTime = null;

    /**
     * @var $hash
     */
    protected $hash = null;

    /**
     * Return the minute
     *
     * @return string
     */
    public function getMinute()
    {
        return $this->minute;
    }

    /**
     * Return the hour
     *
     * @return string
     */
    public function getHour()
    {
        return $this->hour;
    }

    /**
     * Return the day of month
     *
     * @return string
     */
    public function getDayOfMonth()
    {
        return $this->dayOfMonth;
    }

    /**
     * Return the month
     *
     * @return string
     */
    public function getMonth()
    {
        return $this->month;
    }

    /**
     * Return the day of week
     *
     * @return string
     */
    public function getDayOfWeek()
    {
        return $this->dayOfWeek;
    }

    /**
     * To string
     *
     * @return string
     */
    public function __toString()
    {
        try {
            return $this->render();
        } catch (\Exception $e) {
            return '# '.$e;
        }
    }

    /**
     * Parse crontab line into CronExpression object
     *
     * @param $jobLine
     *
     * @return this
     * @throws \InvalidArgumentException
     */
    static function parse($crontabLine)
    {
        // split the line
        $parts = preg_split('@ @', $crontabLine, NULL, PREG_SPLIT_NO_EMPTY);

        // check the number of part
        if (count($parts) < 5) {
            throw new \InvalidArgumentException('Wrong number of arguments.');
        }

        $expression = new CronExpression();
        $expression
            ->setMinute($parts[0])
            ->setHour($parts[1])
            ->setDayOfMonth($parts[2])
            ->setMonth($parts[3])
            ->setDayOfWeek($parts[4]);

        return $expression;
    }

    /**
     * Generate a unique hash related to the job entries
     *
     * @return this
     */
    private function generateHash()
    {
        $this->hash = hash('md5', serialize(array(
            strval($this->getMinute()),
            strval($this->getHour()),
            strval($this->getDayOfMonth()),
            strval($this->getMonth()),
            strval($this->getDayOfWeek()),
        )));

        return $this;
    }

    /**
     * Get an array of job entries
     *
     * @return array
     */
    public function getEntries()
    {
        return array(
            $this->getMinute(),
            $this->getHour(),
            $this->getDayOfMonth(),
            $this->getMonth(),
            $this->getDayOfWeek(),
        );
    }

    /**
     * Render the Expresion for crontab
     *
     * @return string
     */
    public function render()
    {
        // Create / Recreate a line in the crontab
        $line = trim(implode(" ", $this->getEntries()));

        return $line;
    }

    /**
     * Return the last job run time
     *
     * @return DateTime|null
     */
    public function getLastRunTime()
    {
        return $this->lastRunTime;
    }

    /**
     * Return the job unique hash
     *
     * @return this
     */
    public function getHash()
    {
        if (null === $this->hash) {
            $this->generateHash();
        }

        return $this->hash;
    }

    /**
     * Set the minute (* 1 1-10,11-20,30-59 1-59 *\/1)
     *
     * @param string
     *
     * @return this
     */
    public function setMinute($minute)
    {
        if (!preg_match(self::$_regex['minute'], $minute)) {
            throw new \InvalidArgumentException(sprintf('Minute "%s" is incorect', $minute));
        }

        $this->minute = $minute;

        return $this->generateHash();
    }

    /**
     * Set the hour
     *
     * @param string
     *
     * @return this
     */
    public function setHour($hour)
    {
        if (!preg_match(self::$_regex['hour'], $hour)) {
            throw new \InvalidArgumentException(sprintf('Hour "%s" is incorect', $hour));
        }

        $this->hour = $hour;

        return $this->generateHash();
    }

    /**
     * Set the day of month
     *
     * @param string
     *
     * @return this
     */
    public function setDayOfMonth($dayOfMonth)
    {
        if (!preg_match(self::$_regex['dayOfMonth'], $dayOfMonth)) {
            throw new \InvalidArgumentException(sprintf('DayOfMonth "%s" is incorect', $dayOfMonth));
        }

        $this->dayOfMonth = $dayOfMonth;

        return $this->generateHash();
    }

    /**
     * Set the month
     *
     * @param string
     *
     * @return this
     */
    public function setMonth($month)
    {
        if (!preg_match(self::$_regex['month'], $month)) {
            throw new \InvalidArgumentException(sprintf('Month "%s" is incorect', $month));
        }

        $this->month = $month;

        return $this->generateHash();
    }

    /**
     * Set the day of week
     *
     * @param string
     *
     * @return this
     */
    public function setDayOfWeek($dayOfWeek)
    {
        if (!preg_match(self::$_regex['dayOfWeek'], $dayOfWeek)) {
            throw new \InvalidArgumentException(sprintf('DayOfWeek "%s" is incorect', $dayOfWeek));
        }

        $this->dayOfWeek = $dayOfWeek;

        return $this->generateHash();
    }

    /**
     * Set the last task run time
     *
     * @param int
     *
     * @return this
     */
    public function setLastRunTime($lastRunTime)
    {
        $this->lastRunTime = \DateTime::createFromFormat('U', $lastRunTime);

        return $this;
    }

}
