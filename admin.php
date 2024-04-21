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
    <title>潜龙论坛管理面板</title>
    <meta name="viewport" content="width=device-width, initial-scale=0.6">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
            margin: 0;
            padding: 20px;
            display: grid;
            grid-template-columns: 200px 1fr;
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
        .summary {
            margin-top: 20px;
            background-color: #fff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            display: block; /* 确保默认显示 */
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
    </style>
</head>
<body>

<div id="sidebar">
    <br><br><br>
    <h2>管理面板</h2>
    <a href="#" class="sidebar-link" onclick="showSummary()">显示数据</a>
    <a href="delete_post_form.php" class="sidebar-link">删除帖子</a>
    <a href="delete_user_form.php" class="sidebar-link">删除用户</a>
    <a href="del_rep.php" class="sidebar-link">删除评论</a>
    <a href="index.php" class="sidebar-link">回到论坛</a>
</div>

<div id="content">
    <br><br><br>
    <h1>欢迎使用米跨校园管理面板</h1>

    <!-- 统计信息 -->
    <div class="summary" id="summary">
        <h2>统计信息</h2>
        <?php
        // 统计用户数量
        $usersFile = "users.txt";
        $numUsers = count(file($usersFile));

        // 统计帖子数量
        $postsFile = "posts.txt";
        $numPosts = count(file($postsFile));

        // 统计评论数量（假设评论数据存在 replies.txt 中）
        $commentsFile = "replies.txt";
        $numComments = count(file($commentsFile));

        echo "<p>当前用户数量：$numUsers</p>";
        echo "<p>当前帖子数量：$numPosts</p>";
        echo "<p>当前评论数量：$numComments</p>";
        ?>
    </div>
</div>

<script>
    // 页面加载完成后自动显示统计信息
    document.addEventListener('DOMContentLoaded', function() {
        var summaryDiv = document.getElementById('summary');
        summaryDiv.style.display = 'block'; // 显示统计信息
    });
</script>

</body>
</html>
