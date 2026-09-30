<?php
require_once __DIR__ . '/../config/db.php';
requireLogin();

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($id > 0) {
    header("Location: preview_resume.php?id=" . $id);
} else {
    header("Location: preview_resume.php");
}
exit();
?>
