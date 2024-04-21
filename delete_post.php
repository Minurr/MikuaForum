<?php
ini_set('date.timezone', 'Asia/Shanghai');
session_start();

// 检查是否已设置用户名并且用户名是管理员
if (!isset($_SESSION['username']) || $_SESSION['username'] !== 'admin') {
    header("Location: index.php");
    exit();
}

// 如果是管理员，并且有选中的评论要删除
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['selected_replies']) && is_array($_POST['selected_replies'])) {
    // 选中的评论列表
    $selectedReplies = $_POST['selected_replies'];

    // 评论文件路径
    $repliesFile = "replies.txt";

    // 读取评论列表
    $replies = file($repliesFile);

    // 打开评论文件以写入模式
    $fileHandle = fopen($repliesFile, 'w');

    // 遍历评论列表，将不在选中列表中的评论写回文件
    foreach ($replies as $reply) {
        if (!in_array(trim($reply), $selectedReplies)) {
            fwrite($fileHandle, $reply);
        }
    }

    // 关闭文件句柄
    fclose($fileHandle);

    // 重定向回删除评论页面
    header("Location: del_rep.php");
    exit();
} else {
    // 如果没有选中的评论或未通过合法性检查，则重定向到首页或其他适当页面
    echo "异常错误。";
    exit();
}
?>
