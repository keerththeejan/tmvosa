<?php

namespace App\Helpers;

class Lang
{
    private static array $fields = [
        'full_name_tamil' => ['ta' => 'முழுப்பெயர் (தமிழில்)', 'en' => 'Full Name (Tamil)'],
        'full_name_english' => ['ta' => 'முழுப்பெயர்', 'en' => 'Full Name'],
        'gender' => ['ta' => 'பாலினம்', 'en' => 'Gender'],
        'date_of_birth' => ['ta' => 'பிறந்த திகதி', 'en' => 'Date of Birth'],
        'nic_number' => ['ta' => 'தேசிய அடையாள அட்டை இலக்கம்', 'en' => 'National Identity Card Number'],
        'current_address' => ['ta' => 'தற்போதைய வதிவிட முகவரி', 'en' => 'Current Residential Address'],
        'permanent_address' => ['ta' => 'நிரந்தர முகவரி', 'en' => 'Permanent Address'],
        'country' => ['ta' => 'தற்போது வசிக்கும் நாடு', 'en' => 'Country of Residence'],
        'mobile' => ['ta' => 'கைத்தொலைபேசி இலக்கம்', 'en' => 'Mobile Number'],
        'whatsapp' => ['ta' => 'வாட்ஸ்அப் இலக்கம்', 'en' => 'WhatsApp Number'],
        'email' => ['ta' => 'மின்னஞ்சல் முகவரி', 'en' => 'Email Address'],
        'studied_period' => ['ta' => 'கல்வி கற்ற காலம்', 'en' => 'Period Studied at School'],
        'studied_from_year' => ['ta' => 'கல்வி தொடங்கிய ஆண்டு', 'en' => 'From Year'],
        'studied_to_year' => ['ta' => 'கல்வி முடிந்த ஆண்டு (பைச்சு)', 'en' => 'To Year (Batch)'],
        'grade_stream' => ['ta' => 'கல்வி கற்ற பிரிவு', 'en' => 'Grade / Stream'],
        'teacher_name' => ['ta' => 'ஆசிரியர் பெயர்', 'en' => 'Teacher Name'],
        'occupation' => ['ta' => 'தற்போதைய தொழில்', 'en' => 'Current Occupation'],
        'company' => ['ta' => 'பணிபுரியும் நிறுவனம்', 'en' => 'Organization / Company'],
        'membership_type' => ['ta' => 'உறுப்பினர் வகை', 'en' => 'Membership Type'],
        'validity_period' => ['ta' => 'செல்லுபடியாகும் காலம்', 'en' => 'Validity Period'],
        'amount_paid' => ['ta' => 'செலுத்தப்பட்ட தொகை', 'en' => 'Amount Paid'],
        'payment_method' => ['ta' => 'கட்டணம் செலுத்திய முறை', 'en' => 'Payment Method'],
        'transaction_number' => ['ta' => 'பரிவர்த்தனை இலக்கம்', 'en' => 'Transaction Number'],
        'payment_date' => ['ta' => 'கட்டணம் செலுத்திய திகதி', 'en' => 'Payment Date'],
        'passport_photo' => ['ta' => 'கடவுச்சீட்டு அளவு புகைப்படம்', 'en' => 'Passport Size Photograph'],
        'nic_copy' => ['ta' => 'தேசிய அடையாள அட்டை நகல்', 'en' => 'NIC Copy'],
        'payment_slip' => ['ta' => 'கட்டணம் செலுத்திய சான்று', 'en' => 'Payment Slip'],
        'declaration' => ['ta' => 'உறுதிமொழி ஏற்றுக்கொள்கிறேன்', 'en' => 'I Agree to the Declaration'],
        'proposer_name' => ['ta' => 'பரிந்துரைப்பவர் பெயர்', 'en' => 'Proposer Name'],
        'proposer_contact' => ['ta' => 'பரிந்துரைப்பவர் தொடர்பு இலக்கம்', 'en' => 'Proposer Contact Number'],
        'username' => ['ta' => 'பயனர்பெயர் அல்லது மின்னஞ்சல்', 'en' => 'Username or Email'],
        'password' => ['ta' => 'கடவுச்சொல்', 'en' => 'Password'],
        'membership_number' => ['ta' => 'உறுப்பினர் இலக்கம்', 'en' => 'Membership Number'],
        'status' => ['ta' => 'நிலை', 'en' => 'Status'],
        'search' => ['ta' => 'தேடல்', 'en' => 'Search'],
        'bank_name' => ['ta' => 'வங்கி பெயர்', 'en' => 'Bank Name'],
        'branch' => ['ta' => 'கிளை', 'en' => 'Branch'],
        'account_name' => ['ta' => 'கணக்குப் பெயர்', 'en' => 'Account Name'],
        'account_number' => ['ta' => 'கணக்கு இலக்கம்', 'en' => 'Account Number'],
        'address' => ['ta' => 'முகவரி', 'en' => 'Address'],
        'role' => ['ta' => 'பாத்திரம்', 'en' => 'Role'],
        'photo' => ['ta' => 'புகைப்படம்', 'en' => 'Photo'],
    ];

    private static array $ui = [
        'app_title' => ['ta' => 'பழைய மாணவர் சங்க உறுப்பினர் விண்ணப்பம்', 'en' => 'OSA Membership Application'],
        'app_subtitle' => ['ta' => 'கிளிநொச்சி / திருவையாறு மகா வித்தியாலயம் பழைய மாணவர் சங்கம்', 'en' => 'Kilinochchi / Thiruvaiyaru Maha Vidyalayam Old Students\' Association'],
        'step_bank' => ['ta' => 'வங்கி கணக்கு விபரங்கள்', 'en' => 'Bank Account Details'],
        'step_personal' => ['ta' => 'தனிப்பட்ட தகவல்கள்', 'en' => 'Personal Information'],
        'step_education' => ['ta' => 'கல்வி மற்றும் உறுப்பினர் தகவல்கள்', 'en' => 'Educational & Membership Information'],
        'step_education_professional' => ['ta' => 'கல்வி மற்றும் தொழில் தகவல்கள்', 'en' => 'Educational & Professional Information'],
        'step_documents_card' => ['ta' => 'ஆவணங்கள்', 'en' => 'Documents'],
        'step_declaration_card' => ['ta' => 'உறுதிமொழி', 'en' => 'Declaration'],
        'step_documents' => ['ta' => 'ஆவணங்கள் மற்றும் உறுதிப்படுத்தல்', 'en' => 'Documents & Declaration'],
        'start_application' => ['ta' => 'விண்ணப்பத்தை தொடங்கவும்', 'en' => 'Start Application'],
        'payment_info' => ['ta' => 'கட்டண தகவல்கள்', 'en' => 'Payment Information'],
        'sign_in' => ['ta' => 'உள்நுழைவு', 'en' => 'Sign In'],
        'apply_membership' => ['ta' => 'உறுப்பினர் விண்ணப்பம்', 'en' => 'Apply Membership'],
        'track_status' => ['ta' => 'விண்ணப்ப நிலை', 'en' => 'Track Application'],
        'welcome_title' => ['ta' => 'பழைய மாணவர் சங்கத்திற்கு வரவேற்கிறோம்', 'en' => 'Welcome to the Old Students\' Association'],
        'welcome_text' => [
            'ta' => 'பழைய மாணவர் சமூகத்தில் இணையுங்கள், உங்கள் தகவல்களை புதுப்பிக்கவும், பாடசாலை வளர்ச்சிக்கு பங்களிக்கவும், தொடர்பில் இருங்கள்.',
            'en' => 'Join the alumni community, update your information, contribute to school development, and stay connected.',
        ],
        'membership_information' => ['ta' => 'உறுப்பினர் தகவல்கள்', 'en' => 'Membership Information'],
        'hero_alt' => [
            'ta' => 'கிளி / திருவையாறு மகா வித்தியாலயம் பழைய மாணவர் சங்கம்',
            'en' => 'Kilinochchi / Thiruvaiyaru Maha Vidyalayam Old Students\' Association',
        ],
        'not_uploaded' => ['ta' => 'பதிவேற்றப்படவில்லை', 'en' => 'Not Uploaded'],
        'upload_later_hint' => [
            'ta' => 'பின்னர் பதிவேற்றலாம் அல்லது உறுப்பினரிடம் கோரலாம்.',
            'en' => 'Can be uploaded later or requested from the applicant.',
        ],
        'upload_document' => ['ta' => 'ஆவணத்தை பதிவேற்று', 'en' => 'Upload Document'],
        'view_document' => ['ta' => 'ஆவணத்தை காண்க', 'en' => 'View Document'],
        'uploaded' => ['ta' => 'பதிவேற்றப்பட்டது', 'en' => 'Uploaded'],
        'documents' => ['ta' => 'ஆவணங்கள்', 'en' => 'Documents'],
        'file_upload_success' => [
            'ta' => 'கோப்பு வெற்றிகரமாக பதிவேற்றப்பட்டது.',
            'en' => 'File uploaded successfully.',
        ],
        'file_size_error' => [
            'ta' => 'கோப்பின் அளவு 10MB ஐ விட அதிகமாக இருக்கக்கூடாது.',
            'en' => 'File size must be less than 10MB.',
        ],
        'file_type_error' => [
            'ta' => 'JPG, PNG, WEBP அல்லது PDF மட்டுமே அனுமதிக்கப்படும்.',
            'en' => 'Only JPG, PNG, WEBP, or PDF files are allowed.',
        ],
        'file_required_error' => [
            'ta' => 'இந்த ஆவணத்தை பதிவேற்றவும்.',
            'en' => 'Please upload this document.',
        ],
        'developed_by' => ['ta' => 'வடிவமைப்பு:', 'en' => 'Developed by'],
        'begin_application' => ['ta' => 'விண்ணப்ப படிவம்', 'en' => 'Application Form'],
        'dob_invalid' => [
            'ta' => 'தயவுசெய்து சரியான பிறந்த திகதியை உள்ளிடவும்.',
            'en' => 'Please enter a valid Date of Birth.',
        ],
        'previous' => ['ta' => 'முந்தையது', 'en' => 'Previous'],
        'next' => ['ta' => 'அடுத்தது', 'en' => 'Next'],
        'submit' => ['ta' => 'சமர்ப்பிக்கவும்', 'en' => 'Submit'],
        'track_application' => ['ta' => 'விண்ணப்பத்தை கண்காணிக்கவும்', 'en' => 'Track Application'],
        'track' => ['ta' => 'கண்காணிக்கவும்', 'en' => 'Track Application'],
        'select' => ['ta' => 'தேர்வு செய்யவும்', 'en' => 'Select'],
        'select_country' => ['ta' => 'நாட்டைத் தேர்வு செய்யவும்', 'en' => 'Select Country'],
        'declaration_text_ta' => 'மேலே வழங்கியுள்ள தகவல்கள் அனைத்தும் உண்மையானவை என உறுதியளிக்கிறேன்.',
        'declaration_text_en' => 'I hereby declare that the information provided above is true and accurate.',
        'ordinary_member' => ['ta' => 'சாதாரண உறுப்பினர்', 'en' => 'Ordinary Member'],
        'ten_year_member' => ['ta' => '10 ஆண்டு உறுப்பினர்', 'en' => '10-Year Membership'],
        'half_year_member' => ['ta' => 'அரை ஆண்டு உறுப்பினர்', 'en' => 'Half-Year Membership'],
        'validity_1_year' => ['ta' => '1 ஆண்டு', 'en' => '1 Year'],
        'validity_10_years' => ['ta' => '10 ஆண்டுகள்', 'en' => '10 Years'],
        'validity_6_months' => ['ta' => '6 மாதங்கள் (180 நாட்கள்)', 'en' => '6 Months (180 Days)'],
        'fee_label' => ['ta' => 'கட்டணம்', 'en' => 'Fee'],
        'selected_badge' => ['ta' => 'தேர்ந்தெடுக்கப்பட்டது', 'en' => 'Selected'],
        'selected_fee' => ['ta' => 'தேர்ந்தெடுக்கப்பட்ட சந்தா', 'en' => 'Selected Fee'],
        'upload_hint' => ['ta' => 'பதிவேற்ற அல்லது புகைப்படம் எடுக்க தட்டவும்', 'en' => 'Tap to upload or take photo'],
        'dashboard' => ['ta' => 'கட்டுப்பாட்டு பலகை', 'en' => 'Dashboard'],
        'applications' => ['ta' => 'விண்ணப்பங்கள்', 'en' => 'Applications'],
        'application_management' => ['ta' => 'விண்ணப்ப முகாமைத்துவம்', 'en' => 'Application Management'],
        'members' => ['ta' => 'உறுப்பினர்கள்', 'en' => 'Members'],
        'member_management' => ['ta' => 'உறுப்பினர் முகாமைத்துவம்', 'en' => 'Member Management'],
        'payments' => ['ta' => 'கட்டணங்கள்', 'en' => 'Payments'],
        'payment_management' => ['ta' => 'கட்டண முகாமைத்துவம்', 'en' => 'Payment Management'],
        'reports' => ['ta' => 'அறிக்கைகள்', 'en' => 'Reports'],
        'users' => ['ta' => 'பயனர்கள்', 'en' => 'Users'],
        'user_management' => ['ta' => 'பயனர் முகாமைத்துவம்', 'en' => 'User Management'],
        'settings' => ['ta' => 'அமைப்புகள்', 'en' => 'Settings'],
        'audit_logs' => ['ta' => 'பதிவுகள்', 'en' => 'Audit Logs'],
        'logout' => ['ta' => 'வெளியேறு', 'en' => 'Logout'],
        'add_member' => ['ta' => 'உறுப்பினரைச் சேர்', 'en' => 'Add Member'],
        'edit_member' => ['ta' => 'உறுப்பினரைத் திருத்து', 'en' => 'Edit Member'],
        'member_details' => ['ta' => 'உறுப்பினர் விவரங்கள்', 'en' => 'Member Details'],
        'total_members' => ['ta' => 'மொத்த உறுப்பினர்கள்', 'en' => 'Total Members'],
        'active' => ['ta' => 'செயலில்', 'en' => 'Active'],
        'pending_apps' => ['ta' => 'நிலுவையில் உள்ள விண்ணப்பங்கள்', 'en' => 'Pending Applications'],
        'monthly_revenue' => ['ta' => 'மாத வருவாய்', 'en' => 'Monthly Revenue'],
        'total_revenue' => ['ta' => 'மொத்த வருவாய்', 'en' => 'Total Revenue'],
        'expiring_soon' => ['ta' => 'விரைவில் காலாவதியாகும்', 'en' => 'Expiring Soon'],
        'this_month' => ['ta' => 'இந்த மாதம்', 'en' => 'This Month'],
        'outstanding' => ['ta' => 'நிலுவைத் தொகை', 'en' => 'Outstanding'],
        'membership_growth' => ['ta' => 'உறுப்பினர் வளர்ச்சி', 'en' => 'Membership Growth'],
        'revenue_growth' => ['ta' => 'வருவாய் வளர்ச்சி', 'en' => 'Revenue Growth'],
        'country_distribution' => ['ta' => 'நாடு வாரியான பரவல்', 'en' => 'Country Distribution'],
        'membership_types' => ['ta' => 'உறுப்பினர் வகைகள்', 'en' => 'Membership Types'],
        'membership_card' => ['ta' => 'உறுப்பினர் அட்டை', 'en' => 'Membership Card'],
        'download_pdf' => ['ta' => 'PDF பதிவிறக்கம்', 'en' => 'Download PDF'],
        'download_image' => ['ta' => 'படம் பதிவிறக்கம்', 'en' => 'Download Image'],
        'share_whatsapp' => ['ta' => 'வாட்ஸ்அப்பில் பகிர்', 'en' => 'Share via WhatsApp'],
        'share_email' => ['ta' => 'மின்னஞ்சலில் பகிர்', 'en' => 'Share via Email'],
        'valid_until' => ['ta' => 'செல்லுபடியாகும் திகதி', 'en' => 'Valid Until'],
        'male' => ['ta' => 'ஆண்', 'en' => 'Male'],
        'female' => ['ta' => 'பெண்', 'en' => 'Female'],
        'other' => ['ta' => 'பிற', 'en' => 'Other'],
        'bank_transfer' => ['ta' => 'வங்கி பரிமாற்றம்', 'en' => 'Bank Transfer'],
        'cash' => ['ta' => 'பணம்', 'en' => 'Cash'],
        'online' => ['ta' => 'ஆன்லைன் கட்டணம்', 'en' => 'Online Payment'],
        'cheque' => ['ta' => 'காசோலை', 'en' => 'Cheque'],
        'review_submit' => ['ta' => 'சரிபார்த்து சமர்ப்பிக்கவும்', 'en' => 'Review & Submit'],
        'home' => ['ta' => 'முகப்பு', 'en' => 'Home'],
        'about' => ['ta' => 'எங்களைப் பற்றி', 'en' => 'About'],
        'membership' => ['ta' => 'உறுப்பினர்', 'en' => 'Membership'],
        'membership_plans' => ['ta' => 'உறுப்பினர் திட்டங்கள்', 'en' => 'Membership Plans'],
        'benefits' => ['ta' => 'நன்மைகள்', 'en' => 'Benefits'],
        'why_join' => ['ta' => 'ஏன் சேர வேண்டும்', 'en' => 'Why Join'],
        'member_benefits' => ['ta' => 'உறுப்பினர் நன்மைகள்', 'en' => 'Member Benefits'],
        'gallery' => ['ta' => 'புகைப்படங்கள்', 'en' => 'Gallery'],
        'events' => ['ta' => 'நிகழ்வுகள்', 'en' => 'Events'],
        'news' => ['ta' => 'செய்திகள்', 'en' => 'News'],
        'contact' => ['ta' => 'தொடர்பு', 'en' => 'Contact'],
        'verify_member' => ['ta' => 'உறுப்பினர் சரிபார்ப்பு', 'en' => 'Verify Member'],
        'verify_membership' => ['ta' => 'உறுப்பினரைச் சரிபார்', 'en' => 'Verify Membership'],
        'apply_now' => ['ta' => 'இப்போது விண்ணப்பிக்க', 'en' => 'Apply Now'],
        'member_login' => ['ta' => 'உறுப்பினர் உள்நுழைவு', 'en' => 'Member Login'],
        'learn_more' => ['ta' => 'மேலும் அறிய', 'en' => 'Learn More'],
        'language' => ['ta' => 'மொழி', 'en' => 'Language'],
        'language_tamil' => ['ta' => 'தமிழ்', 'en' => 'Tamil'],
        'language_english' => ['ta' => 'ஆங்கிலம்', 'en' => 'English'],
        'membership_cards' => ['ta' => 'உறுப்பினர் அட்டைகள்', 'en' => 'Membership Cards'],
        'duplicate_nics' => ['ta' => 'நகல் NIC', 'en' => 'Duplicate NICs'],
        'email_settings' => ['ta' => 'மின்னஞ்சல் அமைப்புகள்', 'en' => 'Email Settings'],
        'change_password' => ['ta' => 'கடவுச்சொல்லை மாற்று', 'en' => 'Change Password'],
        'cards_short' => ['ta' => 'அட்டை', 'en' => 'Cards'],
        'hero_system_title' => ['ta' => 'OSA பழைய மாணவர் உறுப்பினர் அமைப்பு', 'en' => 'OSA Alumni Membership System'],
        'hero_lead' => [
            'ta' => 'உங்கள் வகுப்புத் தோழர்களுடன் மீண்டும் இணையுங்கள், பாடசாலையை ஆதரியுங்கள், உறுப்பினரைப் புதுப்பித்து வளர்ந்து வரும் பழைய மாணவர் சமூகத்தின் பகுதியாகுங்கள்.',
            'en' => 'Reconnect with your classmates, support your school, renew your membership, and become part of a growing alumni community.',
        ],
        'hero_kicker_line1' => ['ta' => 'பழைய மாணவர் சங்கம்', 'en' => 'Old Students\' Association'],
        'hero_kicker_line2' => ['ta' => 'திருவையாறு மகா வித்தியாலயம்', 'en' => 'Thiruvaiyaru Maha Vidyalayam'],
        'stat_members' => ['ta' => 'உறுப்பினர்கள்', 'en' => 'Members'],
        'stat_applications' => ['ta' => 'விண்ணப்பங்கள்', 'en' => 'Applications'],
        'stat_events' => ['ta' => 'நிகழ்வுகள்', 'en' => 'Events'],
        'stat_years' => ['ta' => 'ஆண்டுகள்', 'en' => 'Years'],
        'apps_short' => ['ta' => 'விண்ணப்பம்', 'en' => 'Apps'],
        'pay_short' => ['ta' => 'கட்டணம்', 'en' => 'Pay'],
        'bank_account_details' => ['ta' => 'சங்க வங்கி கணக்கு விபரங்கள்', 'en' => 'Association Bank Account Details'],
        'official_bank_details' => ['ta' => 'சங்கத்தின் உத்தியோகபூர்வ வங்கி கணக்கு விபரங்கள்', 'en' => 'Official Association Bank Account Details'],
        'membership_fee' => ['ta' => 'உறுப்பினர் கட்டணம்', 'en' => 'Membership Fee'],
        'important_notice' => ['ta' => 'முக்கிய அறிவிப்பு', 'en' => 'Important Notice'],
        'payment_notice_ta' => 'விண்ணப்பத்தை சமர்ப்பிப்பதற்கு முன் உறுப்பினர் கட்டணத்தை மேலே குறிப்பிடப்பட்டுள்ள சங்கத்தின் உத்தியோகபூர்வ வங்கி கணக்கில் செலுத்தவும்.',
        'payment_notice_en' => 'Please transfer the membership fee to the official Association Bank Account before submitting the application.',
        'copy_account_number' => ['ta' => 'கணக்கு இலக்கத்தை நகலெடு', 'en' => 'Copy Account Number'],
        'copy_account_name' => ['ta' => 'கணக்குப் பெயரை நகலெடு', 'en' => 'Copy Account Name'],
        'copied' => ['ta' => 'நகலெடுக்கப்பட்டது!', 'en' => 'Copied!'],
        'membership_fee_details' => ['ta' => 'உறுப்பினர் கட்டண விபரங்கள்', 'en' => 'Membership Fee Details'],
        'payment_instructions' => ['ta' => 'கட்டணம் செலுத்தும் வழிமுறைகள்', 'en' => 'Payment Instructions'],
        'member_reports' => ['ta' => 'உறுப்பினர் அறிக்கைகள்', 'en' => 'Member Reports'],
        'financial_reports' => ['ta' => 'நிதி அறிக்கைகள்', 'en' => 'Financial Reports'],
        'alumni_reports' => ['ta' => 'பழைய மாணவர் அறிக்கைகள்', 'en' => 'Alumni Reports'],
        'generate' => ['ta' => 'உருவாக்கு', 'en' => 'Generate'],
        'verify' => ['ta' => 'சரிபார்', 'en' => 'Verify'],
        'verify_payment' => ['ta' => 'கட்டணத்தை சரிபார்', 'en' => 'Verify Payment'],
        'save' => ['ta' => 'சேமி', 'en' => 'Save'],
        'cancel' => ['ta' => 'ரத்து', 'en' => 'Cancel'],
        'add_user' => ['ta' => 'பயனரைச் சேர்', 'en' => 'Add User'],
        'no_applications' => ['ta' => 'விண்ணப்பங்கள் எதுவும் இல்லை', 'en' => 'No applications found'],
        'no_members' => ['ta' => 'உறுப்பினர்கள் எவரும் இல்லை', 'en' => 'No members found'],
        'go_dashboard' => ['ta' => 'கட்டுப்பாட்டு பலகைக்கு செல்ல', 'en' => 'Go to Dashboard'],
        'page_not_found' => ['ta' => 'பக்கம் கிடைக்கவில்லை', 'en' => 'Page Not Found'],
        'all_status' => ['ta' => 'அனைத்து நிலைகள்', 'en' => 'All Status'],
        'all_types' => ['ta' => 'அனைத்து வகைகள்', 'en' => 'All Types'],
        'application_details' => ['ta' => 'விண்ணப்ப விவரங்கள்', 'en' => 'Application Details'],
        'approve' => ['ta' => 'அனுமதி', 'en' => 'Approve'],
        'reject' => ['ta' => 'நிராகரி', 'en' => 'Reject'],
        'pending' => ['ta' => 'நிலுவை', 'en' => 'Pending'],
        'approved' => ['ta' => 'அனுமதிக்கப்பட்டது', 'en' => 'Approved'],
        'rejected' => ['ta' => 'நிராகரிக்கப்பட்டது', 'en' => 'Rejected'],
        'under_review' => ['ta' => 'பரிசீலனையில்', 'en' => 'Under Review'],
        'all' => ['ta' => 'அனைத்தும்', 'en' => 'All'],
        'daily' => ['ta' => 'தினசரி', 'en' => 'Daily'],
        'monthly' => ['ta' => 'மாதாந்தம்', 'en' => 'Monthly'],
        'yearly' => ['ta' => 'வருடாந்தம்', 'en' => 'Yearly'],
        'view' => ['ta' => 'காண்க', 'en' => 'View'],
        'collection' => ['ta' => 'வசூல்', 'en' => 'Collection'],
        'country_wise' => ['ta' => 'நாடு வாரியாக', 'en' => 'Country Wise'],
        'batch_wise' => ['ta' => 'பைச்சு வாரியாக', 'en' => 'Batch Wise'],
        'occupation_wise' => ['ta' => 'தொழில் வாரியாக', 'en' => 'Occupation Wise'],
        'members_found' => ['ta' => 'உறுப்பினர்கள் கிடைத்தனர்', 'en' => 'members found'],
        'auto_generated' => ['ta' => 'தானாக உருவாக்கப்படும்', 'en' => 'auto-generated if empty'],
        'update_photo' => ['ta' => 'புகைப்படத்தை புதுப்பிக்கவும்', 'en' => 'Update Photo'],
        'personal_info' => ['ta' => 'தனிப்பட்ட தகவல்கள்', 'en' => 'Personal Information'],
        'contact_info' => ['ta' => 'தொடர்பு தகவல்கள்', 'en' => 'Contact Information'],
        'contact_for_inquiries' => ['ta' => 'தொடர்புகளுக்கு', 'en' => 'For Inquiries'],
        'contact_inquiries_text' => [
            'ta' => 'இது தொடர்பாக ஏதேனும் விளக்கம் அல்லது மேலதிக தகவல்கள் தேவைப்படின், தயவுசெய்து செயலாளர் அவர்களை கீழ்க்காணும் தொலைபேசி இலக்கத்தில் தொடர்புகொள்ளவும்.',
            'en' => 'For any clarification or additional information regarding this application, please contact the Secretary using the telephone number below.',
        ],
        'secretary' => ['ta' => 'செயலாளர்', 'en' => 'Secretary'],
        'membership_info' => ['ta' => 'உறுப்பினர் தகவல்கள்', 'en' => 'Membership Information'],
    ];

    private static array $bank = [
        'bank_name' => 'Bank of Ceylon (BOC)',
        'branch' => 'Kilinochchi Kachcheri Extension Branch',
        'account_name' => 'STUDENT ASSOCIATION TMV',
        'account_number' => '93013617',
        'address_ta' => 'கிளிநொச்சி திருவையாறு மகா வித்தியாலயம், திருவையாறு, கிளிநொச்சி 42400',
        'address_en' => 'KN Thiruvaiyaru Maha Vidyalayam, Thiruvaiyaru, Kilinochchi 42400',
    ];

    private static array $applicationContact = [
        'phone_display' => '077 887 0135',
        'phone_tel' => '0778870135',
        'email' => 'tmvosa@vkitnet.info',
    ];

    private static array $paymentSteps = [
        ['ta' => 'மேலே குறிப்பிடப்பட்டுள்ள சங்க வங்கி கணக்கிற்கு கட்டணத்தை செலுத்தவும்.', 'en' => 'Transfer the fee to the official association bank account.'],
        ['ta' => 'பணம் செலுத்திய சான்றை பதிவேற்றவும்.', 'en' => 'Upload the payment slip.'],
        ['ta' => 'பரிவர்த்தனை இலக்கத்தை உள்ளிடவும்.', 'en' => 'Enter the transaction number.'],
        ['ta' => 'விண்ணப்பத்தை சமர்ப்பிக்கவும்.', 'en' => 'Submit the application.'],
    ];

    private static array $placeholders = [
        'full_name_tamil' => ['ta' => 'முழுப்பெயரை உள்ளிடவும்', 'en' => 'Enter Full Name'],
        'full_name_english' => ['ta' => 'ஆங்கிலப் பெயரை உள்ளிடவும்', 'en' => 'Enter English Name'],
        'nic_number' => ['ta' => 'தேசிய அடையாள அட்டை இலக்கத்தை உள்ளிடவும்', 'en' => 'Enter NIC Number'],
        'current_address' => ['ta' => 'தற்போதைய முகவரியை உள்ளிடவும்', 'en' => 'Enter Current Address'],
        'permanent_address' => ['ta' => 'நிரந்தர முகவரியை உள்ளிடவும்', 'en' => 'Enter Permanent Address'],
        'mobile' => ['ta' => 'கைத்தொலைபேசி இலக்கத்தை உள்ளிடவும்', 'en' => 'Enter Mobile Number'],
        'whatsapp' => ['ta' => 'வாட்ஸ்அப் இலக்கத்தை உள்ளிடவும்', 'en' => 'Enter WhatsApp Number'],
        'email' => ['ta' => 'மின்னஞ்சலை உள்ளிடவும்', 'en' => 'Enter Email Address'],
        'studied_from_year' => ['ta' => 'தொடக்க ஆண்டு', 'en' => 'From Year'],
        'studied_to_year' => ['ta' => 'முடிந்த ஆண்டு (பைச்சு)', 'en' => 'To Year (Batch)'],
        'grade_stream' => ['ta' => 'அறிவியல், கலை', 'en' => 'Science, Arts'],
        'teacher_name' => ['ta' => 'ஆசிரியர் பெயரை உள்ளிடவும்', 'en' => 'Enter Teacher Name'],
        'occupation' => ['ta' => 'தொழிலை உள்ளிடவும்', 'en' => 'Enter Occupation'],
        'company' => ['ta' => 'நிறுவனத்தை உள்ளிடவும்', 'en' => 'Enter Organization'],
        'transaction_number' => ['ta' => 'பரிவர்த்தனை இலக்கத்தை உள்ளிடவும்', 'en' => 'Enter Transaction Number'],
        'date_of_birth' => ['ta' => 'DD / MM / YYYY', 'en' => 'DD / MM / YYYY'],
        'proposer_name' => ['ta' => 'பரிந்துரைப்பவர் பெயரை உள்ளிடவும்', 'en' => 'Enter Proposer Name'],
        'proposer_contact' => ['ta' => 'தொடர்பு இலக்கத்தை உள்ளிடவும்', 'en' => 'Enter Contact Number'],
    ];

    public const LOCALE_COOKIE = 'osa_lang';
    public const LOCALE_COOKIE_ALT = 'language';
    public const SESSION_KEY = 'language';

    private static bool $booted = false;
    private static ?string $cachedLocale = null;
    /** @var array<string, array<string, string>> */
    private static array $dictionaries = [];

    public static function boot(): void
    {
        if (self::$booted) {
            return;
        }
        self::$booted = true;

        $cookie = self::normalizeLocale(
            $_COOKIE[self::LOCALE_COOKIE]
                ?? $_COOKIE[self::LOCALE_COOKIE_ALT]
                ?? null
        );
        $session = self::normalizeLocale($_SESSION[self::SESSION_KEY] ?? null);

        // Cookie wins when present (language switcher writes cookie then reloads).
        $lang = $cookie ?? $session ?? 'ta';

        self::$cachedLocale = $lang;
        if (session_status() === PHP_SESSION_ACTIVE) {
            $_SESSION[self::SESSION_KEY] = $lang;
        }
    }

    public static function setLocale(string $lang): void
    {
        $lang = self::normalizeLocale($lang) ?? 'ta';
        self::$cachedLocale = $lang;
        if (session_status() === PHP_SESSION_ACTIVE) {
            $_SESSION[self::SESSION_KEY] = $lang;
        }
        self::writeCookies($lang);
    }

    public static function locale(): string
    {
        if (!self::$booted) {
            self::boot();
        }
        return self::$cachedLocale ?? 'ta';
    }

    public static function isEnglish(): bool
    {
        return self::locale() === 'en';
    }

    public static function isTamil(): bool
    {
        return self::locale() === 'ta';
    }

    /**
     * Translate a key from languages/{locale}.php
     */
    public static function get(string $key, ?string $default = null): string
    {
        $locale = self::locale();
        $dict = self::dictionary($locale);
        if (isset($dict[$key]) && $dict[$key] !== '') {
            return $dict[$key];
        }

        $fallbackLocale = $locale === 'en' ? 'ta' : 'en';
        $fallback = self::dictionary($fallbackLocale);
        if (isset($fallback[$key]) && $fallback[$key] !== '') {
            return $fallback[$key];
        }

        // Legacy in-memory fallbacks
        if (isset(self::$ui[$key])) {
            $v = self::$ui[$key];
            if (is_string($v)) {
                return $v;
            }
            return self::pick($v);
        }
        if (isset(self::$fields[$key])) {
            return self::pick(self::$fields[$key]);
        }

        return $default ?? $key;
    }

    /** @param array{ta?:string,en?:string}|string $pair */
    public static function pick(array|string $pair): string
    {
        if (is_string($pair)) {
            return $pair;
        }
        $loc = self::locale();
        if (!empty($pair[$loc])) {
            return (string) $pair[$loc];
        }
        return (string) ($pair['ta'] ?? $pair['en'] ?? '');
    }

    public static function field(string $key): array
    {
        $legacy = self::$fields[$key] ?? null;
        return [
            'ta' => self::dictionary('ta')['field_' . $key]
                ?? ($legacy['ta'] ?? $key),
            'en' => self::dictionary('en')['field_' . $key]
                ?? ($legacy['en'] ?? $key),
        ];
    }

    public static function ui(string $key): array|string
    {
        $value = self::$ui[$key] ?? null;
        if (is_string($value)) {
            // declaration_text_ta / payment_notice_ta style legacy keys
            return $value;
        }

        return [
            'ta' => self::dictionary('ta')[$key] ?? ($value['ta'] ?? $key),
            'en' => self::dictionary('en')[$key] ?? ($value['en'] ?? $key),
        ];
    }

    public static function placeholder(string $key): string
    {
        $fromFile = self::get('ph_' . $key, '');
        if ($fromFile !== '' && $fromFile !== 'ph_' . $key) {
            return $fromFile;
        }
        $p = self::$placeholders[$key] ?? ['ta' => '', 'en' => ''];
        return self::pick($p);
    }

    /** @return array<string, string> */
    private static function dictionary(string $locale): array
    {
        $locale = self::normalizeLocale($locale) ?? 'ta';
        if (isset(self::$dictionaries[$locale])) {
            return self::$dictionaries[$locale];
        }

        $path = dirname(__DIR__, 2) . '/languages/' . $locale . '.php';
        $loaded = [];
        if (is_file($path)) {
            $data = require $path;
            if (is_array($data)) {
                foreach ($data as $k => $v) {
                    $loaded[(string) $k] = (string) $v;
                }
            }
        }
        self::$dictionaries[$locale] = $loaded;
        return $loaded;
    }

    private static function normalizeLocale(mixed $raw): ?string
    {
        if (!is_string($raw) || $raw === '') {
            return null;
        }
        $raw = strtolower(trim($raw));
        if ($raw === 'en' || $raw === 'english') {
            return 'en';
        }
        if ($raw === 'ta' || $raw === 'tamil' || $raw === 'tam') {
            return 'ta';
        }
        return null;
    }

    private static function writeCookies(string $lang): void
    {
        if (headers_sent()) {
            return;
        }
        $isSecure = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')
            || (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https');
        $opts = [
            'expires' => time() + (365 * 24 * 60 * 60),
            'path' => '/',
            'secure' => $isSecure,
            'httponly' => false,
            'samesite' => 'Lax',
        ];
        setcookie(self::LOCALE_COOKIE, $lang, $opts);
        setcookie(self::LOCALE_COOKIE_ALT, $lang, $opts);
        $_COOKIE[self::LOCALE_COOKIE] = $lang;
        $_COOKIE[self::LOCALE_COOKIE_ALT] = $lang;
    }

    public static function bank(): array
    {
        return self::$bank;
    }

    public static function applicationContact(): array
    {
        return self::$applicationContact;
    }

    public static function paymentSteps(): array
    {
        return self::$paymentSteps;
    }

    public static function formatFee(float $amount): array
    {
        return [
            'ta' => 'ரூ. ' . number_format($amount, 2),
            'en' => 'Rs. ' . number_format($amount, 2),
        ];
    }

    public static function membershipValidityLabel(int $years, ?string $slug = null): array
    {
        if ($slug === 'half_year' || $years === 0) {
            return self::ui('validity_6_months');
        }
        if ($years === 1) {
            return self::ui('validity_1_year');
        }
        if ($years === 10) {
            return self::ui('validity_10_years');
        }
        return [
            'ta' => $years . ' ஆண்டுகள்',
            'en' => $years . ' Years',
        ];
    }

    public static function membershipDisplayFromSlug(string $slug, ?int $durationYears = null, ?string $fallbackName = null): array
    {
        $titleKey = match ($slug) {
            'ten_year' => 'ten_year_member',
            'half_year' => 'half_year_member',
            default => 'ordinary_member',
        };
        $title = self::ui($titleKey);

        // Prefer explicit English DB name when provided for ordinary/ten_year consistency
        if ($fallbackName && $slug === 'ordinary' && stripos($fallbackName, 'Ordinary') !== false) {
            $title['en'] = $fallbackName;
        }
        if ($fallbackName && $slug === 'ten_year' && stripos($fallbackName, '10') !== false) {
            $title['en'] = $fallbackName;
        }
        if ($fallbackName && $slug === 'half_year') {
            $title['en'] = 'Half-Year Membership';
        }

        $years = $durationYears;
        if ($years === null) {
            $years = match ($slug) {
                'ten_year' => 10,
                'half_year' => 0,
                default => 1,
            };
        }
        $validity = self::membershipValidityLabel((int) $years, $slug);
        $feeLabel = self::ui('fee_label');
        $bilingual = $title['en'] . ' (' . $title['ta'] . ')';

        return [
            'slug' => $slug,
            'years' => (int) $years,
            'title_ta' => $title['ta'],
            'title_en' => $title['en'],
            'bilingual' => $bilingual,
            'validity_ta' => $validity['ta'],
            'validity_en' => $validity['en'],
            'with_validity_ta' => $title['ta'] . ' (' . $validity['ta'] . ')',
            'with_validity_en' => $title['en'] . ' (' . $validity['en'] . ')',
            'fee_label_ta' => $feeLabel['ta'],
            'fee_label_en' => $feeLabel['en'],
        ];
    }

    public static function membershipDisplayFromName(string $typeName): array
    {
        $slug = \App\Helpers\MembershipType::slugFromName($typeName);
        return self::membershipDisplayFromSlug($slug, null, $typeName);
    }
}
