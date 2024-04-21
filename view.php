<?php







// 开始会话之前不要有任何输出，包括 HTML、空格、换行等


session_start(); // 开启会话

?>
<!DOCTYPE html>
<html>
<head>
    <title>查看帖子 | 深圳市潜龙学校论坛</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="style3.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
            margin: 0;
            padding: 0;
        }
        header {
            background-color: #4a235a;
            color: #fff;
            text-align: center;
            padding: 20px 0;
        }
        nav ul {
            list-style-type: none;
            padding: 0;
        }
        nav ul li {
            display: inline;
            margin-right: 20px;
        }
        nav ul li a {
            text-decoration: none;
            color: #fff;
            font-weight: bold;
        }
        main {
            max-width: 800px;
            margin: 20px auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        section {
            margin-bottom: 20px;
        }
        h1, h2 {
            color: #333;
        }
        ul {
            list-style-type: none;
            padding: 0;
        }
        li {
            border: 1px solid #ddd;
            margin-bottom: 10px;
            padding: 10px;
            border-radius: 5px;
        }
        a {
            text-decoration: none;
            color: #0077cc;
        }
        a:hover {
            text-decoration: underline;
            color: #2F4F4F;
        }
        footer {
            background-color: #333;
            color: #fff;
            text-align: center;
            padding: 10px 0;
        }
        .floating-button {
            position: fixed;
            bottom: 30px;
            right: 30px;
            background-color: #4a235a;
            color: #fff;
            width: 60px;
            height: 60px;
            border-radius: 50%;
            text-align: center;
            line-height: 60px;
            cursor: pointer;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        /* 新增的 CSS 样式 */
        .comment-wrapper {
            max-width: 600px; /* 设置评论框的最大宽度 */
            margin: 0 auto; /* 将评论框居中 */
            padding: 20px; /* 添加内边距 */
        }

        .comment-input-container {
            width: 100%; /* 设置评论框容器的宽度为100% */
        }

        /* 调整评论框的样式 */
        .custom-textarea {
            width: 100%; /* 将评论框的宽度设置为100% */
            height: 100px; /* 调整评论框的高度 */
        }
    </style>
</head>
<body>

<header>
    <br>
    <br>
    <div class="header-content">
        <img src="OIP.png" alt="Logo" width="85" height="80">
        <h1 style="color: white;">查看帖子</h1> <!-- 将文字颜色改为白色 -->
        <nav>
            <ul>
                <li><a href="index.php">首页</a></li>
                <li><a href="new.php">发表帖子</a></li>
            </ul>
        </nav>
    </div>
</header>

<main>
    <section>
        <?php
        #session_start(); // 开启会话
        // 获取帖子标题
        if (isset($_GET['title'])) {
            $title = $_GET['title'];

            // 读取帖子数据
            $posts = file("posts.txt");

            // 查找匹配的帖子
            $found = false;
            foreach ($posts as $post) {
                list($postTitle, $author, $content, $timestamp) = explode("|", $post);
                if (trim($postTitle) == $title) {
                    $found = true;
                    echo "<h2>$postTitle</h2>";
                    echo "<p style='font-size: 10px'>作者: $author | $timestamp</p><br>";
                    echo "<p style='font-size: 12px'>$content</p>";
                    echo "<hr>";
                    echo "<div class='share-container'>";
                    echo "<button id='shareBtn' style='class:shareBtn'>分享</button>";
                    echo "</div>";

                    // 判断是否显示删除按钮
                    if (isset($_SESSION['username']) && $_SESSION['username'] == $author) {
                        echo "<div class='share-container'><button onclick='deletePost(\"$title\")'>删除帖子</button></div>";
                    }

                    // 显示回复表单和已有回复
                    echo "<div class='comment-wrapper'>"; // 添加评论框容器
                    echo "<h1 style='font-size: 14px'>回复：</h1>";
                    echo "<form action='reply.php' method='post'>";
                    echo "<input type='hidden' name='title' value='$title'>";
                    echo "<div class='comment-input-container'>"; // 添加评论框容器
                    echo "<textarea class='custom-textarea' name='reply' rows='2' cols='50'></textarea><br>";
                    echo "<input type='submit' value='提交' class='submit-btn'>";
                    echo "</div>"; // 关闭评论框容器
                    echo "</form>";

                    // 显示已有回复
                    echo "<h1 style='font-size: 14px'>评论：</h1>";
                    $replies = file("replies.txt");
                    foreach ($replies as $reply) {
                        list($replyTitle, $replyContent, $replyAuthor, $replyTimestamp) = explode("|", $reply);
                        if (trim($replyTitle) == $title) {
                            echo "<p style='font-size: 12px'>@$replyAuthor ： </p>";
                            echo "<p2 style='font-size: 12px'>$replyContent</p2>";
                            echo "<p3 style='float: right; font-size: 12px'>  $replyTimestamp</p3>";
                            echo "<hr>";
                        }
                    }
                    echo "</div>"; // 关闭评论框容器
                    break;
                }
            }

            if (!$found) {
                echo "<p>帖子不存在</p>";
            }
        } else {
            echo "<p>无效的title参数</p>";
        }
        ?>
    </section>
</main>



<footer>
    <p>Minur © 2022-2024 版权所有</p>
</footer>

<a href="#" class="floating-button">&#8593;</a>

<script>
    // 回到顶部功能
    document.querySelector('.floating-button').addEventListener('click', function() {
        window.scrollTo({
            top: 0,
            behavior: 'smooth'
        });
    });
</script>
<script>
    // 删除帖子函数
    function deletePost(title) {
        if (confirm("确定要删除此帖子吗？")) {
            // 发送 Ajax 请求到后端删除帖子
            var xhr = new XMLHttpRequest();
            xhr.open('POST', 'delete_post.php', true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xhr.onreadystatechange = function() {
                if (xhr.readyState === XMLHttpRequest.DONE && xhr.status === 200) {
                    // 处理删除结果，刷新页面或其他操作
                    location.reload(); // 删除成功后刷新页面
                }
            };
            xhr.send('title=' + encodeURIComponent(title));
        }
    }
</script>
<script>
document.getElementById("shareBtn").addEventListener("click", function() {
    var currentUrl = window.location.href;
    generateShortUrl(currentUrl);
});

function generateShortUrl(longUrl) {
    var xhr = new XMLHttpRequest();
    xhr.open("POST", "s.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhr.onreadystatechange = function() {
        if (xhr.readyState === XMLHttpRequest.DONE && xhr.status === 200) {
            var response = JSON.parse(xhr.responseText);
            if(response.short_url) {
                var shareText = "【<?php echo $title; ?>-潜龙论坛】 " + response.short_url;
                copyToClipboard(shareText);
                alert("链接已复制，接下来可以分享给你的朋友了！");
            } else {
                alert("生成链接失败，请重试");
            }
        }
    };
    xhr.send("long_url=" + encodeURIComponent(longUrl));
}

function copyToClipboard(text) {
    var textarea = document.createElement("textarea");
    textarea.value = text;
    document.body.appendChild(textarea);
    textarea.select();
    document.execCommand("copy");
    document.body.removeChild(textarea);
}
</script>


</body>
</html>