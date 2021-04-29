<?php
session_start();
if (!isset($_SESSION['login_user'])){
    header('Location: login.php');
}


 // 暗号学的的に安全なランダムなバイナリを生成し、それを16進数に変換することでASCII文字列に変換します
 $toke_byte = openssl_random_pseudo_bytes(16);
 $csrf_token = bin2hex($toke_byte);
 // 生成したトークンをセッションに保存します
 $_SESSION['csrf_token'] = $csrf_token;

?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/html5reset-1.6.1.css">
    <link rel="stylesheet" href="../css/same_style.css">
    <link rel="stylesheet" href="../css/index.css">
    <title>welcom</title>
</head>
<body>
<h1><?= $_SESSION['user_name']?>さん、<br>ようこそSUBSCへ！</h1>
<h2 class="text">初めにニックネームを決めましょう！</h2>
<div class="main">
<form action="setup2.php" method="POST">
    <input type="hidden" name="csrf_token" value="<?=$csrf_token?>">
    <div>
    <input class="textbox" type="text" name="nickname" placeholder="ニックネーム">
    </div>
    <div>
    <a class="button1 skiplink" href="setup2.php?method=skip">スキップする</a>
    <input class="button1" type="submit" value="決定">
    </div>
</form>
</div>
</body>
</html>