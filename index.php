<?php
ini_set('date.timezone', 'Asia/Shanghai');
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}
$username = $_SESSION['username'];
$posts = file("posts.txt");

// 置顶的帖子标题数组
$stickyPostTitles = array("【公告】祝贺论坛恢复运行！", "【必看】论坛发言守则");

// 提取置顶帖子
$stickyPosts = array();

foreach ($posts as $post) {
    list($title, $author, $content, $timestamp) = explode("|", $post);
    $cleanTitle = trim($title);

    if (in_array($cleanTitle, $stickyPostTitles)) {
        // 将置顶帖子存入数组
        $stickyPosts[] = array(
            'title' => $cleanTitle,
            'author' => $author,
            'content' => $content,
            'timestamp' => $timestamp
        );
    }
}

// 从原始帖子列表中移除置顶帖子
$posts = array_filter($posts, function($post) use ($stickyPostTitles) {
    list($title, $author, $content, $timestamp) = explode("|", $post);
    $cleanTitle = trim($title);
    return !in_array($cleanTitle, $stickyPostTitles);
});

// 逆序排列剩余帖子
$posts = array_reverse($posts);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>首页 | 深圳市潜龙学校论坛</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        function checkNewReplies() {
            $.ajax({
                url: 'check_new_replies.php',
                method: 'GET',
                success: function(response) {
                    if (response.newReplies > 0) {
                        // 处理有新回复的情况，可以弹出通知或者在页面上展示新消息的提示
                        alert('您收到了 ' + response.newReplies + ' 条新回复！');
                    }
                },
                complete: function() {
                    // 继续下一次长轮询
                    setTimeout(checkNewReplies, 5000); // 每隔5秒发起一次查询
                }
            });
        }

        // 页面加载完成后开始长轮询查询新回复
        $(document).ready(function() {
            checkNewReplies();
        });
    </script>
    <link rel="stylesheet" href="style3.css">
    <style>
        .floating-button {
            position: fixed;
            bottom: 30px;
            right: 30px;
            background-color: #3498db;
            color: #fff;
            width: 60px;
            height: 60px;
            border-radius: 50%;
            text-align: center;
            line-height: 60px;
            cursor: pointer;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .menu-container {
            position: fixed;
            bottom: 100px;
            right: 30px;
            background-color: #fff;
            border-radius: 5px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            padding: 10px;
            display: none;
            z-index: 1000;
        }

        .menu-item {
            padding: 10px;
            cursor: pointer;
            border-bottom: 1px solid #eee;
        }

        .menu-item:hover {
            background-color: #f5f5f5;
        }
    </style>
</head>
<body>

<header>
    <br>
    <br>
    <div class="header-content">
        <img src="OIP.png" alt="Logo" width="85" height="80">
        <h1>学校论坛</h1>
        <div class="user-info">
            <span>欢迎, <?php echo htmlspecialchars($username); ?></span>
            <a href="logout.php">退出</a>
        </div>
    </div>
    <br>
    <p>当前用户总数: <span id="userCount"></span></p>
</header>

<main>
    <div class="post-list">
        <?php
        // 输出置顶帖子
        foreach ($stickyPosts as $stickyPost) {
            echo '<div class="post-item" style="background-color: #d3d3d3;">'; // 置顶帖子背景色
            echo '<h2><a href="view.php?title=' . urlencode($stickyPost['title']) . '">' . htmlspecialchars($stickyPost['title']) . '</a></h2>';
            echo '<p class="author-info">By ' . htmlspecialchars($stickyPost['author']) . ' | ' . htmlspecialchars($stickyPost['timestamp']) . '</p>';

            // 获取帖子内容
            $content = $stickyPost['content'];
            $maxChars = 150; // 设定字符阈值
        
            if (mb_strlen($content, 'utf-8') > $maxChars) {
                // 如果内容超出阈值，则显示部分内容和展开链接
                $shortContent = mb_substr($content, 0, $maxChars, 'utf-8');
                // 生成帖子详情页面链接
                $postDetailLink = 'view.php?title=' . urlencode($stickyPost['title']);
                echo "<p class='post-content'>{$shortContent} <a href='{$postDetailLink}' class='show-full-text' data-fulltext='{$content}'>显示全文</a></p>";
            } else {
                // 如果内容未超出阈值，则直接显示内容
                echo "<p class='post-content'>{$content}</p>";
            }
            
            echo '</div>';
        }

        // 输出剩余帖子列表
        foreach ($posts as $post) {
            list($title, $author, $content, $timestamp) = explode("|", $post);

            echo '<div class="post-item">';
            echo '<h2><a href="view.php?title=' . urlencode(trim($title)) . '">' . htmlspecialchars(trim($title)) . '</a></h2>';
            echo '<p class="author-info">By ' . htmlspecialchars($author) . ' | ' . htmlspecialchars($timestamp) . '</p>';

            // 设置显示全文的字符阈值
            $maxChars = 150;
            if (mb_strlen($content, 'utf-8') > $maxChars) {
                $shortContent = mb_substr($content, 0, $maxChars, 'utf-8');
                echo "<p class='post-content'>{$shortContent} <a href='/view.php?title={$title}' class='show-full-text'>显示全文</a></p>";
                echo "<p class='full-text' style='display: none;'>{$content}</p>";
            } else {
                echo "<p class='post-content'>{$content}</p>";
            }

            echo '</div>';
        }
        ?>
    </div>
</main>

<footer>
    <p>Minur &copy; 2022-2024 版权所有</p>
</footer>

<div class="floating-button" id="floatingButton">
    <span>&#9776;</span>
</div>

<div class="menu-container" id="menuContainer">
    <div class="menu-item" id="newPost" style="bold">发布帖子</div>
    </hr></hr>
    <div class="menu-item" id="backToTop">回到顶部</div>
    
    <div class="menu-item" id="downloadApp">官方 App</div>
    <div class="menu-item" id="admin">管理面板</div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const floatingButton = document.getElementById('floatingButton');
    const menuContainer = document.getElementById('menuContainer');
    const backToTop = document.getElementById('backToTop');
    const newPost = document.getElementById('newPost');
    const downloadApp = document.getElementById('downloadApp');
    const admin = document.getElementById('admin');

    floatingButton.addEventListener('click', function() {
        menuContainer.style.display = (menuContainer.style.display === 'block') ? 'none' : 'block';
    });

    backToTop.addEventListener('click', function() {
        window.scrollTo({ top: 0, behavior: 'smooth' });
        menuContainer.style.display = 'none';
    });

    newPost.addEventListener('click', function() {
        window.location.href = 'new.php';
    });

    // 处理下载 App 按钮的点击事件
    downloadApp.addEventListener('click', function() {
        alert('正在下载 App，请稍候...');
        window.location.href = 'https://szqlxx.mikua.icu/qllt.apk';
    });

    // 处理管理面板按钮的点击事件
    admin.addEventListener('click', function() {
        alert('正在跳转米跨校园管理面板for潜龙，请注意，非管理员账号会跳转至首页！！');
        window.location.href = 'https://szqlxx.mikua.icu/admin.php';
    });
});

// 定时获取用户数
function updateUserCount() {
    var xhr = new XMLHttpRequest();
    xhr.open('GET', 'user_count.php', true);
    xhr.onreadystatechange = function() {
        if (xhr.readyState === XMLHttpRequest.DONE && xhr.status === 200) {
            var response = JSON.parse(xhr.responseText);
            var userCount = response.userCount;
            document.getElementById('userCount').textContent = userCount;
        }
    };
    xhr.send();
}

// 初始加载页面时更新一次用户数
updateUserCount();

// 定时检查新消息
function checkNewReplies() {
    setInterval(function() {
        var xhr = new XMLHttpRequest();
        xhr.open('GET', 'check_new_replies.php', true);
        xhr.onreadystatechange = function() {
            if (xhr.readyState === XMLHttpRequest.DONE && xhr.status === 200) {
                var response = JSON.parse(xhr.responseText);
                var newReplies = response.newReplies;

                if (newReplies > 0) {
                    alert('您有新的回复消息！');
                }
            }
        };
        xhr.send();
    }, 5000); // 每5秒查询一次新消息
}

// 启动消息检查
checkNewReplies();

</script>

</body>
</html>
