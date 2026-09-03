<?php

namespace Tests\Feature;

use App\Models\CaseStudy;
use App\Models\Service;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DynamicContentTest extends TestCase
{
    use RefreshDatabase;

    public function test_services_page_displays_dynamic_service_data(): void
    {
        Service::create([
            'title' => 'Brand Strategy',
            'description' => 'We shape brand direction for growing businesses.',
            'icon' => 'sparkles',
        ]);

        $response = $this->get('/services');

        $response->assertOk();
        $response->assertSee('Brand Strategy');
        $response->assertSee('growing businesses');
    }

    public function test_case_studies_page_displays_dynamic_case_study_data(): void
    {
        CaseStudy::create([
            'title' => 'Northstar Launch',
            'client' => 'Northstar Labs',
            'summary' => 'We helped them launch a new product and increase signups.',
            'result' => '+214% leads in 90 days',
        ]);

        $response = $this->get('/case-studies');

        $response->assertOk();
        $response->assertSee('Northstar Launch');
        $response->assertSee('Northstar Labs');
    }
}
