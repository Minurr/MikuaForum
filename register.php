<?php
session_start();

// 检查是否已经登录
if (isset($_SESSION['username'])) {
    header("Location: index.php");
    exit();
}
// 在注册成功后或者显示错误信息时清空已填写的内容
unset($_SESSION['filled_data']);
$_SESSION['filled_data'] = $_POST;
//检查是否提交了手机号
if(isset($_POST['send_verification_code'])){
    //获取手机号
    $phone = $_POST['phone'];

    //调用互亿无线短信接口发送验证码
    $target = "http://106.ihuyi.com/webservice/sms.php?method=Submit";
    $mobile_code = random(4,1); //生成随机验证码
    $_SESSION['verification_code'] = $mobile_code; //保存验证码到session中

    //设置短信内容
    $content = "您的验证码是：" . $mobile_code . "。请不要把验证码泄露给其他人。";

    //构造POST请求数据
    $post_data = "account=xxxxxxx&password=xxxxxxxxx&mobile=".$phone."&content=".rawurlencode($content);

    //请求短信接口
    $result = Post($post_data, $target);

    //解析返回结果
    $result_arr = xml_to_array($result);

    //输出发送结果
    echo $result_arr['SubmitResult']['msg'];
}

// 检查是否提交了表单
if (isset($_POST['submit'])) {
    // 获取表单数据
    $username = $_POST['username'];
    $password = $_POST['password'];
    $confirm = $_POST['confirm'];
    $phone = $_POST['phone'];
    $verification_code = $_POST['verification_code']; // 新增：获取验证码

    // 检查手机号格式是否正确
    if (!preg_match('/^1(?!([0-9])\1{2,})[3456789]\d{9}$/', $phone)) {
        $error = "手机号格式不正确";
    } elseif (empty($verification_code) || $verification_code != $_SESSION['verification_code']) { // 新增：检查验证码是否正确
        $error = "验证码错误";
    } else {
        // 其他注册逻辑...
        // 将用户名、密码和手机号追加到文件中
        file_put_contents("users.txt", "\n$username,$password,$phone", FILE_APPEND);
        // 跳转到登录页面
        header("Location: login.php?success=注册成功，请登录");
        exit();
    }
}

//请求数据到短信接口，检查环境是否 开启 curl init。
function Post($curlPost, $url){
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_HEADER, false);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_NOBODY, true);
    curl_setopt($curl, CURLOPT_POST, true);
    curl_setopt($curl, CURLOPT_POSTFIELDS, $curlPost);
    $return_str = curl_exec($curl);
    curl_close($curl);
    return $return_str;
}

//将 xml数据转换为数组格式。
function xml_to_array($xml){
    $reg = "/<(\w+)[^>]*>([\\x00-\\xFF]*)<\\/\\1>/";
    if(preg_match_all($reg, $xml, $matches)){
        $count = count($matches[0]);
        for($i = 0; $i < $count; $i++){
            $subxml= $matches[2][$i];
            $key = $matches[1][$i];
            if(preg_match( $reg, $subxml )){
                $arr[$key] = xml_to_array( $subxml );
            }else{
                $arr[$key] = $subxml;
            }
        }
    }
    return $arr;
}

//random() 函数返回随机整数。
function random($length = 6 , $numeric = 0) {
    PHP_VERSION < '4.2.0' && mt_srand((double)microtime() * 1000000);
    if($numeric) {
        $hash = sprintf('%0'.$length.'d', mt_rand(0, pow(10, $length) - 1));
    } else {
        $hash = '';
        $chars = 'ABCDEFGHJKLMNPQRSTUVWXYZ23456789abcdefghjkmnpqrstuvwxyz';
        $max = strlen($chars) - 1;
        for($i = 0; $i < $length; $i++) {
            $hash .= $chars[mt_rand(0, $max)];
        }
    }
    return $hash;
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>注册 | 深圳市潜龙学校论坛</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="styles2.css">
    <style>
    .note {
        font-size: 14px;
        color: #666;
        margin-top: 10px;
    }
    .success-message {
        position: fixed;
        top: 20px;
        left: 50%;
        transform: translateX(-50%);
        background-color: #4CAF50;
        color: white;
        padding: 15px;
        border-radius: 5px;
        z-index: 999;
    }
    
    .error {
        position: fixed;
        top: 20px;
        left: 50%;
        transform: translateX(-50%);
        background-color: red;
        color: white;
        padding: 15px;
        border-radius: 5px;
        z-index: 999;
    }
    .phone-input {
        display: flex;
        align-items: center;
    }
    .phone-input input[type="text"],
    .phone-input input[type="submit"] {
        margin-right: 10px;
    }

    </style>
</head>
<body>
<div class="register-container">
    <h2>学校论坛注册</h2>
    <?php
    // 显示错误信息或成功信息
    if (isset($error)) {
        echo "<p class='error'>$error</p>";
    }
    if (isset($_GET['success'])) {
        echo "<p class='success-message'>" . $_GET['success'] . "</p>";
    }
    ?>
    <form action="register.php" method="post" class="register-form">
        <?php
    // 显示发送成功提示消息
    if (isset($result_arr['SubmitResult']['msg']) && $result_arr['SubmitResult']['code'] == '2') {
        echo "<div class='success-message'>" . $result_arr['SubmitResult']['msg'] . "</div>";
    }
    ?>
        <input type="text" name="username" placeholder="用户名" value="<?php echo isset($_SESSION['filled_data']['username']) ? $_SESSION['filled_data']['username'] : ''; ?>">
        <input type="password" name="password" placeholder="密码" value="<?php echo isset($_SESSION['filled_data']['password']) ? $_SESSION['filled_data']['password'] : ''; ?>">
        <input type="password" name="confirm" placeholder="确认密码" value="<?php echo isset($_SESSION['filled_data']['confirm']) ? $_SESSION['filled_data']['confirm'] : ''; ?>">
        <div class="phone-input">
            <input type="text" name="phone" id="phone" placeholder="手机号" value="<?php echo isset($_SESSION['filled_data']['phone']) ? $_SESSION['filled_data']['phone'] : ''; ?>">
            <input type="submit" name="send_verification_code" onclick="sendVerificationCode()" value="发送验证码">
        </div>
        <input type="text" name="verification_code" placeholder="验证码">
        <button type="submit" name="submit">注册</button>
    </form>
    <p class="note">你们在论坛输入的密码，我们采用SQL自写加密进行加密，甚至服务器都不知道你的真实密码，请放心！</p>
    <p class="register-link"><a href="login.php">登录账号</a></p>
</div>

<script>

    var isSending = false; // 用于跟踪是否正在发送验证码的变量

    function sendVerificationCode() {
        if (isSending) return; // 如果正在发送验证码，则退出函数
        isSending = true; // 设置标志以指示正在发送验证码

        var phone = document.getElementById('phone').value; // 获取手机号
        var sendBtn = document.getElementsByName('send_verification_code')[0]; // 获取发送按钮

        // 使用PHP发送验证码的AJAX请求
        var xhr = new XMLHttpRequest();
        xhr.onreadystatechange = function() {
            if (xhr.readyState === XMLHttpRequest.DONE) {
                // 如果需要，在此处处理响应
                isSending = false; // 重置标志
            }
        };
        xhr.open('POST', 'register.php', true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xhr.send('send_verification_code=true&phone=' + encodeURIComponent(phone));
    }
</script>

<script>
    // 在页面加载时检查本地存储中是否存在发送按钮的禁用状态，并相应地设置按钮状态
    window.onload = function() {
        var sendBtn = document.getElementsByName('send_verification_code')[0];
        var sendBtnDisabled = localStorage.getItem('sendBtnDisabled');
        if (sendBtnDisabled) {
            sendBtn.disabled = true;
        }
    };
</script>

</body>
</html>