<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use App\Task;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        //
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        /*$schedule->command('inspire')
            ->everyMinute();*/

       DB::beginTransaction();
       try{
           $tasks = Task::where(['is_run' => 1])->get();
           if (!empty($tasks)) {
               foreach ($tasks as $task) {
                   $time = [
                       $task->minute,
                       $task->hour,
                       $task->day,
                       $task->month,
                       $task->week
                   ];
                   $cron = trim(implode(' ', $time));
                   if ($task->command_type == 1) {
                       if (!$task->is_wol) {
                           //避免任务重复
                           if (!$task->is_eimm) {
                               //维护模式强制执行
                               $schedule->command($task->command)->withoutOverlapping()->evenInMaintenanceMode()->cron($cron);
                           } else {
                               $schedule->command($task->command)->withoutOverlapping()->cron($cron);
                           }
                       } else {
                           if (!$task->is_eimm) {
                               //维护模式强制执行
                               $schedule->command($task->command)->evenInMaintenanceMode()->cron($cron);
                           } else {
                               $schedule->command($task->command)->cron($cron);
                           }

                       }
                   } else {
                       $schedule->exec($task->command)->cron($cron);
                   }
               }
           }

       }catch (\Exception $exception){
           return true;

       }



    }

    /**
     * Register the Closure based commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        require base_path('routes/console.php');
    }
}
