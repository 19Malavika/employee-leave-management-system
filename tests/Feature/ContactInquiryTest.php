<?php

namespace Tests\Feature;

use Tests\TestCase;

class ContactInquiryTest extends TestCase
{
    public function test_contact_form_validates_required_contact_fields(): void
    {
        $response = $this->from('/contact')->post('/contact', [
            'name' => '',
            'email' => 'not-an-email',
            'message' => '',
        ]);

        $response->assertRedirect('/contact');
        $response->assertSessionHasErrors(['name', 'email', 'message']);
    }

    public function test_contact_form_accepts_valid_client_inquiry(): void
    {
        $response = $this->from('/contact')->post('/contact', [
            'name' => 'Jane Doe',
            'email' => 'jane@example.com',
            'message' => 'We need a website refresh for our marketing team.',
        ]);

        $response->assertRedirect('/contact');
        $response->assertSessionHas('success', 'Thanks! Your inquiry has been received.');
    }
}
