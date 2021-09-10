<?php

declare(strict_types=1);

namespace Tests\Feature\Controllers\V1\State;

use App\Http\Controllers\V1\State\GetAllStateController;
use Tests\TestCase;

class GetAllStateTest extends TestCase
{
    /** @var string $route */
    protected string $route;

    /**
     * Override set up the test environment.
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->route = action(GetAllStateController::class);
    }

    /** @test */
    public function unauthorized_user_failed_to_get_state_information(): void
    {
        $this->assertGuest();

        $this->json('GET', $this->route)->assertUnauthorized();
    }
}
