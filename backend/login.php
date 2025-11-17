<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
include('connect.php'); // Kết nối database

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Lấy dữ liệu từ form
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    // Kiểm tra dữ liệu có trống không
    if (empty($username) || empty($password)) {
        echo "<script>alert('Vui lòng nhập đầy đủ tài khoản và mật khẩu!'); window.location.href='login.php';</script>";
        exit();
    }

    // Truy vấn kiểm tra người dùng
    $sql = "SELECT * FROM users WHERE username = ? LIMIT 1";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    // Nếu có tài khoản tồn tại
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();

        if ($password == $row['password']) {  // chưa mã hóa
            $_SESSION['user_id'] = $row['id'];
            $_SESSION['username'] = $row['username'];
            echo "<script>alert('Đăng nhập thành công!'); window.location.href='/trangweb/index.html';</script>";
            exit();
        } else {
            echo "<script>alert('Sai mật khẩu!'); window.location.href='login.php';</script>";
            exit();
        }
    } else {
        echo "<script>alert('Tài khoản không tồn tại!'); window.location.href='login.php';</script>";
        exit();
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
        }
        .login-box {
            background: white;
            padding: 25px;
            width: 320px;
            border-radius: 8px;
            box-shadow: 0px 0px 8px rgba(0,0,0,0.2);
        }
        input {
            width: 100%;
            padding: 10px;
            margin-top: 10px;
            border-radius: 5px;
        }
        button {
            width: 100%;
            padding: 10px;
            margin-top: 15px;
            background: #007bff;
            color: white;
            border-radius: 5px;
            border: none;
            cursor: pointer;
        }
        button:hover {
            background: #005dc1;
        }
    </style>
</head>
<body>

<div class="login-box">
    <h2>Đăng nhập</h2>
    <form action="" method="POST">
        <input type="text" name="username" placeholder="Tài khoản" required>
        <input type="password" name="password" placeholder="Mật khẩu" required>
        <button type="submit">Đăng nhập</button>
    </form>
</div>

</body>
</html>
