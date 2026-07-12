<?php
-- Add Half-Year Membership (idempotent). duration_years=0 means use 180-day helper logic.
INSERT INTO membership_types (name, slug, fee, duration_years, description, is_active)
SELECT 'Half-Year Membership', 'half_year', 500.00, 0,
       'Half-Year Membership (அரை ஆண்டு உறுப்பினர்) — Rs. 500.00 | 6 Months (180 Days)', 1
WHERE NOT EXISTS (SELECT 1 FROM membership_types WHERE slug = 'half_year');

UPDATE membership_types
SET name = 'Half-Year Membership',
    fee = 500.00,
    duration_years = 0,
    description = 'Half-Year Membership (அரை ஆண்டு உறுப்பினர்) — Rs. 500.00 | 6 Months (180 Days)',
    is_active = 1
WHERE slug = 'half_year';

UPDATE membership_types
SET description = 'Ordinary Member (சாதாரண உறுப்பினர்) — Rs. 1,000.00 | 1 Year'
WHERE slug = 'ordinary';

UPDATE membership_types
SET description = '10-Year Membership (10 ஆண்டு உறுப்பினர்) — Rs. 10,000.00 | 10 Years'
WHERE slug = 'ten_year';
