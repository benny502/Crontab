<?php
namespace Crontab\Contract;

interface CronExpressionInterface {

    /**
     * Set the minute (* 1 1-10,11-20,30-59 1-59 *\/1)
     *
     * @param string
     *
     * @return CronExpressionInterface
     */
    public function setMinute($minute);

    /**
     * Set the hour
     *
     * @param string
     *
     * @return CronExpressionInterface
     */
    public function setHour($hour);

    /**
     * Set the month
     *
     * @param string
     *
     * @return CronExpressionInterface
     */
    public function setMonth($month);

    /**
     * Set the day of week
     *
     * @param string
     *
     * @return CronExpressionInterface
     */
    public function setDayOfWeek($dayOfWeek);

    /**
     * Set the day of month
     *
     * @param string
     *
     * @return CronExpressionInterface
     */
    public function setDayOfMonth($dayOfMonth);

    /**
     * Render the Expresion for crontab
     *
     * @return string
     */
    public function render();

    /**
     * Parse crontab line into CronExpression object
     *
     * @param $crontabLine
     *
     * @return this
     * @throws \InvalidArgumentException
     */
    static function parse($crontabLine)


}