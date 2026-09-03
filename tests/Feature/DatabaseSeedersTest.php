<?php

namespace Tests\Feature;

use Tests\TestCase;

class DatabaseSeedersTest extends TestCase
{
    public function test_services_and_case_studies_are_seeded(): void
    {
        $this->artisan('db:seed')->assertSuccessful();

        $this->assertDatabaseCount('services', 6);
        $this->assertDatabaseCount('case_studies', 4);
    }
}
