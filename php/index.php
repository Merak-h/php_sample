<?php
session_start();
if (isset($_SESSION['login_user'])){
    header('Location: home.php');
}?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/html5reset-1.6.1.css">
    <link rel="stylesheet" href="../css/same_style.css">
    <link rel="stylesheet" href="../css/index.css">
    <title>index</title>
</head>
<body>
    <h1>SUBSC！</h1>
    <div class="main">
    <h2 class="text2">ログイン、もしくは新規登録をしてください。</h2>
    <div>
    <a class="button1" href="login.php">login</a>
    <a class="button1" href="signup.php">sign up</a>
    </div>
    </div>
</body>
</html>
