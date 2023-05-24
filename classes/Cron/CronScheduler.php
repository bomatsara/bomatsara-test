<?php

namespace BomatsaraTest\Cron;

use BomatsaraTest\Cron\CronInterface;

class CronScheduler implements CronInterface
{
    private $callback;
    private string $interval;
    private string $event_name;

    public function __construct(callable $callback, $interval, $event_name)
    {
        $this->callback = $callback;
        $this->interval = $interval;
        $this->event_name = $event_name;

        add_action('init', [$this, 'schedule_cron']);
        add_action($this->event_name, $this->callback);
    }

    public function schedule_cron(): void
    {
        if (!wp_next_scheduled($this->event_name)) {
            wp_schedule_event(time(), $this->interval, $this->event_name);
        }
    }
}