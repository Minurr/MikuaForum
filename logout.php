<?php
//退出页面
//销毁会话变量
session_start();
session_destroy();
//跳转到登录页面
header("Location: login.php?success=退出成功！");
exit();
?>
