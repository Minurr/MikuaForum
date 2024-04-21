<?php
// 处理用户上传的长链接并生成短链接
if(isset($_POST['long_url'])) {
    $long_url = $_POST['long_url'];
    $short_url = generateShortURL(); // 生成短链接
    storeLink($long_url, $short_url); // 只存储长链接和短链接中的l参数内容到文件中
    $link_data = array("long_url" => $long_url, "short_url" => $short_url);
    echo json_encode($link_data);
    exit(); // 停止执行后续代码
}

// 解析短链接并跳转至对应的长链接
if(isset($_GET['l'])) {
    $short_url = $_GET['l'];
    $file = fopen("links.txt", "r");
    $found = false; // 标记是否找到匹配的长链接
    
    while(!feof($file)) {
        $line = fgets($file);
        $data = explode(" ", $line);
        
        if(trim($data[3]) == $short_url) {
            header("Location: " . $data[0]); // 跳转至对应的长链接
            $found = true;
            break;
        }
    }
    
    fclose($file);
    
    if(!$found) {
        echo "无效的短链接";
    }
}

// 生成短链接函数
function generateShortURL() {
    $characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
    $short_url = '';
    $length = 6; // 短链接长度
    $characters_length = strlen($characters);
    
    for ($i = 0; $i < $length; $i++) {
        $short_url .= $characters[rand(0, $characters_length - 1)];
    }
    
    return "qlxx.fun/s.php?l=$short_url"; // 替换为你的域名和路径
}

// 只存储长链接和短链接中的l参数内容到文件中
function storeLink($long_url, $short_url) {
    // 解析长链接中的l参数内容
    $long_params = parse_url($long_url, PHP_URL_QUERY); // 获取长链接中的查询参数部分
    parse_str($long_params, $long_params_array); // 将查询参数部分解析为关联数组
    $long_l_param = isset($long_params_array['l']) ? $long_params_array['l'] : ''; // 获取长链接中的l参数内容
    
    // 解析短链接中的l参数内容
    $short_params = parse_url($short_url, PHP_URL_QUERY); // 获取短链接中的查询参数部分
    parse_str($short_params, $short_params_array); // 将查询参数部分解析为关联数组
    $short_l_param = isset($short_params_array['l']) ? $short_params_array['l'] : ''; // 获取短链接中的l参数内容
    
    // 将长链接和短链接中的l参数内容存储到文件中
    $file = fopen("links.txt", "a");
    fwrite($file, $long_url . " " . $short_url . " " . $long_l_param . " " . $short_l_param . "\n");
    fclose($file);
}
?>