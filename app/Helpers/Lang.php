<?php

namespace App\Helpers;

class Lang
{
    private static array $fields = [
        'full_name_tamil' => ['ta' => 'முழுப்பெயர் (தமிழில்)', 'en' => 'Full Name (Tamil)'],
        'full_name_english' => ['ta' => 'முழுப்பெயர் (ஆங்கிலத்தில்)', 'en' => 'Full Name (English)'],
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
        'membership_type' => ['ta' => 'உறுப்பினர் வகை', 'en' => 'Membership Category'],
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

    public static function field(string $key): array
    {
        return self::$fields[$key] ?? ['ta' => $key, 'en' => $key];
    }

    public static function ui(string $key): array|string
    {
        $value = self::$ui[$key] ?? ['ta' => $key, 'en' => $key];
        if (is_string($value)) {
            return $value;
        }
        return $value;
    }

    public static function placeholder(string $key): string
    {
        $p = self::$placeholders[$key] ?? ['ta' => '', 'en' => ''];
        if ($p['ta'] && $p['en']) {
            return $p['ta'] . ' / ' . $p['en'];
        }
        return $p['ta'] ?: $p['en'];
    }

    public static function bank(): array
    {
        return self::$bank;
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
}
