<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\SiteSetting;
use App\Models\HeaderLink;
use App\Models\FooterLink;

class SiteDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Seed Site Settings
        SiteSetting::updateOrCreate(
            ['id' => 1],
            [
                'site_name' => 'Bharat Biomer',
                'tagline' => 'Advanced biological solutions for sustainable farming.',
                'email' => 'admin@bharatbiomer.com',
                'phone' => '+91 7828333334',
                'address' => 'India',
                'about' => 'Bharat Biomer is dedicated to providing advanced biological solutions for sustainable farming and agricultural development.',
                'facebook_url' => 'https://facebook.com',
                'twitter_url' => 'https://twitter.com',
                'instagram_url' => 'https://instagram.com',
                'linkedin_url' => 'https://linkedin.com',
                'footer_text' => '© '.date('Y').' Bharat Biomer. All rights reserved.',
            ]
        );

        // Seed Header Links
        $headerLinks = [
            ['label' => 'Home', 'url' => '/', 'position' => 1, 'is_active' => true, 'target' => '_self'],
            ['label' => 'Technology', 'url' => '/technology', 'position' => 2, 'is_active' => true, 'target' => '_self'],
            ['label' => 'Products', 'url' => '/products', 'position' => 3, 'is_active' => true, 'target' => '_self'],
            ['label' => 'About Us', 'url' => '/about', 'position' => 4, 'is_active' => true, 'target' => '_self'],
            ['label' => 'Impact', 'url' => '/impact', 'position' => 5, 'is_active' => true, 'target' => '_self'],
            ['label' => 'Contact', 'url' => '/contact', 'position' => 6, 'is_active' => true, 'target' => '_self'],
        ];

        foreach ($headerLinks as $link) {
            HeaderLink::updateOrCreate(
                ['label' => $link['label']],
                $link
            );
        }

        // Seed Footer Links
        $footerLinks = [
            // Products Section
            ['section' => 'Products', 'label' => 'Bio-stimulants', 'url' => '/products', 'position' => 1, 'is_active' => true, 'target' => '_self'],
            ['section' => 'Products', 'label' => 'Microbial Solutions', 'url' => '/products', 'position' => 2, 'is_active' => true, 'target' => '_self'],
            
            // Company Section
            ['section' => 'Company', 'label' => 'About Us', 'url' => '/about', 'position' => 1, 'is_active' => true, 'target' => '_self'],
            ['section' => 'Company', 'label' => 'Technology', 'url' => '/technology', 'position' => 2, 'is_active' => true, 'target' => '_self'],
            
            // Contact Section
            ['section' => 'Contact', 'label' => 'Email Us', 'url' => 'mailto:admin@bharatbiomer.com', 'position' => 1, 'is_active' => true, 'target' => '_self'],
            ['section' => 'Contact', 'label' => 'Call Us', 'url' => 'tel:+917828333334', 'position' => 2, 'is_active' => true, 'target' => '_self'],
        ];

        foreach ($footerLinks as $link) {
            FooterLink::updateOrCreate(
                ['section' => $link['section'], 'label' => $link['label']],
                $link
            );
        }
    }
}
