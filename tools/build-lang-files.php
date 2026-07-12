<?php
/**
 * One-shot generator: builds languages/ta.php and languages/en.php
 * from Lang.php internal arrays + homepage marketing strings.
 */
require dirname(__DIR__) . '/bootstrap.php';
App\Core\App::init();

$ref = new ReflectionClass(App\Helpers\Lang::class);
$get = static function (string $prop) use ($ref) {
    $p = $ref->getProperty($prop);
    $p->setAccessible(true);
    return $p->getValue();
};

$ta = [];
$en = [];

foreach ($get('ui') as $k => $v) {
    if (is_string($v)) {
        if (str_ends_with($k, '_ta')) {
            $ta[substr($k, 0, -3)] = $v;
        } elseif (str_ends_with($k, '_en')) {
            $en[substr($k, 0, -3)] = $v;
        } else {
            $ta[$k] = $v;
            $en[$k] = $v;
        }
        continue;
    }
    $ta[$k] = $v['ta'] ?? $k;
    $en[$k] = $v['en'] ?? $k;
}

foreach ($get('fields') as $k => $v) {
    $ta['field_' . $k] = $v['ta'] ?? $k;
    $en['field_' . $k] = $v['en'] ?? $k;
}

foreach ($get('placeholders') as $k => $v) {
    $ta['ph_' . $k] = $v['ta'] ?? $k;
    $en['ph_' . $k] = $v['en'] ?? $k;
}

$homeExtra = [
    'apply' => ['ta' => 'விண்ணப்பிக்க', 'en' => 'Apply'],
    'login' => ['ta' => 'உள்நுழை', 'en' => 'Login'],
    'skip_to_content' => ['ta' => 'உள்ளடக்கத்திற்குச் செல்', 'en' => 'Skip to content'],
    'scroll' => ['ta' => 'உருட்டு', 'en' => 'Scroll'],
    'featured' => ['ta' => 'சிறப்பு', 'en' => 'Featured'],
    'currency_rs' => ['ta' => 'ரூ.', 'en' => 'Rs.'],
    'digital_membership' => ['ta' => 'டிஜிட்டல் உறுப்பினர்', 'en' => 'Digital Membership'],
    'member' => ['ta' => 'உறுப்பினர்', 'en' => 'Member'],
    'alumni_member' => ['ta' => 'பழைய மாணவர் உறுப்பினர்', 'en' => 'Alumni Member'],
    'batch_ordinary' => ['ta' => 'பைச்சு / சாதாரணம்', 'en' => 'Batch / Ordinary'],
    'member_id' => ['ta' => 'உறுப்பினர் இலக்கம்', 'en' => 'Member ID'],
    'qr_verified' => ['ta' => 'QR சரிபார்க்கப்பட்டது', 'en' => 'QR Verified'],
    'about_eyebrow' => ['ta' => 'எமது பழைய மாணவர் சங்கம்', 'en' => 'Our Alumni Association'],
    'about_title' => [
        'ta' => 'பாரம்பரியத்தைப் பாதுகாத்து, எதிர்காலத்தை இணைக்கும் உறவுப் பாலம்',
        'en' => 'Preserving tradition and connecting the future',
    ],
    'about_lead' => [
        'ta' => 'திருவையாறு மகா வித்தியாலயத்தின் பழைய மாணவர்களை ஒருங்கிணைத்து, பாடசாலையின் வளர்ச்சிக்கும் மாணவர்களின் முன்னேற்றத்திற்கும் சமூக நலனுக்கும் அர்ப்பணிப்புடன் செயல்படும் அமைப்பாக எமது பழைய மாணவர் சங்கம் திகழ்கிறது.',
        'en' => 'Our Old Students\' Association unites alumni of Thiruvaiyaru Maha Vidyalayam and works with dedication for school development, student progress, and community welfare.',
    ],
    'mission' => ['ta' => 'பணிக்கூற்று', 'en' => 'Mission'],
    'mission_text' => [
        'ta' => 'நேர்மை, ஒற்றுமை மற்றும் சமூகப் பொறுப்புணர்வுடன் பழைய மாணவர்களை ஒன்றிணைத்து, பாடசாலை மற்றும் மாணவர்களின் முன்னேற்றத்திற்கு தொடர்ந்து பங்களிப்புச் செய்தல்.',
        'en' => 'Unite alumni with integrity, unity, and social responsibility, and continually contribute to the advancement of the school and its students.',
    ],
    'vision' => ['ta' => 'தூரநோக்கு', 'en' => 'Vision'],
    'vision_text' => [
        'ta' => 'திருவையாறு மகா வித்தியாலயத்தின் பாரம்பரியத்தை உலகம் முழுவதும் வாழும் பழைய மாணவர்களுடன் இணைத்து, வலுவான மற்றும் ஒற்றுமையான பழைய மாணவர் சமூகத்தை உருவாக்குதல்.',
        'en' => 'Connect the heritage of Thiruvaiyaru Maha Vidyalayam with alumni worldwide and build a strong, united alumni community.',
    ],
    'objectives' => ['ta' => 'நோக்கங்கள்', 'en' => 'Objectives'],
    'obj_1' => ['ta' => 'புதிய உறுப்பினர்களை இணைத்தல்', 'en' => 'Enroll new members'],
    'obj_2' => ['ta' => 'கல்வி உதவித்தொகை திட்டங்களை முன்னெடுத்தல்', 'en' => 'Advance scholarship programmes'],
    'obj_3' => ['ta' => 'பழைய மாணவர் சந்திப்புகள் மற்றும் விழாக்களை நடத்துதல்', 'en' => 'Host alumni gatherings and celebrations'],
    'obj_4' => ['ta' => 'வெளிப்படையான டிஜிட்டல் உறுப்பினர் சேவைகளை வழங்குதல்', 'en' => 'Provide transparent digital membership services'],
    'membership_eyebrow' => ['ta' => 'உறுப்பினர் திட்டங்கள்', 'en' => 'Membership Plans'],
    'membership_title' => ['ta' => 'உங்கள் உறுப்பினர் வகையைத் தேர்வு செய்யுங்கள்', 'en' => 'Choose your membership type'],
    'membership_lead' => [
        'ta' => 'வெளிப்படையான கட்டணங்களுடன் அதிகாரப்பூர்வ சங்கத் திட்டங்களும் டிஜிட்டல் உறுப்பினர் அட்டைகளும்.',
        'en' => 'Official association plans with transparent fees and digital membership cards.',
    ],
    'why_join_eyebrow' => ['ta' => 'ஏன் சேர வேண்டும்', 'en' => 'Why Join'],
    'why_join_title' => [
        'ta' => 'உறுப்பினர் சேர்க்கையைத் தாண்டிய பெறுமதிகள்',
        'en' => 'Value beyond membership enrollment',
    ],
    'journey_eyebrow' => ['ta' => 'உறுப்பினர் நன்மைகள்', 'en' => 'Member Benefits'],
    'journey_title' => ['ta' => 'எமது சங்கத்துடன் உங்கள் பயணம்', 'en' => 'Your journey with our association'],
    'news_eyebrow' => ['ta' => 'சமீபத்திய செய்திகள்', 'en' => 'Latest News'],
    'news_title' => [
        'ta' => 'பழைய மாணவர் சங்கத்தின் அறிவிப்புகள், நிகழ்வுகள் மற்றும் முக்கிய தகவல்கள்',
        'en' => 'Association announcements, events, and important updates',
    ],
    'read_more' => ['ta' => 'மேலும் வாசிக்க', 'en' => 'Read more'],
    'events_eyebrow' => ['ta' => 'வரவிருக்கும் நிகழ்வுகள்', 'en' => 'Upcoming Events'],
    'gallery_eyebrow' => ['ta' => 'புகைப்படக் காட்சி', 'en' => 'Photo Gallery'],
    'gallery_title' => ['ta' => 'நினைவுகள் மற்றும் தருணங்கள்', 'en' => 'Memories and moments'],
    'video_eyebrow' => ['ta' => 'காணொளி', 'en' => 'Video'],
    'video_title' => ['ta' => 'OSA பழைய மாணவர் பயணம்', 'en' => 'The OSA alumni journey'],
    'video_coming_soon' => ['ta' => 'காணொளி விரைவில்', 'en' => 'Video coming soon'],
    'stories_eyebrow' => ['ta' => 'கதைகள்', 'en' => 'Stories'],
    'stories_title' => ['ta' => 'பழைய மாணவர் குரல்கள்', 'en' => 'Alumni voices'],
    'partners_eyebrow' => ['ta' => 'பங்காளிகள்', 'en' => 'Partners'],
    'verify_eyebrow' => ['ta' => 'சரிபார்ப்பு', 'en' => 'Verification'],
    'verify_title' => ['ta' => 'உறுப்பினர் அந்தஸ்தைச் சரிபார்க்கவும்', 'en' => 'Verify membership status'],
    'verify_lead' => [
        'ta' => 'உறுப்பினர் இலக்கம் அல்லது NIC மூலம் அதிகாரப்பூர்வ உறுப்பினரை உடனடியாகச் சரிபார்க்கவும்.',
        'en' => 'Instantly verify an official member using membership number or NIC.',
    ],
    'verify_placeholder' => [
        'ta' => 'உறுப்பினர் இலக்கம் அல்லது NIC',
        'en' => 'Membership number or NIC',
    ],
    'cta_title' => [
        'ta' => 'இன்றே உங்கள் OSA உறுப்பினரைத் தொடங்குங்கள்',
        'en' => 'Start your OSA membership today',
    ],
    'cta_lead' => [
        'ta' => 'அதிகாரப்பூர்வ OSA உறுப்பினராகுங்கள், உங்கள் டிஜிட்டல் அட்டையைப் பெறுங்கள், திருவையாறு பழைய மாணவர் தலைமுறைகளுடன் தொடர்பில் இருங்கள்.',
        'en' => 'Become an official OSA member, receive your digital card, and stay connected with generations of Thiruvaiyaru alumni.',
    ],
    'contact_eyebrow' => ['ta' => 'தொடர்பு', 'en' => 'Contact'],
    'contact_title' => ['ta' => 'எம்முடன் தொடர்புகொள்ளுங்கள்', 'en' => 'Get in touch'],
    'contact_name' => ['ta' => 'பெயர்', 'en' => 'Name'],
    'contact_message' => ['ta' => 'செய்தி', 'en' => 'Message'],
    'contact_send' => ['ta' => 'அனுப்பு', 'en' => 'Send'],
    'newsletter_title' => ['ta' => 'செய்திமடல்', 'en' => 'Newsletter'],
    'newsletter_lead' => [
        'ta' => 'நிகழ்வுகள் மற்றும் அறிவிப்புகளைப் பெற பதிவு செய்யுங்கள்.',
        'en' => 'Subscribe for events and announcements.',
    ],
    'subscribe' => ['ta' => 'பதிவு செய்', 'en' => 'Subscribe'],
    'footer_quick_links' => ['ta' => 'விரைவு இணைப்புகள்', 'en' => 'Quick Links'],
    'footer_contact' => ['ta' => 'தொடர்பு', 'en' => 'Contact'],
    'footer_copyright' => ['ta' => 'பதிப்புரிமை', 'en' => 'Copyright'],
    'footer_website' => ['ta' => 'வலைத்தளம்', 'en' => 'Website'],
    'footer_assoc_name' => [
        'ta' => 'கிளிநொச்சி / திருவையாறு மகா வித்தியாலயம் பழைய மாணவர் சங்கம்',
        'en' => 'Kilinochchi / Thiruvaiyaru Maha Vidyalayam Old Students\' Association',
    ],
    'footer_rights' => ['ta' => 'அனைத்து உரிமைகளும் பாதுகாக்கப்பட்டவை.', 'en' => 'All Rights Reserved.'],
    'footer_design' => ['ta' => 'வடிவமைப்பு மற்றும் உருவாக்கம்', 'en' => 'Design & Development'],
    'required' => ['ta' => 'தேவை', 'en' => 'Required'],
    'why_1_title' => ['ta' => 'பழைய மாணவர் இணைப்பு', 'en' => 'Alumni Network'],
    'why_1_text' => [
        'ta' => 'இலங்கை மற்றும் உலகின் பல்வேறு நாடுகளில் வாழும் பழைய மாணவர்களுடன் உறவுகளை வலுப்படுத்துங்கள்.',
        'en' => 'Strengthen relationships with alumni living across Sri Lanka and around the world.',
    ],
    'why_2_title' => ['ta' => 'தொழில் வாய்ப்புகள்', 'en' => 'Career Opportunities'],
    'why_2_text' => [
        'ta' => 'அனுபவமிக்க பழைய மாணவர்களின் வழிகாட்டலும் தொழில்முறை தொடர்புகளும் உங்கள் வளர்ச்சிக்கு துணைநிற்கும்.',
        'en' => 'Mentorship and professional connections from experienced alumni support your growth.',
    ],
    'why_3_title' => ['ta' => 'கல்வி உதவித்தொகைகள்', 'en' => 'Scholarships'],
    'why_3_text' => [
        'ta' => 'திறமையான மற்றும் பொருளாதார வசதி குறைந்த மாணவர்களுக்கு கல்வி உதவிகளை வழங்கும் திட்டங்கள்.',
        'en' => 'Programmes that support talented and financially needy students.',
    ],
    'why_4_title' => ['ta' => 'நிகழ்வுகள்', 'en' => 'Events'],
    'why_4_text' => [
        'ta' => 'ஒன்றுகூடல்கள், கலாசார நிகழ்ச்சிகள், நினைவு விழாக்கள் மற்றும் சிறப்பு சந்திப்புகள்.',
        'en' => 'Reunions, cultural programmes, memorials, and special gatherings.',
    ],
    'why_5_title' => ['ta' => 'பயிற்சிகள்', 'en' => 'Training'],
    'why_5_text' => [
        'ta' => 'திறன் மேம்பாட்டு பயிற்சிகள், கருத்தரங்குகள் மற்றும் அறிவுப் பகிர்வு நிகழ்ச்சிகள்.',
        'en' => 'Skills workshops, seminars, and knowledge-sharing sessions.',
    ],
    'why_6_title' => ['ta' => 'வணிக இணைப்புகள்', 'en' => 'Business Links'],
    'why_6_text' => [
        'ta' => 'பழைய மாணவர்களிடையே தொழில்முறை மற்றும் வணிக உறவுகளை உருவாக்குங்கள்.',
        'en' => 'Build professional and business relationships among alumni.',
    ],
    'why_7_title' => ['ta' => 'டிஜிட்டல் உறுப்பினர் அட்டை', 'en' => 'Digital Membership Card'],
    'why_7_text' => [
        'ta' => 'QR குறியீடு மூலம் சரிபார்க்கக்கூடிய பாதுகாப்பான டிஜிட்டல் உறுப்பினர் அட்டை.',
        'en' => 'A secure digital membership card verifiable via QR code.',
    ],
    'why_8_title' => ['ta' => 'அங்கீகாரம்', 'en' => 'Recognition'],
    'why_8_text' => [
        'ta' => 'அதிகாரப்பூர்வ உறுப்பினர் அங்கீகாரம், சான்றிதழ்கள் மற்றும் சங்கத்தின் சிறப்பு அடையாளம்.',
        'en' => 'Official membership recognition, certificates, and association identity.',
    ],
    'step_1_title' => ['ta' => 'ஆன்லைனில் விண்ணப்பிக்க', 'en' => 'Apply online'],
    'step_1_text' => [
        'ta' => 'உங்கள் உறுப்பினர் விண்ணப்பத்தையும் தேவையான தகவல்களையும் பாதுகாப்பாக சமர்ப்பிக்கவும்.',
        'en' => 'Securely submit your membership application and required details.',
    ],
    'step_2_title' => ['ta' => 'உறுப்பினர் சரிபார்ப்பு', 'en' => 'Membership review'],
    'step_2_text' => [
        'ta' => 'சங்க நிர்வாகக் குழு உங்கள் விண்ணப்பத்தை ஆய்வு செய்து அங்கீகரிக்கும்.',
        'en' => 'The association committee reviews and approves your application.',
    ],
    'step_3_title' => ['ta' => 'டிஜிட்டல் உறுப்பினர் அட்டையைப் பெறுங்கள்', 'en' => 'Receive your digital card'],
    'step_3_text' => [
        'ta' => 'QR குறியீடு கொண்ட உங்கள் உறுப்பினர் அட்டையை உடனடியாகப் பதிவிறக்கம் செய்யுங்கள்.',
        'en' => 'Download your membership card with QR code immediately.',
    ],
    'step_4_title' => ['ta' => 'சங்க நடவடிக்கைகளில் பங்கேற்குங்கள்', 'en' => 'Join association activities'],
    'step_4_text' => [
        'ta' => 'நிகழ்வுகளில் கலந்துகொள்ளுங்கள், மாணவர்களுக்கு வழிகாட்டுங்கள், பாடசாலை வளர்ச்சிக்கு பங்களியுங்கள்.',
        'en' => 'Attend events, mentor students, and contribute to school development.',
    ],
    'perk_half_1' => ['ta' => 'டிஜிட்டல் QR உறுப்பினர் அட்டை', 'en' => 'Digital QR membership card'],
    'perk_half_2' => ['ta' => 'பழைய மாணவர் வலைப்பின்னல்', 'en' => 'Alumni network'],
    'perk_half_3' => ['ta' => 'நிகழ்வு அழைப்பிதழ்கள்', 'en' => 'Event invitations'],
    'perk_half_4' => ['ta' => '6 மாத செல்லுபடி', 'en' => '6-month validity'],
    'perk_ord_1' => ['ta' => 'டிஜிட்டல் QR உறுப்பினர் அட்டை', 'en' => 'Digital QR membership card'],
    'perk_ord_2' => ['ta' => 'முழு உறுப்பினர் சலுகைகள்', 'en' => 'Full member benefits'],
    'perk_ord_3' => ['ta' => 'ஆண்டு நிகழ்வுகள்', 'en' => 'Annual events'],
    'perk_ord_4' => ['ta' => '1 ஆண்டு செல்லுபடி', 'en' => '1-year validity'],
    'perk_ten_1' => ['ta' => 'நீண்டகால உறுப்பினர்', 'en' => 'Long-term membership'],
    'perk_ten_2' => ['ta' => 'முன்னுரிமை நிகழ்வு அணுகல்', 'en' => 'Priority event access'],
    'perk_ten_3' => ['ta' => 'அங்கீகார நன்மைகள்', 'en' => 'Recognition benefits'],
    'perk_ten_4' => ['ta' => '10 ஆண்டு செல்லுபடி', 'en' => '10-year validity'],
    'news_1_title' => ['ta' => 'ஆண்டுப் பொதுக் கூட்டம் 2026', 'en' => 'Annual General Meeting 2026'],
    'news_1_date' => ['ta' => '15 மார்ச் 2026', 'en' => '15 Mar 2026'],
    'news_1_cat' => ['ta' => 'சங்கம்', 'en' => 'Association'],
    'news_1_summary' => [
        'ta' => 'முன்னேற்றத்தை மதிப்பாய்வு செய்யவும் அலுவலர்களைத் தேர்ந்தெடுக்கவும் உறுப்பினர்கள் அழைக்கப்படுகின்றனர்.',
        'en' => 'Members are invited to review progress and elect officers.',
    ],
    'news_2_title' => ['ta' => 'கல்வி உதவித்தொகை விழா', 'en' => 'Scholarship Ceremony'],
    'news_2_date' => ['ta' => '02 பெப் 2026', 'en' => '02 Feb 2026'],
    'news_2_cat' => ['ta' => 'கல்வி', 'en' => 'Education'],
    'news_2_summary' => [
        'ta' => 'சிறந்த மாணவர்கள் தகுதி உதவித்தொகைகளுடன் அங்கீகரிக்கப்பட்டனர்.',
        'en' => 'Outstanding students were recognized with merit scholarships.',
    ],
    'news_3_title' => ['ta' => 'டிஜிட்டல் உறுப்பினர் அறிமுகம்', 'en' => 'Digital Membership Launch'],
    'news_3_date' => ['ta' => '20 சன 2026', 'en' => '20 Jan 2026'],
    'news_3_cat' => ['ta' => 'தொழில்நுட்பம்', 'en' => 'Technology'],
    'news_3_summary' => [
        'ta' => 'விண்ணப்பிக்க, செலுத்த மற்றும் QR உறுப்பினர் அட்டைகளுடன் சரிபார்க்கவும்.',
        'en' => 'Apply, pay, and verify with QR membership cards.',
    ],
    'event_1_title' => ['ta' => 'பழைய மாணவர் ஒன்றுகூடல் இரவு', 'en' => 'Alumni Reunion Night'],
    'event_1_place' => ['ta' => 'பாடசாலை மண்டபம்', 'en' => 'School Hall'],
    'event_1_summary' => [
        'ta' => 'நட்பு, இசை மற்றும் பகிர்ந்த நினைவுகளின் மாலை.',
        'en' => 'An evening of friendship, music, and shared memories.',
    ],
    'event_2_title' => ['ta' => 'தொழில் வழிகாட்டல் நாள்', 'en' => 'Career Guidance Day'],
    'event_2_place' => ['ta' => 'ஆன்லைன் + வளாகம்', 'en' => 'Online + Campus'],
    'event_2_summary' => [
        'ta' => 'வழிகாட்டிகள் தற்போதைய மாணவர்களுக்கும் இளைஞர் பழைய மாணவர்களுக்கும் உதவுகின்றனர்.',
        'en' => 'Mentors support current students and young alumni.',
    ],
    'event_3_title' => ['ta' => 'நிறுவனர் தின நினைவு', 'en' => 'Founders\' Day Remembrance'],
    'event_3_place' => ['ta' => 'முதன்மை மண்டபம்', 'en' => 'Main Hall'],
    'event_3_summary' => [
        'ta' => 'திருவையாறு மகா வித்தியாலயத்தின் பாரம்பரியத்தை கௌரவித்தல்.',
        'en' => 'Honouring the heritage of Thiruvaiyaru Maha Vidyalayam.',
    ],
    'testimonial_1_name' => ['ta' => 'கோபிநாத் தி.', 'en' => 'Gopinath T.'],
    'testimonial_1_batch' => ['ta' => 'பைச்சு 2012', 'en' => 'Batch 2012'],
    'testimonial_1_role' => ['ta' => 'சாதாரண உறுப்பினர்', 'en' => 'Ordinary Member'],
    'testimonial_1_quote' => [
        'ta' => 'டிஜிட்டல் உறுப்பினர் அட்டையும் ஆன்லைன் விண்ணப்பமும் இணைவதை எளிமையாகவும் தொழில்முறையாகவும் ஆக்கியது.',
        'en' => 'The digital membership card and online application made joining simple and professional.',
    ],
    'testimonial_2_name' => ['ta' => 'கீர்த்தீஜன் வி.', 'en' => 'Keerthijan V.'],
    'testimonial_2_batch' => ['ta' => 'பைச்சு 2015', 'en' => 'Batch 2015'],
    'testimonial_2_role' => ['ta' => 'பழைய மாணவர்', 'en' => 'Alumni'],
    'testimonial_2_quote' => [
        'ta' => 'உலகில் எங்கே இருந்தாலும் OSA எமது பாடசாலைச் சமூகத்தை இணைத்து வைக்கிறது.',
        'en' => 'Wherever we are in the world, OSA keeps our school community connected.',
    ],
    'testimonial_3_name' => ['ta' => 'OSA குழு', 'en' => 'OSA Team'],
    'testimonial_3_batch' => ['ta' => 'தலைமைத்துவம்', 'en' => 'Leadership'],
    'testimonial_3_role' => ['ta' => 'சங்க அலுவலகம்', 'en' => 'Association Office'],
    'testimonial_3_quote' => [
        'ta' => 'நவீன உறுப்பினர் அமைப்பு தெளிவுடனும் நம்பிக்கையுடனும் பழைய மாணவர்களுக்கு சேவை செய்ய உதவுகிறது.',
        'en' => 'A modern membership system helps us serve alumni with clarity and trust.',
    ],
];

foreach ($homeExtra as $k => $v) {
    $ta[$k] = $v['ta'];
    $en[$k] = $v['en'];
}

ksort($ta);
ksort($en);

$export = static function (array $arr): string {
    $out = "<?php\n\nreturn [\n";
    foreach ($arr as $k => $v) {
        $out .= '    ' . var_export((string) $k, true) . ' => ' . var_export((string) $v, true) . ",\n";
    }
    $out .= "];\n";
    return $out;
};

$dir = dirname(__DIR__) . '/languages';
if (!is_dir($dir)) {
    mkdir($dir, 0777, true);
}
file_put_contents($dir . '/ta.php', $export($ta));
file_put_contents($dir . '/en.php', $export($en));
echo 'Wrote ' . count($ta) . " keys to languages/ta.php and languages/en.php\n";
