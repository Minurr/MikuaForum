<?php
session_start();

if (!isset($_SESSION['username'])) {
    header("Content-Type: application/json");
    echo json_encode(['newReplies' => 0]);
    exit();
}

$username = $_SESSION['username'];
$replies = file("replies.txt");

$newRepliesCount = 0;

foreach ($replies as $reply) {
    list($replyTitle, $replyAuthor, $replyContent, $replyTimestamp, $recipient, $isRead) = explode("|", $reply);

    if (trim($recipient) === $username && trim($isRead) === '0') {
        $newRepliesCount++;
    }
}

header("Content-Type: application/json");
echo json_encode(['newReplies' => $newRepliesCount]);
?>
