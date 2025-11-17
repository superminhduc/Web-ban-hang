<?php
// Bật hiển thị lỗi để debug
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();

require_once 'connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if ($username === '' || $password === '') {
        echo "<script>
                alert('Vui lòng nhập đầy đủ tài khoản và mật khẩu!');
                window.location.href = '/Web-ban-hang/login.html';
              </script>";
        exit();
    }

    $sql = "SELECT id, username, password FROM users WHERE username = ? LIMIT 1";
    $stmt = $conn->prepare($sql);

    if (!$stmt) {
        echo "<script>
                alert('Lỗi hệ thống, vui lòng thử lại sau!');
                window.location.href = '/Web-ban-hang/login.html';
              </script>";
        exit();
    }

    $stmt->bind_param('s', $username);
    $stmt->execute();
    $result = $stmt->get_result();

    // TỒN TẠI TÀI KHOẢN
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();

        if ($password === $row['password']) {
            // Đăng nhập thành công
            $_SESSION['user_id'] = $row['id'];
            $_SESSION['username'] = $row['username'];

            header("Location: /Web-ban-hang/index.html");
            exit();
        } else {
            // SAI MẬT KHẨU
            echo "<script>
                    alert('Sai mật khẩu!');
                    window.location.href = '/Web-ban-hang/login.html';
                  </script>";
            exit();
        }
    } else {
        // KHÔNG TÌM THẤY TÀI KHOẢN
        echo "<script>
                alert('Tài khoản không tồn tại!');
                window.location.href = '/Web-ban-hang/login.html';
              </script>";
        exit();
    }
}
?>
