<?php
// 检查是否有选中的用户要删除
if (isset($_POST['selected_users']) && is_array($_POST['selected_users'])) {
    // 要删除的用户名列表
    $selectedUsers = $_POST['selected_users'];

    // 用户列表文件路径
    $usersFile = "users.txt";

    // 读取用户列表
    $users = file($usersFile);

    // 打开用户列表文件以写入模式
    $fileHandle = fopen($usersFile, 'w');

    // 遍历用户列表，将不在选中列表中的用户写回文件
    foreach ($users as $user) {
        list($username) = explode("|", $user);
        if (!in_array($username, $selectedUsers)) {
            fwrite($fileHandle, $user);
        }
    }

    // 关闭文件句柄
    fclose($fileHandle);
    echo "删除成功。";
    // 重定向回管理面板页面
    exit;
} else {
    // 如果没有选中的用户，则显示错误消息或执行其他操作
    echo "没有选中的用户要删除";
}
?>
