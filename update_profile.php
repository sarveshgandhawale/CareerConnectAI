<?php
// Forwarder to profile/profile.php
require_once __DIR__ . '/config/config.php';
header("Location: " . url('profile/profile.php'));
exit();
?>