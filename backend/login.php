<?php
// Bật hiển thị lỗi để dễ debug trong quá trình phát triển
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start(); // Khởi động session để lưu đăng nhập người dùng

require_once 'connect.php'; // Kết nối đến database

// Biến để lưu lỗi và hiển thị ra giao diện
$error = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Lấy dữ liệu từ form, tránh null bằng toán tử ?? ''
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');

    // Kiểm tra nếu người dùng bỏ trống tài khoản hoặc mật khẩu
    if ($username === '' || $password === '') {
        $error = 'Vui lòng nhập đầy đủ tài khoản và mật khẩu!';
    } else {

        // Chuẩn bị câu lệnh kiểm tra tài khoản (dùng Prepared Statement để tránh SQL Injection)
        $sql = "SELECT id, username, password FROM users WHERE username = ? LIMIT 1";
        $stmt = $conn->prepare($sql);

        // Nếu câu prepare thất bại
        if (!$stmt) {
            $error = 'Lỗi hệ thống, vui lòng thử lại sau!';
        } else {

            // Gán giá trị tham số và thực thi lệnh SQL
            $stmt->bind_param("s", $username);
            $stmt->execute();
            $result = $stmt->get_result();

            // Nếu tìm thấy tài khoản
            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();

                // So sánh mật khẩu (phiên bản đơn giản cho bài lab, chưa mã hoá)
                if ($password === $row['password']) {

                    // Lưu thông tin vào session
                    $_SESSION['user_id'] = $row['id'];
                    $_SESSION['username'] = $row['username'];

                    // Đăng nhập thành công → chuyển sang trang chính
                    header("Location: /Web-ban-hang/index.html");
                    exit();
                } else {
                    // Mật khẩu sai
                    $error = 'Sai mật khẩu!';
                }
            } else {
                // Không tìm thấy tài khoản
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

    <!-- CSS giao diện login -->
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

    <!-- action="" nghĩa là submit trở lại chính login.php -->
    <form action="" method="POST">
        <input type="text" name="username" placeholder="Tài khoản" required>
        <input type="password" name="password" placeholder="Mật khẩu" required>
        <button type="submit">Đăng nhập</button>
    </form>

    <!-- Nếu có lỗi thì hiển thị -->
    <?php if ($error !== ''): ?>
        <div class="error"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>

    <!-- Link quay lại trang chủ -->
    <div class="back-link">
        <a href="/Web-ban-hang/index.html">⟵ Quay lại trang chính</a>
    </div>
</div>

</body>
</html>
