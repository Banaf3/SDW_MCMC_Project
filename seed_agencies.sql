-- Use the mcmc database
USE mcmc;

-- Clear existing data (optional - remove this line if you want to keep existing data)
DELETE FROM agencies;

-- Insert agencies directly from AgencySeeder.php data
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

-- Verify the insertion
SELECT COUNT(*) as 'Total Agencies Inserted' FROM agencies;

-- Show all agencies
SELECT 
    AgencyID,
    AgencyName,
    AgencyEmail,
    AgencyPhoneNum,
    AgencyType,
    created_at
FROM agencies 
ORDER BY AgencyID;
