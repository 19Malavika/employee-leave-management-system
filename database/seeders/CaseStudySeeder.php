<?php

namespace Database\Seeders;

use App\Models\CaseStudy;
use Illuminate\Database\Seeder;

class CaseStudySeeder extends Seeder
{
    public function run(): void
    {
        $caseStudies = [
            [
                'project_name' => 'E-Commerce Platform',
                'description' => 'A full-featured online store with product catalog, cart, and secure checkout.',
                'technology' => 'Laravel, Vue.js, Stripe',
                'image' => 'build/assets/casestudy2.jpg',
            ],
            [
                'project_name' => 'Booking System',
                'description' => 'A reservation platform allowing customers to book services and manage appointments.',
                'technology' => 'Laravel, MySQL, Tailwind',
                'image' => 'build/assets/casestudy3.png',
            ],
            [
                'project_name' => 'Fitness Tracking App',
                'description' => 'A mobile app for tracking workouts, nutrition, and progress over time.',
                'technology' => 'Flutter, Laravel API',
                'image' => 'build/assets/casestudy4.jpeg',
            ],
            [
                'project_name' => 'Real Estate Listing Portal',
                'description' => 'A property listing website with search filters, maps, and agent profiles.',
                'technology' => 'Laravel, Alpine.js, Google Maps API',
                'image' => 'build/assets/casestudy.webp',
            ],
        ];

        foreach ($caseStudies as $item) {
            CaseStudy::create($item);
        }
    }
}
