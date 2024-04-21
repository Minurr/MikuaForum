<?php
// 检查是否已经登录
ini_set('date.timezone', 'Asia/Shanghai');
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

// 获取用户名
$username = $_SESSION['username'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // 获取回复内容
    $title = $_POST['title'];
    $replyContent = $_POST['reply'];

    // 敏感词过滤
    $filteredWords = ["iframe", "herf", "script", "习近平", "onerror", "?php", "javaScript", "tion.repla"];
    foreach ($filteredWords as $word) {
        if (stripos($replyContent, $word) !== false) {
            $error = "回复内容包含不允许的词语";
            break;
        }
    }

    if (!isset($error) && !empty($replyContent)) {
        // 读取回复数据文件
        $replies = file("replies.txt");

        // 添加新回复
        $newReply = "$title|$replyContent|$username|" . date("Y-m-d H:i:s");
        file_put_contents("replies.txt", "\n$newReply", FILE_APPEND);

        // 重定向到查看帖子页面
        header("Location: view.php?title=$title");
        exit();
    }
}
?>
