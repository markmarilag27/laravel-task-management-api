<?php

declare(strict_types=1);

namespace Database\Seeders;

use Database\Seeders\Traits\HasFaker;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    use HasFaker;

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        // Set the memory limit to 512M, adjust base on your needs
        ini_set('memory_limit', '512M');

        // By default, Laravel keeps a log in memory of all queries that have been run for the current request.
        // However, in some cases, such as when inserting a large number of rows, this can cause the application to use excess memory.
        DB::disableQueryLog();

        $this->command->info('Initializing UserSeeder...');

        /** @var $time_start */
        $time_start = microtime(true);

        /** @var $rowsNo */
        $rowsNo = 100000;

        /** @var $range */
        $range = range(1, $rowsNo);

        /** @var $chunkSize */
        $chunkSize = 1000;

        // Loop through array chunk
        foreach (array_chunk($range, $chunkSize, true) as $chunk) {
            /** @var $userData */
            $userData = [];

            // Loop chunk
            foreach ($chunk as $index) {
                /** @var $email */
                $email = $this->faker->unique()->safeEmail();
                /** @var $array */
                $array = explode('@', $email);
                /** @var $name */
                $name = Str::slug($array[0] . $index);

                $userData[$index] = [
                    'uuid'          => (string) Str::uuid(),
                    'name'          => $name,
                    'email'         => $email,
                    'password'      => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
                    'created_at'    => now(),
                    'updated_at'    => now()
                ];
            }

            // Insert the users
            DB::table('users')->insert($userData);
        }

        /** @var $time_end */
        $time_end = microtime(true);

        /** @var $execution_time */
        $execution_time = number_format(($time_end - $time_start), 2);

        $this->command->info("Total execution time: ${execution_time} in seconds.");
    }
}
