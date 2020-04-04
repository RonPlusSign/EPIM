<?php

// Comment this line in production
$DEBUG_MODE = true;

if (isset($DEBUG_MODE)) {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
}
else {
    error_reporting(0);
    ini_set('display_errors', 0);
}

// Session security configurations
ini_set('session.use_only_cookies', TRUE);
ini_set('session.use_trans_sid', FALSE);
ini_set('session.cookie_lifetime', 0); // In seconds. 0 = Session timeout (this because we manage session timeout ourselves)

// POST settings
ini_set('post_max_size', '10M');
ini_set('upload_max_filesize', '10M');
ini_set('max_file_uploads', 1);

require_once __DIR__ . '/Database.php';
Database::init(); // initialize connection
