<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Inquiry;
use App\Models\PublicUser;
use Carbon\Carbon;

class UnassignedInquirySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create some unassigned inquiries for testing
        $inquiries = [
            [
                'InquiryTitle' => 'Suspicious Social Media Post About COVID-19 Vaccine',
                'InquiryDescription' => 'I came across a viral Facebook post claiming that COVID-19 vaccines contain microchips for government tracking. The post has been shared over 10,000 times and includes what appears to be official medical documents. I am concerned about the spread of this misinformation and its impact on public health. The post also includes videos that claim to show magnetic reactions in vaccinated individuals.',
                'InquiryStatus' => 'Pending Review',
                'SubmitionDate' => Carbon::now()->subDays(2),
                'UserID' => 1,
                'AgencyID' => null,
                'AdminID' => null,
                'InquiryEvidence' => json_encode([
                    'files' => [
                        ['name' => 'facebook_screenshot.png', 'type' => 'image'],
                        ['name' => 'viral_video.mp4', 'type' => 'video']
                    ]
                ])
            ],
            [
                'InquiryTitle' => 'Fake Investment Scheme on WhatsApp',
                'InquiryDescription' => 'My elderly neighbor received WhatsApp messages about a "guaranteed 300% return investment" scheme allegedly endorsed by Bank Negara Malaysia. The messages include fake official letterheads and promise returns within 30 days. Several people in our community have already fallen victim to this scam, losing significant amounts of money. The scammers are using official-looking documents and logos.',
                'InquiryStatus' => 'Pending Review',
                'SubmitionDate' => Carbon::now()->subDays(1),
                'UserID' => 2,
                'AgencyID' => null,
                'AdminID' => null,
                'InquiryEvidence' => json_encode([
                    'files' => [
                        ['name' => 'whatsapp_messages.pdf', 'type' => 'pdf'],
                        ['name' => 'fake_bnm_letter.jpg', 'type' => 'image']
                    ]
                ])
            ],
            [
                'InquiryTitle' => 'Fake Government Grant Application Website',
                'InquiryDescription' => 'I discovered a website claiming to offer Malaysian government grants for small businesses affected by the pandemic. The site looks official and uses government logos and imagery. However, they are asking for upfront fees and personal banking information. The website URL is similar to official government sites but with slight variations. I believe this is a phishing attempt targeting desperate business owners.',
                'InquiryStatus' => 'Pending Review',
                'SubmitionDate' => Carbon::now()->subHours(12),
                'UserID' => 3,
                'AgencyID' => null,
                'AdminID' => null,
                'InquiryEvidence' => json_encode([
                    'files' => [
                        ['name' => 'website_screenshots.zip', 'type' => 'archive'],
                        ['name' => 'phishing_form.png', 'type' => 'image']
                    ]
                ])
            ],
            [
                'InquiryTitle' => 'False Information About 5G Health Risks',
                'InquiryDescription' => 'A local community group is spreading false information about 5G towers causing cancer and other health issues. They are organizing protests and encouraging people to destroy telecommunications equipment. The group is using doctored scientific studies and fake medical expert testimonials to support their claims. This is causing panic in rural communities and potentially damaging critical infrastructure.',
                'InquiryStatus' => 'Pending Review',
                'SubmitionDate' => Carbon::now()->subHours(6),
                'UserID' => 1,
                'AgencyID' => null,
                'AdminID' => null,
                'InquiryEvidence' => json_encode([
                    'files' => [
                        ['name' => 'telegram_group_messages.pdf', 'type' => 'pdf'],
                        ['name' => 'fake_medical_study.pdf', 'type' => 'pdf'],
                        ['name' => 'protest_flyer.jpg', 'type' => 'image']
                    ]
                ])
            ],
            [
                'InquiryTitle' => 'Impersonation of Government Official on Social Media',
                'InquiryDescription' => 'Someone is impersonating the Minister of Health on Instagram and Twitter, spreading false information about new health policies and making unofficial statements about government decisions. The fake accounts have verified-looking badges and are confusing the public. They are also soliciting donations for fake charity causes using the minister\'s name and likeness.',
                'InquiryStatus' => 'Pending Review',
                'SubmitionDate' => Carbon::now()->subHours(3),
                'UserID' => 2,
                'AgencyID' => null,
                'AdminID' => null,
                'InquiryEvidence' => json_encode([
                    'files' => [
                        ['name' => 'fake_instagram_profile.png', 'type' => 'image'],
                        ['name' => 'fake_twitter_posts.png', 'type' => 'image'],
                        ['name' => 'donation_solicitation.jpg', 'type' => 'image']
                    ]
                ])
            ]
        ];

        foreach ($inquiries as $inquiry) {
            Inquiry::create($inquiry);
        }

        $this->command->info('Created 5 unassigned inquiries for MCMC testing.');
    }
}