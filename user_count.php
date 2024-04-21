<?php
// 读取 users.txt 文件的行数作为用户总数
$userCount = count(file("users.txt"));

// 将用户数以 JSON 格式返回给前端
echo json_encode(['userCount' => $userCount]);
?>
