<?php
session_start();

// 检查是否已经登录
if(isset($_SESSION['username'])){
    header("Location: index.php");
    exit();
}

// 检查是否提交了表单
if(isset($_POST['submit'])){
    // 获取表单数据
    $username = $_POST['username'];
    $password = $_POST['password'];
    
    // 验证数据
    if(empty($username) || empty($password)){
        $error = "请填写全部字段";
    }else{
        // 检查用户名和密码是否匹配
        $users = file("users.txt");
        foreach($users as $user){
            list($name, $pass) = explode(",", $user);
            if(trim($name) == $username && trim($pass) == $password){
                // 设置会话变量
                $_SESSION['username'] = $username;
                // 跳转到首页或其他页面
                header("Location: index.php");
                exit();
            }
        }
        $error = "用户名或密码错误";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>登录 | 深圳市潜龙学校论坛</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="login-container">
        <h2>登录学校论坛</h2>
        <?php
        // 显示错误信息或成功信息
        if(isset($error)){
            echo "<p class='error'>$error</p>";
        }
        if(isset($_GET['success'])){
            echo "<p class='success'>".$_GET['success']."</p>";
        }
        ?>
        <form action="login.php" method="post" class="login-form">
            <input type="text" name="username" placeholder="用户名">
            <input type="password" name="password" placeholder="密码">
            <button type="submit" name="submit">登录</button>
        </form>
        <p class="register-link"><a href="register.php">注册新账号</a></p>
    </div>
</body>
</html>
