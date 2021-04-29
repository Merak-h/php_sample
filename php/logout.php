<?php
session_start();
if (!isset($_SESSION['login_user'])) {
  header('Location: login.php');
  exit;
}
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/html5reset-1.6.1.css">
    <link rel="stylesheet" href="../css/same_style.css">
    <link rel="stylesheet" href="../css/logout.css">
    <title>log out</title>
</head>
<body>
<h1>本当に<?=$_SESSION['login_user']?>さんのアカウントから
ログアウトしますか？</h1>
<div class="main">
<div>
<a class="button1" href="profile.php">cancel</a>
<a class="button1" href="logout_process.php?roulte=ture">log out</a>
</div>
</div>
</body>
</html>