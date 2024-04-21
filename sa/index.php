<?php
// 开启会话
session_start();

// 处理长链接提交
if (isset($_POST['long_url'])) {
    $longUrl = $_POST['long_url'];
    // 生成随机的短链接标识符
    $shortUrl = generateShortUrl();
    // 保存到 sa.txt 文件
    saveShortUrl($shortUrl, $longUrl);
    // 返回短链接给用户
    echo $shortUrl;
    exit;
}

// 处理短链接跳转
if (isset($_GET['l'])) {
    $shortUrl = $_GET['l'];
    // 查找对应的长链接
    $longUrl = findLongUrl($shortUrl);
    // 如果找到了对应的长链接，则跳转到该链接，否则显示错误信息
    if ($longUrl) {
        header("Location: $longUrl");
        exit;
    } else {
        echo "短链接无效";
        exit;
    }
}

// 生成随机的短链接标识符
function generateShortUrl() {
    // 定义短链接的字符集合
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $shortUrl = '';
    // 生成6位的随机字符串作为短链接
    for ($i = 0; $i < 6; $i++) {
        $index = rand(0, strlen($characters) - 1);
        $shortUrl .= $characters[$index];
    }
    return $shortUrl;
}

// 保存短链接到 sa.txt 文件
function saveShortUrl($shortUrl, $longUrl) {
    // 打开 sa.txt 文件，如果不存在则创建
    $file = fopen('sa.txt', 'a+');
    // 写入短链接和长链接到文件中
    fwrite($file, "$shortUrl|$longUrl\n");
    // 关闭文件
    fclose($file);
}

// 查找对应的长链接
function findLongUrl($shortUrl) {
    // 读取 sa.txt 文件
    $lines = file('sa.txt');
    foreach ($lines as $line) {
        // 按照 | 分割每行，并检查是否匹配短链接
        list($storedShortUrl, $longUrl) = explode('|', trim($line));
        if ($storedShortUrl === $shortUrl) {
            return $longUrl;
        }
    }
    return false; // 如果找不到对应的长链接，则返回 false
}
?>