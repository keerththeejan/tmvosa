-- Fix SMTP host: mail.vkitnet.info is required (vkitnet.info does not accept SMTP)
UPDATE settings SET setting_value = 'mail.vkitnet.info' WHERE setting_key = 'smtp_host';
