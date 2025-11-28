<?php
$filepath = realpath(dirname(__FILE__));
include_once($filepath . "/../lib/session.php");
Session::init();
if (Session::get('admin')) {
    header("Location: index.php");
    exit();
}
include("../classes/admin.php");
$class = new Admin();
// Xử lý lấy thông tin từ cookie nếu có
$email_cookie = isset($_COOKIE['admin_email']) ? $_COOKIE['admin_email'] : '';
$password_cookie = isset($_COOKIE['admin_password']) ? $_COOKIE['admin_password'] : '';
$remember_cookie = isset($_COOKIE['admin_remember']) ? true : false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password_raw = $_POST['password'];
    $password = md5($password_raw);
    $remember = isset($_POST['remember']);
    $loginCheck = $class->loginAdmin($email, $password);
    if (isset($loginCheck) && strpos($loginCheck, 'thành công') !== false) {
        if ($remember) {
            setcookie('admin_email', $email, time() + (86400 * 30), "/");
            setcookie('admin_password', $password_raw, time() + (86400 * 30), "/");
            setcookie('admin_remember', '1', time() + (86400 * 30), "/");
        } else {
            setcookie('admin_email', '', time() - 3600, "/");
            setcookie('admin_password', '', time() - 3600, "/");
            setcookie('admin_remember', '', time() - 3600, "/");
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Đăng Nhập Admin</title>
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;500;600&display=swap" rel="stylesheet">
    <style media="screen">
        *,
        *:before,
        *:after {
            padding: 0;
            margin: 0;
            box-sizing: border-box;
        }

        body {
            background-color: #080710;
        }

        .background {
            width: 430px;
            height: 520px;
            position: absolute;
            transform: translate(-50%, -50%);
            left: 50%;
            top: 50%;
        }

        .background .shape {
            height: 200px;
            width: 200px;
            position: absolute;
            border-radius: 50%;
        }

        .shape:first-child {
            background: linear-gradient(#1845ad,
                    #23a2f6);
            left: -80px;
            top: -80px;
        }

        .shape:last-child {
            background: linear-gradient(to right,
                    #ff512f,
                    #f09819);
            right: -30px;
            bottom: -80px;
        }

        form {
            height: 520px;
            width: 400px;
            background-color: rgba(255, 255, 255, 0.13);
            position: absolute;
            transform: translate(-50%, -50%);
            top: 50%;
            left: 50%;
            border-radius: 10px;
            backdrop-filter: blur(10px);
            border: 2px solid rgba(255, 255, 255, 0.1);
            box-shadow: 0 0 40px rgba(8, 7, 16, 0.6);
            padding: 50px 35px;
        }

        form * {
            font-family: 'Poppins', sans-serif;
            color: #ffffff;
            letter-spacing: 0.5px;
            outline: none;
            border: none;
        }

        form h3 {
            font-size: 32px;
            font-weight: 500;
            line-height: 42px;
            text-align: center;
        }

        label {
            display: block;
            margin-top: 30px;
            font-size: 16px;
            font-weight: 500;
        }

        input {
            display: block;
            height: 50px;
            width: 100%;
            background-color: rgba(255, 255, 255, 0.07);
            border-radius: 3px;
            padding: 0 10px;
            margin-top: 8px;
            font-size: 14px;
            font-weight: 300;
        }

        ::placeholder {
            color: #e5e5e5;
        }

        button {
            margin-top: 50px;
            width: 100%;
            background-color: #ffffff;
            color: #080710;
            padding: 15px 0;
            font-size: 18px;
            font-weight: 600;
            border-radius: 5px;
            cursor: pointer;
        }

        .social {
            margin-top: 30px;
            display: flex;
        }

        .social div {
            background: red;
            width: 150px;
            border-radius: 3px;
            padding: 5px 10px 10px 5px;
            background-color: rgba(255, 255, 255, 0.27);
            color: #eaf0fb;
            text-align: center;
        }

        .social div:hover {
            background-color: rgba(255, 255, 255, 0.47);
        }

        .social .fb {
            margin-left: 25px;
        }

        .social i {
            margin-right: 4px;
        }
        /* Ghi nhớ đăng nhập */
        .remember-row {
            display: flex;
            flex-direction: row;
            align-items: center;
            justify-content: flex-end;
            margin: 10px 0 0 0;
            gap: 6px;
            height: 32px;
        }
        .remember-row label {
            font-size: 13px;
            color: #e5e5e5;
            cursor: pointer;
            margin-bottom: 0;
            user-select: none;
            display: inline-block;
            vertical-align: middle;
        }
        .remember-row input[type="checkbox"] {
            width: 16px;
            height: 16px;
            accent-color: #1845ad;
            cursor: pointer;
            margin: 0;
            display: inline-block;
            vertical-align: middle;
            margin-top: 30px;
        }
    </style>
</head>
<body>
    <div class="background">
        <div class="shape"></div>
        <div class="shape"></div>
    </div>
    <form action="" method="post">
        <h3>Đăng Nhập</h3>
        <label for="username">Tên tài khoản</label>
        <input type="text" placeholder="Email hoặc tên tài khoản" id="username" name="email" value="<?php echo htmlspecialchars(isset($_POST['email']) ? $_POST['email'] : $email_cookie); ?>">
        <label for="password">Mật khẩu</label>
        <input type="password" placeholder="Mật khẩu" id="password" name="password" value="<?php echo htmlspecialchars(isset($_POST['password']) ? $_POST['password'] : $password_cookie); ?>">
        <div class="remember-row">
            <input type="checkbox" id="remember" name="remember" <?php echo (isset($_POST['remember']) || $remember_cookie) ? 'checked' : ''; ?>>
            <label for="remember">Ghi nhớ</label>
        </div>
        <button type="submit" name="login">Đăng Nhập</button>
        <div>
            <span style="color:red;">
                <?php if (isset($loginCheck)) echo $loginCheck; ?>
            </span>
        </div>
    </form>
</body>
</html>
