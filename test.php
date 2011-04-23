<?php

// To connect to an IMAP server running on port 143 on the local machine,
// do the following:
ini_set('display_errors', 1);
error_reporting(E_ALL);

echo phpinfo();
$mbox = imap_open("{localhost:143}INBOX", "user_id", "password");
?>
