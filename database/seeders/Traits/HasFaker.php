<?php

declare(strict_types=1);

namespace Database\Seeders\Traits;

use Faker\Generator;
use Illuminate\Container\Container;

trait HasFaker
{
    /** @var Generator|mixed|object $faker */
    protected mixed $faker;

    /**
     * Create a new CommentSeeder constructor.
     *
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function __construct()
    {
        $this->faker = $this->withFaker();
    }

    /**
     * @return Generator|mixed|object
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    private function withFaker(): mixed
    {
        return Container::getInstance()->make(Generator::class);
    }
}
