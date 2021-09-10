<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Task;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TaskSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        // By default, Laravel keeps a log in memory of all queries that have been run for the current request.
        // However, in some cases, such as when inserting a large number of rows, this can cause the application to use excess memory.
        DB::disableQueryLog();

        $this->command->info('Initializing TaskSeeder...');

        /** @var $time_start */
        $time_start = microtime(true);

        Task::factory()
            ->count(20)
            ->create()
            ->each(function ($task) {
                Task::factory()
                    ->count(20)
                    ->create(['parent_id' => $task->id])
                    ->each(function ($subTask) {
                        Task::factory()
                            ->count(20)
                            ->create(['parent_id' => $subTask->id]);
                    });
            });

        /** @var $time_end */
        $time_end = microtime(true);

        /** @var $execution_time */
        $execution_time = number_format(($time_end - $time_start), 2);

        $this->command->info("Total execution time: ${execution_time} in seconds.");
    }
}
