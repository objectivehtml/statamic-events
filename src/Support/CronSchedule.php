<?php

namespace Objectivehtml\Events\Support;

use Cron\CronExpression;
use Illuminate\Console\Scheduling\ManagesFrequencies;

class CronSchedule
{
    use ManagesFrequencies;

    /**
     * The cron expression representing the event's frequency.
     *
     * @var string
     */
    public $expression = '* * * * *';

    /**
     * The timezone the date should be evaluated on.
     *
     * @var \DateTimeZone|string
     */
    public $timezone;

    /**
     * The array of filter callbacks.
     *
     * @var array
     */
    protected $filters = [];

    /**
     * The array of reject callbacks.
     *
     * @var array
     */
    protected $rejects = [];

    /**
     * Construct the cron schedule.
     * 
     * @param \DateTimeZone|string  $timezone
     */
    public function __construct($expression = null, $timezone = null)
    {
        if(!is_null($expression)) {
            $this->expression = $expression;
        }

        $this->timezone = $timezone ?: config('app.timezone');
    }

    /**
     * Schedule the event to run every nth position.
     *
     * @return $this
     */
    public function months($months)
    {
        return $this->spliceIntoPosition(4, collect($months)->join(','));
    }

    public function dates($dates)
    {
        return $this->spliceIntoPosition(3, collect($dates)->join(','));
    }

    /**
     * Schedule the event to run every nth position.
     *
     * @return $this
     */
    public function nth($position, $nth)
    {
        return $this->spliceIntoPosition($position, "*/$nth");
    }

    /**
     * Schedule the event to run every nth minute.
     *
     * @return $this
     */
    public function everyNthMinute($nth)
    {
        return $this->nth(1, $nth);
    }

    /**
     * Schedule the event to run every nth hour.
     *
     * @return $this
     */
    public function everyNthHour($nth)
    {
        return $this->nth(2, $nth);
    }

    /**
     * Schedule the event to run every nth day.
     *
     * @return $this
     */
    public function everyNthDay($nth)
    {
        return $this->nth(3, $nth);
    }

    /**
     * Schedule the event to run every nth week.
     *
     * @return $this
     */
    public function everyNthWeek($nth)
    {
        $start = max(0, $nth - 1) * 7;
        
        return $this->spliceIntoPosition(3, max(1, $start).'-'.min(31, $start + 7));
    }

    /**
     * Schedule the event to run every nth month.
     *
     * @return $this
     */
    public function everyNthMonth($nth)
    {
        return $this->nth(4, $nth);
    }

    /**
     * Determine if the filters pass for the event.
     *
     * @return bool
     */
    public function filtersPass()
    {
        foreach ($this->filters as $callback) {
            if (! $callback()) {
                return false;
            }
        }

        foreach ($this->rejects as $callback) {
            if ($callback()) {
                return false;
            }
        }

        return true;
    }

    /**
     * Register a callback to further filter the schedule.
     *
     * @param  \Closure|bool  $callback
     * @return $this
     */
    public function when($callback)
    {
        $this->filters[] = is_callable($callback) ? $callback : function () use ($callback) {
            return $callback;
        };

        return $this;
    }

    /**
     * Register a callback to further filter the schedule.
     *
     * @param  \Closure|bool  $callback
     * @return $this
     */
    public function skip($callback)
    {
        $this->rejects[] = is_callable($callback) ? $callback : function () use ($callback) {
            return $callback;
        };

        return $this;
    }

    /**
     * Instantiate an instance of the cron parser.
     * 
     * @return \Cron\CronExpression
     */
    public function expression()
    {
        return CronExpression::factory($this->expression);
    }

}