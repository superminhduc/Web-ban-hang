<?php
// Bật hiển thị lỗi để debug
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
require_once 'connect.php'; // Kết nối database

// Biến chứa lỗi để hiển thị dưới form
$error = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Lấy dữ liệu từ form (phòng null)
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');

    // Kiểm tra dữ liệu có trống không
    if ($username === '' || $password === '') {
        $error = 'Vui lòng nhập đầy đủ tài khoản và mật khẩu!';
    } else {
        // Truy vấn kiểm tra người dùng
        $sql = "SELECT id, username, password FROM users WHERE username = ? LIMIT 1";
        $stmt = $conn->prepare($sql);

        if (!$stmt) {
            // Lỗi prepare SQL
            $error = 'Lỗi hệ thống, vui lòng thử lại sau!';
        } else {
            $stmt->bind_param("s", $username);
            $stmt->execute();
            $result = $stmt->get_result();

            // Nếu có tài khoản tồn tại
            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();

                // So sánh mật khẩu (chưa mã hóa, cho bài lab)
                if ($password === $row['password']) {
                    // Lưu session
                    $_SESSION['user_id'] = $row['id'];
                    $_SESSION['username'] = $row['username'];

                    // Đăng nhập thành công → về trang quản lý sản phẩm
                    header("Location: /Web-ban-hang/index.html");
                    exit();
                } else {
                    $error = 'Sai mật khẩu!';
                }
            } else {
                $error = 'Tài khoản không tồn tại!';
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Đăng nhập</title>
    <style>
        body { 
            font-family: Arial, sans-serif;
            background: #f2f2f2;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .login-box {
            background: white;
            padding: 25px 28px;
            width: 320px;
            border-radius: 8px;
            box-shadow: 0px 0px 8px rgba(0,0,0,0.2);
        }
        .login-box h2 {
            margin-top: 0;
            margin-bottom: 15px;
            text-align: center;
            color: #333;
        }
        .login-box input {
            width: 100%;
            padding: 10px;
            margin-top: 10px;
            border-radius: 5px;
            border: 1px solid #ccc;
            box-sizing: border-box;
        }
        .login-box button {
            width: 100%;
            padding: 10px;
            margin-top: 15px;
            background: #007bff;
            color: white;
            border-radius: 5px;
            border: none;
            cursor: pointer;
            font-weight: 600;
        }
        .login-box button:hover {
            background: #005dc1;
        }
        .error {
            margin-top: 10px;
            color: red;
            font-size: 14px;
            text-align: center;
        }
        .back-link {
            margin-top: 12px;
            text-align: center;
            font-size: 14px;
        }
        .back-link a {
            color: #007bff;
            text-decoration: none;
        }
        .back-link a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

<div class="login-box">
    <h2>Đăng nhập</h2>
    <!-- action trống = gửi về chính login.php -->
    <form action="" method="POST">
        <input type="text" name="username" placeholder="Tài khoản" required>
        <input type="password" name="password" placeholder="Mật khẩu" required>
        <button type="submit">Đăng nhập</button>
    </form>

    <?php if ($error !== ''): ?>
        <div class="error"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>

    <div class="back-link">
        <a href="/Web-ban-hang/index.html">⟵ Quay lại trang chính</a>
    </div>
</div>

</body>
</html>
