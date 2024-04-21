<?php
ini_set('date.timezone', 'Asia/Shanghai');
session_start();

// 检查是否已设置用户名并且用户名不是 'admin'
if (!isset($_SESSION['username']) || $_SESSION['username'] !== 'admin') {
    header("Location: index.php");
    exit();
}

// 如果用户名是 'admin'，则继续执行以下代码
$username = $_SESSION['username'];
$posts = file("posts.txt");
?>
<!DOCTYPE html>
<html>
<head>
    <title>删除用户 - 潜龙论坛管理面板</title>
    <meta name="viewport" content="width=device-width, initial-scale=0.6">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
            margin: 0;
            padding: 20px;
            display: grid;
            grid-template-columns: 200px 1fr;
            position: relative;
            min-height: 100vh;
        }
        #sidebar {
            background-color: #ddd;
            padding: 20px;
        }
        #content {
            padding: 20px;
        }
        h1 {
            color: #4a235a;
        }
        form {
            background-color: #fff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            max-width: 600px;
            margin: 0 auto;
        }
        label {
            display: block;
            margin-bottom: 10px;
        }
        input[type="checkbox"] {
            margin-right: 10px;
        }
        input[type="submit"] {
            padding: 38px 66px;
            font-size: 24px;
            background-color: #4a235a;
            color: #fff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        input[type="submit"]:hover {
            background-color: #3c1e45;
        }
        .user-item {
            display: flex;
            align-items: center;
            margin-bottom: 10px;
        }
        .sidebar-link {
            display: block;
            margin-bottom: 10px;
            text-decoration: none;
            color: #333;
            padding: 5px;
            background-color: #ccc;
            border-radius: 5px;
            text-align: center;
        }
        .sidebar-link:hover {
            background-color: #bbb;
        }
        #delete-btn-container {
            position: fixed;
            bottom: 20px;
            right: 20px;
            z-index: 999;
        }
    </style>
</head>
<body>

<div id="sidebar">
    <br><br><br>
    <h2>管理面板</h2>
    <a href="admin.php" class="sidebar-link">显示数据</a>
    <a href="delete_post_form.php" class="sidebar-link">删除帖子</a>
    <a href="delete_user_form.php" class="sidebar-link">删除用户</a>
    <a href="del_rep.php" class="sidebar-link">删除评论</a>
    <a href="/" class="sidebar-link">回到论坛</a>
</div>

<div id="content">
    <br><br><br>
    <h1>删除用户</h1>

    <form action="delete_user.php" method="post" onsubmit="return confirm('确定要删除选中的用户吗？');">
        <?php
        // 读取用户列表
        $usersFile = "users.txt";
        $users = file($usersFile);
        foreach ($users as $user) {
            list($username) = explode("|", $user);
            echo "<div class='user-item'>";
            echo "<input type='checkbox' name='selected_users[]' value='$username' id='$username'>";
            echo "<label for='$username'>$username</label>";
            echo "<hr/>";
            echo "</div>";
        }
        ?>
        <div id="delete-btn-container">
            <input type="submit" value="删除选中的用户">
        </div>
    </form>
</div>

</body>
</html>
