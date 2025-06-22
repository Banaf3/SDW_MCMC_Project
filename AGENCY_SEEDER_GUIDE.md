# AgencySeeder Application Guide

## Method 1: Using Laravel Artisan Command
Run this command in your terminal from the project root:

```bash
php artisan db:seed --class=AgencySeeder
```

## Method 2: Using Laravel Tinker
1. Open tinker:
```bash
php artisan tinker
```

2. Run these commands in tinker:
```php
DB::table('agencies')->count()  // Check current count
$seeder = new Database\Seeders\AgencySeeder();
$seeder->run();
DB::table('agencies')->count()  // Check new count
exit
```

## Method 3: Direct SQL Insertion
If Laravel commands don't work, you can run this SQL directly in your MySQL database:

```sql
USE mcmc;

INSERT INTO agencies (AgencyName, AgencyEmail, AgencyPhoneNum, AgencyType, created_at, updated_at) VALUES
('CyberSecurity Malaysia', 'contact@cybersecurity.gov.my', '03-89926888', 'Cybersecurity', NOW(), NOW()),
('Ministry of Health Malaysia (MOH)', 'webmaster@moh.gov.my', '03-88834000', 'Health', NOW(), NOW()),
('Royal Malaysia Police (PDRM)', 'webmaster@rmp.gov.my', '03-21159999', 'Law Enforcement', NOW(), NOW()),
('Ministry of Domestic Trade and Consumer Affairs (KPDN)', 'info@kpdn.gov.my', '03-88825555', 'Trade and Consumer Affairs', NOW(), NOW()),
('Ministry of Education (MOE)', 'webmoe@moe.gov.my', '03-88846000', 'Education', NOW(), NOW()),
('Ministry of Communications and Digital (KKD)', 'webmaster@kkmm.gov.my', '03-88707000', 'Communications and Digital', NOW(), NOW()),
('Department of Islamic Development Malaysia (JAKIM)', 'info@islam.gov.my', '03-88925000', 'Religious Affairs', NOW(), NOW()),
('Election Commission of Malaysia (SPR)', 'info@spr.gov.my', '03-88922525', 'Electoral', NOW(), NOW()),
('Malaysian Anti-Corruption Commission (MACC / SPRM)', 'info@sprm.gov.my', '03-62062000', 'Anti-Corruption', NOW(), NOW()),
('Department of Environment Malaysia (DOE)', 'pro@doe.gov.my', '03-88712111', 'Environment', NOW(), NOW());
```

## Method 4: Using phpMyAdmin
1. Open phpMyAdmin (usually at http://localhost/phpmyadmin)
2. Select your 'mcmc' database
3. Go to the 'agencies' table
4. Click "Insert" and manually add each agency record

## Verification
After seeding, verify the data with:
```sql
SELECT COUNT(*) FROM agencies;
SELECT * FROM agencies;
```

You should see 10 agencies in your database after successful seeding.
