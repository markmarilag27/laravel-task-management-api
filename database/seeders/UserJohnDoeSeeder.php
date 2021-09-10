<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserJohnDoeSeeder extends Seeder
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

        $this->command->info('Initializing UserJohnDoeSeeder...');

        /** @var $time_start */
        $time_start = microtime(true);

        /** @var $payload */
        $payload = [
            'name'  => 'John Doe',
            'email' => 'john.doe@laravel.com'
        ];

        $user = User::query()->where('email', $payload['email'])->first();

        if (! $user) {
            User::factory()->create($payload);
        }

        /** @var $time_end */
        $time_end = microtime(true);

        /** @var $execution_time */
        $execution_time = number_format(($time_end - $time_start), 2);

        $this->command->info("Total execution time: ${execution_time} in seconds.");
    }
}
