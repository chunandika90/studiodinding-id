<?php
/**
 * Copy this file to shared/config.local.php and fill in real values.
 * shared/ is NOT web-accessible — it lives outside both the website/
 * and cms/ document roots on the server (sibling folder in cPanel's
 * home dir, alongside public_html-equivalents for each subdomain).
 *
 * SITE_DIR_BASE / CMS_DIR_BASE are the actual folder NAMES on the server
 * for each subdomain's document root — cPanel names these after
 * whatever you called the subdomain, so this almost never matches your
 * local folder name. Get the exact name from cPanel > Domains (or File
 * Manager) after creating the subdomain, THEN fill this in — don't
 * guess. If you ever rename/move the folder, this is the one place to
 * update; every other file derives paths from it, never hardcodes it.
 */

return [
    'db_host' => 'localhost',
    'db_name' => 'vodbpxic_cms',
    'db_user' => 'yourcpaneluser_dbuser', // fill in the DB user you create in cPanel > MySQL Databases
    'db_pass' => 'your-db-password',

    // Public site (new.studiodinding.id)
    'site_url' => 'https://new.studiodinding.id',
    'site_dir_base' => 'new.studiodinding.id', // exact folder name in cPanel for this subdomain's document root

    // Admin panel (cms.studiodinding.id)
    'cms_url' => 'https://cms.studiodinding.id',
    'cms_dir_base' => 'cms.studiodinding.id',

    // Set to false once the site is live for real visitors — raw PHP
    // errors can leak code/DB structure to anyone watching.
    'debug_mode' => true,
];
