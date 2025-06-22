<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class TempInquirySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */    public function run(): void
    {
        // Clear existing data first - disable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('inquiry_audit_logs')->truncate();
        DB::table('inquiries')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // Sample inquiries with different statuses and agencies
        DB::table('inquiries')->insert([
            [
                'InquiryTitle' => 'Fake COVID-19 Vaccine Information on Social Media',
                'SubmitionDate' => Carbon::now()->subDays(15),
                'InquiryStatus' => 'Under Investigation',
                'StatusHistory' => json_encode([
                    ['status' => 'Submitted', 'date' => Carbon::now()->subDays(15)->format('Y-m-d H:i:s')],
                    ['status' => 'Under Investigation', 'date' => Carbon::now()->subDays(10)->format('Y-m-d H:i:s')]
                ]),
                'InquiryDescription' => 'I received a WhatsApp message claiming that COVID-19 vaccines contain microchips and will make people magnetic. The message includes fake scientific data and urges people not to get vaccinated. This is spreading rapidly in my community and causing vaccine hesitancy.',
                'InquiryEvidence' => 'WhatsApp screenshot, viral video link, fake scientific paper PDF',
                'AdminComment' => 'Assigned to Ministry of Health Malaysia for investigation. High priority due to public health implications.',
                'ResolvedExplanation' => null,
                'ResolvedSupportingDocs' => null,
                'AgencyRejectionComment' => null,                'AgencyID' => 2, // Ministry of Health Malaysia
                'AdminID' => 1,
                'UserID' => 3,
                'created_at' => Carbon::now()->subDays(15),
                'updated_at' => Carbon::now()->subDays(10),
            ],
            [
                'InquiryTitle' => 'Fraudulent Online Investment Scheme Advertisement',
                'SubmitionDate' => Carbon::now()->subDays(12),
                'InquiryStatus' => 'Verified as True',
                'StatusHistory' => json_encode([
                    ['status' => 'Submitted', 'date' => Carbon::now()->subDays(12)->format('Y-m-d H:i:s')],
                    ['status' => 'Under Investigation', 'date' => Carbon::now()->subDays(8)->format('Y-m-d H:i:s')],
                    ['status' => 'Verified as True', 'date' => Carbon::now()->subDays(3)->format('Y-m-d H:i:s')]
                ]),
                'InquiryDescription' => 'I saw Facebook ads promising 300% returns on cryptocurrency investments with minimal risk. The company claims to be regulated but I cannot find them in any official registry. They are asking for upfront payments and personal banking details.',
                'InquiryEvidence' => 'Facebook ad screenshots, company website link, promotional materials',
                'AdminComment' => 'Confirmed as fraudulent scheme. Multiple similar reports received.',
                'ResolvedExplanation' => 'Investigation confirmed this is an unregistered investment scheme violating securities laws. The company is not licensed and uses fake testimonials. We have issued a public warning and are working with authorities to shut down the operation.',
                'ResolvedSupportingDocs' => 'Public warning notice, regulatory violation report, evidence compilation',
                'AgencyRejectionComment' => null,                'AgencyID' => 4, // Ministry of Domestic Trade and Consumer Affairs
                'AdminID' => 2,
                'UserID' => 3,
                'created_at' => Carbon::now()->subDays(12),
                'updated_at' => Carbon::now()->subDays(3),
            ],
            [
                'InquiryTitle' => 'Deepfake Video of Prime Minister Making False Statements',
                'SubmitionDate' => Carbon::now()->subDays(8),
                'InquiryStatus' => 'Identified as Fake',
                'StatusHistory' => json_encode([
                    ['status' => 'Submitted', 'date' => Carbon::now()->subDays(8)->format('Y-m-d H:i:s')],
                    ['status' => 'Under Investigation', 'date' => Carbon::now()->subDays(5)->format('Y-m-d H:i:s')],
                    ['status' => 'Identified as Fake', 'date' => Carbon::now()->subDays(2)->format('Y-m-d H:i:s')]
                ]),
                'InquiryDescription' => 'A video is circulating on TikTok and Twitter showing the Prime Minister allegedly announcing immediate resignation and making controversial statements about government policies. The video quality seems unusual and the audio doesn\'t match lip movements perfectly.',
                'InquiryEvidence' => 'Video file, social media links, technical analysis screenshots',
                'AdminComment' => 'High priority case due to political sensitivity. Requires technical analysis.',
                'ResolvedExplanation' => 'Technical analysis confirmed this is a deepfake video created using AI technology. The original video was from a different speech, and the audio has been artificially generated. We have coordinated with social media platforms for removal.',
                'ResolvedSupportingDocs' => 'Technical analysis report, original video comparison, platform takedown confirmations',
                'AgencyRejectionComment' => null,                'AgencyID' => 6, // Ministry of Communications and Digital
                'AdminID' => 1,
                'UserID' => 3,
                'created_at' => Carbon::now()->subDays(8),
                'updated_at' => Carbon::now()->subDays(2),
            ],
            [
                'InquiryTitle' => 'False Scholarship Opportunity Targeting Students',
                'SubmitionDate' => Carbon::now()->subDays(6),
                'InquiryStatus' => 'Under Investigation',
                'StatusHistory' => json_encode([
                    ['status' => 'Submitted', 'date' => Carbon::now()->subDays(6)->format('Y-m-d H:i:s')],
                    ['status' => 'Under Investigation', 'date' => Carbon::now()->subDays(4)->format('Y-m-d H:i:s')]
                ]),
                'InquiryDescription' => 'My daughter received an email about a RM50,000 scholarship from "Malaysia Education Foundation" requiring a RM500 processing fee. The email uses official-looking logos and letterheads but something feels suspicious about the payment request.',
                'InquiryEvidence' => 'Email screenshot, fake scholarship letter, payment instructions',
                'AdminComment' => 'Forwarded to MOE for verification of legitimacy.',
                'ResolvedExplanation' => null,
                'ResolvedSupportingDocs' => null,
                'AgencyRejectionComment' => null,                'AgencyID' => 5, // Ministry of Education
                'AdminID' => 2,
                'UserID' => 3,
                'created_at' => Carbon::now()->subDays(6),
                'updated_at' => Carbon::now()->subDays(4),
            ],
            [
                'InquiryTitle' => 'Misinformation About New Government Policy on Foreign Workers',
                'SubmitionDate' => Carbon::now()->subDays(4),
                'InquiryStatus' => 'Rejected',
                'StatusHistory' => json_encode([
                    ['status' => 'Submitted', 'date' => Carbon::now()->subDays(4)->format('Y-m-d H:i:s')],
                    ['status' => 'Under Investigation', 'date' => Carbon::now()->subDays(3)->format('Y-m-d H:i:s')],
                    ['status' => 'Rejected', 'date' => Carbon::now()->subDays(1)->format('Y-m-d H:i:s')]
                ]),
                'InquiryDescription' => 'I saw a news article claiming the government will ban all foreign workers starting next month. This is causing panic among my foreign worker friends and I want to verify if this is true.',
                'InquiryEvidence' => 'News article link, social media discussions',
                'AdminComment' => 'Inquiry does not fall under cybersecurity or digital misinformation scope.',
                'ResolvedExplanation' => null,
                'ResolvedSupportingDocs' => null,
                'AgencyRejectionComment' => 'This inquiry relates to labor policy matters which are outside our jurisdiction for digital misinformation verification. Please contact the Ministry of Human Resources directly for official policy clarifications.',                'AgencyID' => 3, // Royal Malaysia Police
                'AdminID' => 1,
                'UserID' => 3,
                'created_at' => Carbon::now()->subDays(4),
                'updated_at' => Carbon::now()->subDays(1),
            ],
            [
                'InquiryTitle' => 'Fake Emergency Alert About Natural Disaster',
                'SubmitionDate' => Carbon::now()->subDays(2),
                'InquiryStatus' => 'Under Investigation',
                'StatusHistory' => json_encode([
                    ['status' => 'Submitted', 'date' => Carbon::now()->subDays(2)->format('Y-m-d H:i:s')],
                    ['status' => 'Under Investigation', 'date' => Carbon::now()->subDays(1)->format('Y-m-d H:i:s')]
                ]),
                'InquiryDescription' => 'Multiple WhatsApp groups are sharing an emergency alert claiming a major earthquake will hit Kuala Lumpur tomorrow at 3 PM. The message includes fake MetMalaysia logos and urges immediate evacuation. This is causing unnecessary panic in my neighborhood.',
                'InquiryEvidence' => 'WhatsApp message screenshots, fake emergency alert image, group chat discussions',
                'AdminComment' => 'Urgent case - coordinating with MetMalaysia and emergency services for verification.',
                'ResolvedExplanation' => null,
                'ResolvedSupportingDocs' => null,
                'AgencyRejectionComment' => null,                'AgencyID' => 10, // Department of Environment Malaysia
                'AdminID' => 2,
                'UserID' => 3,
                'created_at' => Carbon::now()->subDays(2),
                'updated_at' => Carbon::now()->subDays(1),
            ],
        ]);        // Create some audit log entries for timeline tracking
        DB::table('inquiry_audit_logs')->insert([
            // For Inquiry 1
            [
                'InquiryID' => 1,                'Action' => 'Inquiry submitted by public user',
                'PerformedBy' => 'User ID: 3',
                'ActionDate' => Carbon::now()->subDays(15),
                'created_at' => Carbon::now()->subDays(15),
                'updated_at' => Carbon::now()->subDays(15),
            ],
            [
                'InquiryID' => 1,
                'Action' => 'Assigned to Ministry of Health Malaysia for investigation',
                'PerformedBy' => 'Admin ID: 1',
                'ActionDate' => Carbon::now()->subDays(10),
                'created_at' => Carbon::now()->subDays(10),
                'updated_at' => Carbon::now()->subDays(10),
            ],
            [
                'InquiryID' => 1,
                'Action' => 'Investigation in progress - health experts consulted',
                'PerformedBy' => 'Agency ID: 2',
                'ActionDate' => Carbon::now()->subDays(7),
                'created_at' => Carbon::now()->subDays(7),
                'updated_at' => Carbon::now()->subDays(7),
            ],

            // For Inquiry 2
            [
                'InquiryID' => 2,                'Action' => 'Inquiry submitted by public user',
                'PerformedBy' => 'User ID: 3',
                'ActionDate' => Carbon::now()->subDays(12),
                'created_at' => Carbon::now()->subDays(12),
                'updated_at' => Carbon::now()->subDays(12),
            ],
            [
                'InquiryID' => 2,
                'Action' => 'Assigned to Ministry of Domestic Trade and Consumer Affairs',
                'PerformedBy' => 'Admin ID: 1',
                'ActionDate' => Carbon::now()->subDays(8),
                'created_at' => Carbon::now()->subDays(8),
                'updated_at' => Carbon::now()->subDays(8),
            ],
            [
                'InquiryID' => 2,
                'Action' => 'Investigation completed - confirmed as fraudulent scheme',
                'PerformedBy' => 'Agency ID: 4',
                'ActionDate' => Carbon::now()->subDays(3),
                'created_at' => Carbon::now()->subDays(3),
                'updated_at' => Carbon::now()->subDays(3),
            ],

            // For Inquiry 3
            [
                'InquiryID' => 3,                'Action' => 'Inquiry submitted by public user',
                'PerformedBy' => 'User ID: 3',
                'ActionDate' => Carbon::now()->subDays(8),
                'created_at' => Carbon::now()->subDays(8),
                'updated_at' => Carbon::now()->subDays(8),
            ],
            [
                'InquiryID' => 3,
                'Action' => 'Assigned to Ministry of Communications and Digital',
                'PerformedBy' => 'Admin ID: 2',
                'ActionDate' => Carbon::now()->subDays(5),
                'created_at' => Carbon::now()->subDays(5),
                'updated_at' => Carbon::now()->subDays(5),
            ],
            [
                'InquiryID' => 3,
                'Action' => 'Technical analysis confirmed deepfake video',
                'PerformedBy' => 'Agency ID: 6',
                'ActionDate' => Carbon::now()->subDays(2),
                'created_at' => Carbon::now()->subDays(2),
                'updated_at' => Carbon::now()->subDays(2),
            ],
        ]);
    }
}
