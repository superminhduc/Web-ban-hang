<?php
session_start();

// Hủy toàn bộ session
session_unset();
session_destroy();

// Chặn cache
header("Cache-Control: no-cache, must-revalidate");
header("Expires: 0");

// Chuyển về trang login
header("Location: login.html");
exit();
?>
