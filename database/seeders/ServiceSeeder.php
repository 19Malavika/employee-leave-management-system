<?php

namespace Database\Seeders;

use App\Models\Service;
use Illuminate\Database\Seeder;

class ServiceSeeder extends Seeder
{
    public function run(): void
    {
        $services = [
            [
                'title' => 'Web Development',
                'description' => 'Custom, scalable web applications built with modern frameworks like Laravel.',
            ],
            [
                'title' => 'Mobile App Development',
                'description' => 'Native and cross-platform mobile apps for iOS and Android.',
            ],
            [
                'title' => 'UI/UX Design',
                'description' => 'User-centered design that balances aesthetics with usability.',
            ],
            [
                'title' => 'Cloud Solutions',
                'description' => 'Scalable cloud infrastructure, deployment, and DevOps support.',
            ],
            [
                'title' => 'E-Commerce Development',
                'description' => 'Custom online stores with secure payment integration and inventory management.',
            ],
            [
                'title' => 'API Development & Integration',
                'description' => 'RESTful APIs connecting your systems and integrating third-party services.',
            ],
        ];

        foreach ($services as $service) {
            Service::create($service);
        }
    }
}
