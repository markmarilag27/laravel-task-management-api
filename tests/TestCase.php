<?php

namespace Tests;

use App\Enums\TaskState;
use App\Models\State;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication, RefreshDatabase, WithFaker;

    /**
     * Override set up the test environment.
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();

        foreach (TaskState::getValues() as $value) {
            State::factory()->create(['name' => $value]);
        }
    }
}
