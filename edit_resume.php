<?php
require_once __DIR__ . '/config/config.php';
$id = isset($_GET['id']) ? '?id=' . (int)$_GET['id'] : '';
header("Location: " . url('resume/edit_resume.php' . $id));
exit();
?>