<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Task;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use JetBrains\PhpStorm\ArrayShape;

class TaskFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Task::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    #[ArrayShape(['title' => "string", 'body' => "string", 'state_id' => "mixed", 'user_id' => "\Illuminate\Database\Eloquent\Factories\Factory"])]
    public function definition(): array
    {
        return [
            'title'     => $this->faker->jobTitle,
            'body'      => $this->faker->paragraph,
            'state_id'  => $this->faker->randomElement([1, 2, 3]),
            'user_id'   => User::factory(),
        ];
    }
}
