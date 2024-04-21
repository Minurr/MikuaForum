<?php
// 检查用户是否在 adminlist.txt 中
function isUserAdmin($username) {
    $adminListFile = "adminlist.txt";

    // 读取 adminlist.txt 文件中的所有行到数组
    $admins = file($adminListFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

    // 检查用户名是否在管理员列表中
    return in_array($username, $admins);
}

// 获取当前用户
$currentUsername = isset($_SESSION['username']) ? $_SESSION['username'] : null;
error_log("Current Username: " . $currentUsername);
error_log("Admins List: " . implode(', ', $admins)); 
// 如果当前用户不是管理员，则重定向到另一个页面
if (!$currentUsername || !isUserAdmin($currentUsername)) {
    header("Location: index.php"); // 可替换为其他页面路径或输出访问被拒绝的消息
    exit;
}
?>
