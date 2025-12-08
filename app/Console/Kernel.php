<?php

namespace App\Console;

use App\Observers\AssignmentObserver;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     * 
     * HORARIO: Se ejecuta diariamente a las 04:00 AM hora Denver (America/Denver)
     * Laravel convierte automáticamente al timezone del servidor
     */
    protected function schedule(Schedule $schedule): void
    {
        $schedule->command('supplies:reset')
            ->dailyAt('04:00') // ✅ 04:00 AM hora Denver (CORREGIDO)
            ->timezone('America/Denver')
            ->appendOutputTo(storage_path('logs/supplies-reset.log')); // ✅ Log adicional


            // 🔥 EJECUTAR CADA 8 HORAS EL RESET AUTOMÁTICO
        $schedule->call(function () {
            $observer = new AssignmentObserver();
            $observer->checkAndUpdateExpiredAssignments();
        })->everyEightHours()->name('reset-expired-assignments')->withoutOverlapping();
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');
        require base_path('routes/console.php');
    }
}