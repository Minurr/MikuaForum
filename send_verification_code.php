<?php
session_start();

//检查是否提交了手机号
if(isset($_POST['phone'])){
    //获取手机号
    $phone = $_POST['phone'];

    //调用互亿无线短信接口发送验证码
    $target = "http://106.ihuyi.com/webservice/sms.php?method=Submit";
    $mobile_code = mt_rand(1000, 9999); //生成随机验证码
    $_SESSION['verification_code'] = $mobile_code; //保存验证码到session中

    //设置短信内容
    $content = "您的验证码是：" . $mobile_code . "。请不要把验证码泄露给其他人。";

    //构造POST请求数据
    $post_data = "account=C80780237&password=0e861db73d28cf18e61d655d7dfd7973&mobile=".$phone."&content=".rawurlencode($content);

    //请求短信接口
    $result = Post($post_data, $target);

    //解析返回结果
    $result_arr = xml_to_array($result);

    //输出发送结果
    echo json_encode(array('status' => 'success', 'verification_code' => $mobile_code));
} else {
    echo json_encode(array('status' => 'error', 'message' => '未收到手机号'));
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
?>