<?php
// 从 POST 请求中获取短链接和长链接参数
$shortUrl = $_POST['short_url'];
$longUrl = $_POST['long_url'];

// 将短链接和长链接存储到 sa.txt 文件中
file_put_contents('/sa/sa.txt', "\n$shortUrl|$longUrl", FILE_APPEND);
?>