<?php
$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? "https" : "http";
$host = $_SERVER['HTTP_HOST'];
$projectFolder = str_replace($_SERVER['DOCUMENT_ROOT'], '', str_replace('\\', '/', __DIR__));
$base = $protocol . "://" . $host . $projectFolder;

// Session timeout (in seconds). Used by includes/session_check.php to expire inactive sessions.
$SESSION_TIMEOUT = 1800; // 30 minutes
?>
