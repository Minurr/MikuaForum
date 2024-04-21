<?php
session_start(); // 启用会话（如果需要）

if (isset($_POST['title'])) {
    $title = $_POST['title'];

    if (!isset($_SESSION['liked_posts'])) {
        $_SESSION['liked_posts'] = array(); // 初始化点赞记录
    }

    if (!in_array($title, $_SESSION['liked_posts'])) {
        // 用户未点过赞，可以执行点赞操作
        $_SESSION['liked_posts'][] = $title; // 记录点赞状态

        // 更新点赞数（这里使用文件存储示例，实际应用中可能使用数据库）
        $likesData = file("likes.txt");
        $updated = false;
        foreach ($likesData as &$like) {
            list($postTitle, $likes) = explode("|", $like);
            if (trim($postTitle) == $title) {
                $likes = (int)$likes + 1;
                $like = "$postTitle|$likes\n";
                $updated = true;
                break;
            }
        }
        if (!$updated) {
            // 如果帖子的点赞记录不存在，则新增
            file_put_contents("likes.txt", "$title|1\n", FILE_APPEND);
        }
        // 更新点赞数文件
        file_put_contents("likes.txt", implode("", $likesData));

        echo 'success'; // 返回成功信息
    } else {
        echo 'already_liked'; // 已经点过赞
    }
} else {
    echo 'error'; // 请求参数错误
}
?>
