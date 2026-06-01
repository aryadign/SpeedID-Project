<?php

use Illuminate\Support\Facades\Schedule;

Schedule::command('speedid:cleanup-queues')->dailyAt('00:00');
Schedule::command('speedid:cleanup-notifications')->dailyAt('01:00');
Schedule::command('speedid:cleanup-activity')->dailyAt('02:00');
Schedule::command('speedid:publish-news')->everyMinute();
