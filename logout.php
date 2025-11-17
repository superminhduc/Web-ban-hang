<?php
session_start();
session_unset();
session_destroy();

// Sau khi logout -> quay về trang login
header("Location: /Web-ban-hang/login.html");
// Nếu muốn quay về index thì thay bằng: /Web-ban-hang/index.html
exit();
?>
