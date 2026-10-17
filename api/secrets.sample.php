<?php
// Template for api/secrets.php — copy this file to api/secrets.php and fill in
// the real values. api/secrets.php is gitignored and must be uploaded to the
// server manually (once); deployments will not overwrite or delete it.

return [
    // Admin dashboard password hash (bcrypt).
    // Generate: htpasswd -nbB -C 12 admin 'YourPassword' | cut -d: -f2
    // or:       php -r "echo password_hash('YourPassword', PASSWORD_BCRYPT), PHP_EOL;"
    'admin_password_hash' => 'REPLACE_WITH_BCRYPT_HASH',

    // Brevo SMTP (port 2525 bypasses FastComet's silent port blocking)
    'smtp_host' => 'smtp-relay.brevo.com',
    'smtp_user' => 'REPLACE_WITH_BREVO_SMTP_LOGIN',
    'smtp_password' => 'REPLACE_WITH_BREVO_SMTP_KEY',
    'smtp_port' => 2525,
    'from_email' => 'REPLACE_WITH_SENDER_EMAIL',
    'from_name' => 'Torat Habayit Institute',
    'admin_email' => 'REPLACE_WITH_ADMIN_INBOX',
];
