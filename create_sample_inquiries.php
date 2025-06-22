<?php
require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Inquiry;
use App\Models\Agency;
use App\Models\PublicUser;
use Carbon\Carbon;

try {
    echo "=== CREATING SAMPLE INQUIRIES ===\n\n";
    
    // First check if agencies exist
    $agencies = Agency::all();
    if ($agencies->count() == 0) {
        echo "❌ No agencies found. Please run insert_agencies.php first.\n";
        exit;
    }
    
    // Check if we have users
    $users = PublicUser::all();
    if ($users->count() == 0) {
        echo "Creating sample public users...\n";
        
        $sampleUsers = [
            ['UserName' => 'Ahmad Rahman', 'UserEmail' => 'ahmad@example.com', 'UserPhoneNum' => '012-3456789'],
            ['UserName' => 'Siti Nurhaliza', 'UserEmail' => 'siti@example.com', 'UserPhoneNum' => '012-9876543'],
            ['UserName' => 'David Lim', 'UserEmail' => 'david@example.com', 'UserPhoneNum' => '012-5551234'],
            ['UserName' => 'Priya Devi', 'UserEmail' => 'priya@example.com', 'UserPhoneNum' => '012-7778888'],
        ];
        
        foreach ($sampleUsers as $userData) {
            PublicUser::create([
                'UserName' => $userData['UserName'],
                'UserEmail' => $userData['UserEmail'],
                'UserPhoneNum' => $userData['UserPhoneNum'],
                'Password' => bcrypt('password123'),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
            echo "✓ Created user: {$userData['UserName']}\n";
        }
        
        $users = PublicUser::all();
    }
    
    echo "\nCreating sample inquiries...\n\n";
    
    $sampleInquiries = [
        [
            'title' => 'Fake COVID-19 Vaccine Certificate Scam',
            'description' => 'There are people selling fake COVID-19 vaccination certificates on social media platforms. This is dangerous as it compromises public health safety.',
            'status' => 'Under Investigation',
            'agency_id' => $agencies->where('AgencyName', 'Ministry of Health Malaysia (MOH)')->first()->AgencyID ?? $agencies->first()->AgencyID,
        ],
        [
            'title' => 'Fraudulent Investment Scheme on WhatsApp',
            'description' => 'A WhatsApp group is promoting a get-rich-quick investment scheme that promises 300% returns in 30 days. Many people have lost money.',
            'status' => 'Verified as True',
            'agency_id' => $agencies->where('AgencyName', 'Malaysian Anti-Corruption Commission (MACC / SPRM)')->first()->AgencyID ?? $agencies->first()->AgencyID,
        ],
        [
            'title' => 'Fake University Degree Certificates',
            'description' => 'An online advertisement claims to sell genuine university degree certificates without attending classes. This is affecting the credibility of our education system.',
            'status' => 'Under Investigation',
            'agency_id' => $agencies->where('AgencyName', 'Ministry of Education (MOE)')->first()->AgencyID ?? $agencies->first()->AgencyID,
        ],
        [
            'title' => 'Cyberbullying and Online Harassment',
            'description' => 'A group of individuals are using social media to harass and threaten others. Screenshots and evidence are available.',
            'status' => 'Verified as True',
            'agency_id' => $agencies->where('AgencyName', 'Royal Malaysia Police (PDRM)')->first()->AgencyID ?? $agencies->first()->AgencyID,
        ],
        [
            'title' => 'Fake Product Reviews on E-commerce',
            'description' => 'A company is posting fake positive reviews and fake negative reviews about competitors on various e-commerce platforms.',
            'status' => 'Identified as Fake',
            'agency_id' => $agencies->where('AgencyName', 'Ministry of Domestic Trade and Consumer Affairs (KPDN)')->first()->AgencyID ?? $agencies->first()->AgencyID,
        ],
        [
            'title' => 'Misinformation about Environmental Policies',
            'description' => 'False information is being spread about new environmental regulations, causing unnecessary panic among business owners.',
            'status' => 'Under Investigation',
            'agency_id' => $agencies->where('AgencyName', 'Department of Environment Malaysia (DOE)')->first()->AgencyID ?? $agencies->first()->AgencyID,
        ],
        [
            'title' => 'Fake Government Portal for Document Applications',
            'description' => 'A fake government website is collecting personal information and charging fees for document applications that should be free.',
            'status' => 'Verified as True',
            'agency_id' => $agencies->where('AgencyName', 'CyberSecurity Malaysia')->first()->AgencyID ?? $agencies->first()->AgencyID,
        ],
        [
            'title' => 'False Election Information',
            'description' => 'Misleading information about voting procedures and candidate qualifications is being shared on social media.',
            'status' => 'Rejected',
            'agency_id' => $agencies->where('AgencyName', 'Election Commission of Malaysia (SPR)')->first()->AgencyID ?? $agencies->first()->AgencyID,
        ],
    ];
    
    $created = 0;
    foreach ($sampleInquiries as $index => $inquiryData) {
        $user = $users->random();
        
        $inquiry = Inquiry::create([
            'InquiryTitle' => $inquiryData['title'],
            'InquiryDescription' => $inquiryData['description'],
            'InquiryStatus' => $inquiryData['status'],
            'UserID' => $user->UserID,
            'AgencyID' => $inquiryData['agency_id'],
            'SubmitionDate' => Carbon::now()->subDays(rand(1, 30)),
            'ResolvedDate' => in_array($inquiryData['status'], ['Verified as True', 'Identified as Fake', 'Rejected']) 
                ? Carbon::now()->subDays(rand(1, 15)) 
                : null,
            'AdminComment' => 'Investigation in progress...',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
        
        echo "✓ Created: {$inquiryData['title']}\n";
        echo "  Status: {$inquiryData['status']}\n";
        echo "  Assigned to: " . Agency::find($inquiryData['agency_id'])->AgencyName . "\n";
        echo "  User: {$user->UserName}\n\n";
        
        $created++;
    }
    
    echo "=== SUMMARY ===\n";
    echo "Total inquiries created: $created\n";
    
    // Show final statistics
    $totalInquiries = Inquiry::count();
    echo "Total inquiries in database: $totalInquiries\n\n";
    
    echo "Status distribution:\n";
    $statuses = ['Under Investigation', 'Verified as True', 'Identified as Fake', 'Rejected'];
    foreach ($statuses as $status) {
        $count = Inquiry::where('InquiryStatus', $status)->count();
        echo "- $status: $count\n";
    }
    
    echo "\n✅ Sample inquiries created successfully!\n";
    echo "🌐 Now visit /all-inquiries to see the populated inquiry list.\n";
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    echo "Stack trace: " . $e->getTraceAsString() . "\n";
}
?>
