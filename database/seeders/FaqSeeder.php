<?php

namespace Database\Seeders;

use App\Models\Faq;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class FaqSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faqs = [
            [
                'question' => 'What is this platform about?',
                'answer' => 'This platform helps users manage and access documentation efficiently.',
                'is_active' => true,
                'sort_order' => 1,
            ],
            [
                'question' => 'How can I create an account?',
                'answer' => 'Click on the signup button and fill in the required details.',
                'is_active' => true,
                'sort_order' => 2,
            ],
            [
                'question' => 'Is registration free?',
                'answer' => 'Yes, registration is completely free.',
                'is_active' => true,
                'sort_order' => 3,
            ],
            [
                'question' => 'How do I reset my password?',
                'answer' => 'Use the "Forgot Password" option on the login page.',
                'is_active' => true,
                'sort_order' => 4,
            ],
            [
                'question' => 'Can I edit my profile?',
                'answer' => 'Yes, you can update your profile from the dashboard.',
                'is_active' => true,
                'sort_order' => 5,
            ],
            [
                'question' => 'How do I delete my account?',
                'answer' => 'Contact support to request account deletion.',
                'is_active' => true,
                'sort_order' => 6,
            ],
            [
                'question' => 'Is my data secure?',
                'answer' => 'We use industry-standard security practices to protect your data.',
                'is_active' => true,
                'sort_order' => 7,
            ],
            [
                'question' => 'Can I export my data?',
                'answer' => 'Yes, export options are available in settings.',
                'is_active' => true,
                'sort_order' => 8,
            ],
            [
                'question' => 'Do you support API access?',
                'answer' => 'Yes, API access is available for developers.',
                'is_active' => true,
                'sort_order' => 9,
            ],
            [
                'question' => 'Where can I find documentation?',
                'answer' => 'Documentation is available under the Docs section.',
                'is_active' => true,
                'sort_order' => 10,
            ],
            [
                'question' => 'Can I collaborate with others?',
                'answer' => 'Yes, collaboration features are supported.',
                'is_active' => true,
                'sort_order' => 11,
            ],
            [
                'question' => 'How do I report a bug?',
                'answer' => 'You can report bugs through the support section.',
                'is_active' => true,
                'sort_order' => 12,
            ],
            [
                'question' => 'Do you offer customer support?',
                'answer' => 'Yes, 24/7 customer support is available.',
                'is_active' => true,
                'sort_order' => 13,
            ],
            [
                'question' => 'Is there a mobile app?',
                'answer' => 'Currently, we are working on a mobile application.',
                'is_active' => true,
                'sort_order' => 14,
            ],
            [
                'question' => 'Can I customize notifications?',
                'answer' => 'Yes, notification preferences can be managed in settings.',
                'is_active' => true,
                'sort_order' => 15,
            ],
            [
                'question' => 'How often is the platform updated?',
                'answer' => 'We release updates regularly with improvements.',
                'is_active' => true,
                'sort_order' => 16,
            ],
            [
                'question' => 'Do you provide tutorials?',
                'answer' => 'Yes, tutorials are available in the help section.',
                'is_active' => true,
                'sort_order' => 17,
            ],
            [
                'question' => 'Can I integrate third-party services?',
                'answer' => 'Yes, integrations are supported via API.',
                'is_active' => true,
                'sort_order' => 18,
            ],
            [
                'question' => 'What browsers are supported?',
                'answer' => 'We support all modern browsers like Chrome, Firefox, and Edge.',
                'is_active' => true,
                'sort_order' => 19,
            ],
            [
                'question' => 'How do I contact support?',
                'answer' => 'You can contact support via email or live chat.',
                'is_active' => true,
                'sort_order' => 20,
            ],
        ];

        foreach ($faqs as $faq) {
            Faq::create(array_merge($faq, [
                'created_at' => now(),
                'updated_at' => now(),
            ]));
        }
    }
}
