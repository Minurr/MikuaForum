<?php
ini_set('date.timezone', 'Asia/Shanghai');
session_start();

// 检查是否已设置用户名并且用户名是管理员
if (!isset($_SESSION['username']) || $_SESSION['username'] !== 'admin') {
    header("Location: index.php");
    exit();
}

// 如果是管理员，则继续执行以下代码
$username = $_SESSION['username'];
$repliesFile = "replies.txt";
$replies = file($repliesFile);
?>
<!DOCTYPE html>
<html>
<head>
    <title>删除评论 - 潜龙论坛管理面板</title>
    <meta name="viewport" content="width=device-width, initial-scale=0.6">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
            margin: 0;
            padding: 20px;
            display: grid;
            grid-template-columns: 200px 1fr;
            min-height: 100vh; /* 最小高度占据整个视口 */
        }
        #sidebar {
            background-color: #ddd;
            padding: 20px;
        }
        #content {
            padding: 20px;
            position: relative; /* 相对定位，用于包含绝对定位的按钮 */
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
            display: flex;
            align-items: center; /* 将复选框和文本垂直居中对齐 */
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
            position: fixed; /* 固定定位，相对于浏览器窗口 */
            bottom: 20px; /* 距离视口底部 20px */
            right: 20px; /* 距离视口右侧 20px */
            z-index: 999; /* 可选：确保按钮位于其他内容上方 */
        }
        input[type="submit"]:hover {
            background-color: #3c1e45;
        }
        .post-info {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-top: 10px;
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
    <a href="admin.php" class="sidebar-link">显示数据</a>
    <a href="delete_post_form.php" class="sidebar-link">删除帖子</a>
    <a href="delete_user_form.php" class="sidebar-link">删除用户</a>
    <a href="del_rep.php" class="sidebar-link">删除评论</a>
    <a href="/" class="sidebar-link">回到论坛</a>
</div>

<div id="content">
    <br><br><br>
    <h1>删除评论</h1>

    <form action="delete_reply.php" method="post" onsubmit="return confirm('确定要删除选中的评论吗？');">
        <?php
        // 读取评论列表并显示
        foreach ($replies as $reply) {
            list($postTitle, $author, $comment, $timestamp) = explode("|", $reply);
            echo "<div class='reply-item'>";
            echo "<div class='reply-info'>";
            echo "<span><strong>帖子标题:</strong> $postTitle</span><br>";
            echo "<span><strong>内容:</strong> $author</span><br>";
            echo "<span><strong>时间:</strong> $timestamp</span><br>";
            echo "</div>";
            echo "<p>$comment</p>";
            echo "<input type='checkbox' name='selected_replies[]' value='$reply'>";
            echo "<hr/>";
            echo "</div>";
        }
        ?>
        <input type="submit" value="删除选中的评论">
    </form>
</div>

</body>
</html>
