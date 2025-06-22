<?php

namespace Database\Seeders;

use App\Models\Agency;
use Illuminate\Database\Seeder;

class TempAgencySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create sample agencies for testing
        $agencies = [
            [
                'AgencyName' => 'Malaysian Communications and Multimedia Commission',
                'AgencyEmail' => 'info@mcmc.gov.my',
                'AgencyPhoneNum' => '03-8688-8000',
                'AgencyType' => 'Regulatory Body'
            ],
            [
                'AgencyName' => 'Jabatan Komunikasi dan Multimedia Malaysia',
                'AgencyEmail' => 'contact@kkmm.gov.my',
                'AgencyPhoneNum' => '03-8000-8000',
                'AgencyType' => 'Government Department'
            ],
            [
                'AgencyName' => 'Suruhanjaya Perkhidmatan Awam Malaysia',
                'AgencyEmail' => 'info@spa.gov.my',
                'AgencyPhoneNum' => '03-8885-5000',
                'AgencyType' => 'Public Service Commission'
            ],
            [
                'AgencyName' => 'Jabatan Audit Negara',
                'AgencyEmail' => 'webmaster@audit.gov.my',
                'AgencyPhoneNum' => '03-2612-8000',
                'AgencyType' => 'Audit Department'
            ],
            [
                'AgencyName' => 'Bank Negara Malaysia',
                'AgencyEmail' => 'info@bnm.gov.my',
                'AgencyPhoneNum' => '03-2698-8044',
                'AgencyType' => 'Central Bank'
            ],
            [
                'AgencyName' => 'Polis Diraja Malaysia',
                'AgencyEmail' => 'webmaster@rmp.gov.my',
                'AgencyPhoneNum' => '03-2266-2222',
                'AgencyType' => 'Law Enforcement'
            ],
            [
                'AgencyName' => 'Suruhanjaya Sekuriti Malaysia',
                'AgencyEmail' => 'enquiry@sc.com.my',
                'AgencyPhoneNum' => '03-6204-8000',
                'AgencyType' => 'Securities Commission'
            ],
            [
                'AgencyName' => 'Jabatan Tenaga Kerja',
                'AgencyEmail' => 'pro@mohr.gov.my',
                'AgencyPhoneNum' => '03-8000-8000',
                'AgencyType' => 'Labour Department'
            ],
            [
                'AgencyName' => 'Majlis Amanah Rakyat',
                'AgencyEmail' => 'info@mara.gov.my',
                'AgencyPhoneNum' => '03-2693-2000',
                'AgencyType' => 'Development Agency'
            ],
            [
                'AgencyName' => 'Jabatan Perkhidmatan Awam',
                'AgencyEmail' => 'info@jpa.gov.my',
                'AgencyPhoneNum' => '03-8888-8020',
                'AgencyType' => 'Public Service Department'
            ]
        ];

        foreach ($agencies as $agencyData) {
            Agency::firstOrCreate([
                'AgencyEmail' => $agencyData['AgencyEmail']
            ], $agencyData);
        }

        $this->command->info('✅ ' . count($agencies) . ' agencies created successfully!');
        $this->command->info('Agencies: MCMC, KKMM, SPA, JAN, BNM, PDRM, SC, JTK, MARA, JPA');
    }
}
