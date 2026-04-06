<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Page;

class PageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $pages = [
            [
                'title' => 'Home',
                'slug' => 'home',
                'content' => '<h1>Welcome to Bharat Biomer</h1><p>Discover innovative biometric solutions that transform industries.</p>',
                'meta_title' => 'Bharat Biomer - Advanced Biometric Solutions',
                'meta_description' => 'Explore cutting-edge biometric technology and solutions for secure identification and authentication.',
                'meta_keyword' => 'biometric solutions, biometric technology, secure identification, authentication',
                'status' => true,
            ],
            [
                'title' => 'Technology',
                'slug' => 'technology',
                'content' => '<h1>Our Technology</h1><p>Learn about our advanced biometric technology platform and capabilities.</p><p>We utilize state-of-the-art algorithms and machine learning to deliver reliable and secure biometric solutions.</p>',
                'meta_title' => 'Our Advanced Biometric Technology | Bharat Biomer',
                'meta_description' => 'Discover our cutting-edge biometric technology platform with advanced algorithms and AI capabilities.',
                'meta_keyword' => 'biometric technology, algorithms, machine learning, secure platform',
                'status' => true,
            ],
            [
                'title' => 'About Us',
                'slug' => 'about-us',
                'content' => '<h1>About Bharat Biomer</h1><p>Bharat Biomer is a leading provider of biometric solutions and services.</p><p>With years of expertise in the industry, we are committed to delivering innovative and reliable solutions for our clients worldwide.</p>',
                'meta_title' => 'About Bharat Biomer - Leading Biometric Solutions Provider',
                'meta_description' => 'Learn about Bharat Biomer, our mission, vision, and commitment to providing innovative biometric solutions.',
                'meta_keyword' => 'about us, biometric company, mission, vision, expertise',
                'status' => true,
            ],
            [
                'title' => 'Impact',
                'slug' => 'impact',
                'content' => '<h1>Our Impact</h1><p>See how our biometric solutions are making a difference across industries.</p><p>From healthcare to security, our technology enables better outcomes and enhanced efficiency.</p>',
                'meta_title' => 'Impact & Results | Bharat Biomer Solutions',
                'meta_description' => 'Discover the real-world impact of our biometric solutions across healthcare, security, and enterprise sectors.',
                'meta_keyword' => 'impact, results, biometric solutions, case studies, success stories',
                'status' => true,
            ],
            [
                'title' => 'Contact',
                'slug' => 'contact',
                'content' => '<h1>Get In Touch</h1><p>Have questions? We\'d love to hear from you.</p><p>Contact our team to learn more about our biometric solutions and services.</p>',
                'meta_title' => 'Contact Us - Bharat Biomer',
                'meta_description' => 'Get in touch with our team for inquiries, support, and more information about our biometric solutions.',
                'meta_keyword' => 'contact, support, inquiry, customer service, get in touch',
                'status' => true,
            ],
            [
                'title' => 'Our Collaboration',
                'slug' => 'collaboration',
                'content' => '<h1>Partnership & Collaboration</h1><p>We believe in the power of partnerships and collaboration.</p><p>Join us in revolutionizing the biometric industry through strategic collaborations.</p>',
                'meta_title' => 'Partnerships & Collaboration | Bharat Biomer',
                'meta_description' => 'Explore partnership opportunities and collaboration with Bharat Biomer for mutual growth and innovation.',
                'meta_keyword' => 'partnership, collaboration, strategic alliance, mutual growth',
                'status' => true,
            ],
            [
                'title' => 'Privacy Policy',
                'slug' => 'privacy-policy',
                'content' => '<h1>Privacy Policy</h1><p>Your privacy is important to us.</p><p>This page outlines how we collect, use, and protect your personal information.</p>',
                'meta_title' => 'Privacy Policy - Bharat Biomer',
                'meta_description' => 'Read our privacy policy to understand how we protect and manage your personal data.',
                'meta_keyword' => 'privacy policy, data protection, personal information, privacy',
                'status' => true,
            ],
            [
                'title' => 'Terms & Conditions',
                'slug' => 'terms-conditions',
                'content' => '<h1>Terms & Conditions</h1><p>Please read our terms and conditions carefully.</p><p>By using our services, you agree to be bound by these terms.</p>',
                'meta_title' => 'Terms & Conditions - Bharat Biomer',
                'meta_description' => 'Review the terms and conditions governing the use of our services and products.',
                'meta_keyword' => 'terms and conditions, legal, service agreement, user terms',
                'status' => true,
            ],
        ];

        foreach ($pages as $page) {
            Page::updateOrCreate(
                ['slug' => $page['slug']],
                $page
            );
        }
    }
}
