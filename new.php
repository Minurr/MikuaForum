<?php
ini_set('date.timezone', 'Asia/Shanghai');
session_start();

// 检查是否已经登录
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

// 获取用户名
$username = $_SESSION['username'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // 获取表单数据，并进行处理
    $title = str_replace(["\r", "\n"], '', $_POST['title']); // 过滤换行符
    $content = str_replace(["\r", "\n"], '<br>', $_POST['content']); // 将换行符转换为<br>标签
    
    // 敏感词过滤
    $filteredWords = ["iframe", "herf", "script", "习近平", "onerror", "/style", "?php", "javaScript", "tion.repla", "乌龟", "wugui", "xkj", "🐢"];
    foreach ($filteredWords as $word) {
        if (stripos($title, $word) !== false || stripos($content, $word) !== false) {
            $error = "标题或内容包含不允许的内容";
            break;
        }
    }

    if (!isset($error) && !empty($title) && !empty($content)) {
        // 读取帖子数据文件
        $posts = file("posts.txt");

        // 检查标题是否已经存在
        foreach ($posts as $post) {
            list($t, $a, $c, $timestamp) = explode("|", $post);
            if (trim($t) == $title) {
                $error = "已存在相同标题";
                break;
            }
        }

        if (!isset($error)) {
            // 获取当前日期和时间
            $currentDateTime = date('Y-m-d H:i:s');
            
            // 将标题、作者、内容和日期时间追加到文件中
            $newPost = "\n$title|$username|$content|$currentDateTime";
            file_put_contents("posts.txt", $newPost, FILE_APPEND);
            
            // 跳转到查看页面
            header("Location: view.php?title=$title");
            exit();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>发帖 | 深圳市潜龙学校论坛</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="login-container">
        <h2>发帖</h2>
        <?php
        // 显示错误信息
        if(isset($error)){
            echo "<p class='error'>$error</p>";
        }
        ?>
        <form action="new.php" method="post" class="login-form">
            <input type="text" name="title" placeholder="标题">
            <textarea name="content" rows="10" cols="30" placeholder="内容"></textarea>
            <button type="submit" name="submit">发表</button>
        </form>
        <p class="register-link"><a href="index.php">返回主页</a></p>
    </div>
</body>
</html>
