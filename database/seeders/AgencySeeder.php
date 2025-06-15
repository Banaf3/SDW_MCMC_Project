<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AgencySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('agencies')->insert([
            [
                'AgencyName' => 'CyberSecurity Malaysia',
                'AgencyEmail' => 'contact@cybersecurity.gov.my',
                'AgencyPhoneNum' => '03-89926888',
                'AgencyType' => 'Cybersecurity',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'AgencyName' => 'Ministry of Health Malaysia (MOH)',
                'AgencyEmail' => 'webmaster@moh.gov.my',
                'AgencyPhoneNum' => '03-88834000',
                'AgencyType' => 'Health',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'AgencyName' => 'Royal Malaysia Police (PDRM)',
                'AgencyEmail' => 'webmaster@rmp.gov.my',
                'AgencyPhoneNum' => '03-21159999',
                'AgencyType' => 'Law Enforcement',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'AgencyName' => 'Ministry of Domestic Trade and Consumer Affairs (KPDN)',
                'AgencyEmail' => 'info@kpdn.gov.my',
                'AgencyPhoneNum' => '03-88825555',
                'AgencyType' => 'Trade and Consumer Affairs',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'AgencyName' => 'Ministry of Education (MOE)',
                'AgencyEmail' => 'webmoe@moe.gov.my',
                'AgencyPhoneNum' => '03-88846000',
                'AgencyType' => 'Education',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'AgencyName' => 'Ministry of Communications and Digital (KKD)',
                'AgencyEmail' => 'webmaster@kkmm.gov.my',
                'AgencyPhoneNum' => '03-88707000',
                'AgencyType' => 'Communications and Digital',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'AgencyName' => 'Department of Islamic Development Malaysia (JAKIM)',
                'AgencyEmail' => 'info@islam.gov.my',
                'AgencyPhoneNum' => '03-88925000',
                'AgencyType' => 'Religious Affairs',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'AgencyName' => 'Election Commission of Malaysia (SPR)',
                'AgencyEmail' => 'info@spr.gov.my',
                'AgencyPhoneNum' => '03-88922525',
                'AgencyType' => 'Electoral',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'AgencyName' => 'Malaysian Anti-Corruption Commission (MACC / SPRM)',
                'AgencyEmail' => 'info@sprm.gov.my',
                'AgencyPhoneNum' => '03-62062000',
                'AgencyType' => 'Anti-Corruption',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'AgencyName' => 'Department of Environment Malaysia (DOE)',
                'AgencyEmail' => 'pro@doe.gov.my',
                'AgencyPhoneNum' => '03-88712111',
                'AgencyType' => 'Environment',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ]);
    }
}
