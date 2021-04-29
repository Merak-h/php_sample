<?php
session_start();
if (isset($_SESSION['login_user'])){
    header('Location: home.php');
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
    <title>signup</title>
</head>
<body>
    <h1>enter your information.</h1>
<div class="main">
    <form action="signup2.php" method="POST">
        <div>
            <input type="hidden" name="csrf_token" value="<?=$csrf_token?>">
        </div>
        <div>
            <input class="textbox" type="text" name="first_name" placeholder="first name" required>
        </div>
        <div>
            <input class="textbox" type="text" name="last_name" placeholder="last name" required>
        </div>
        <div>
            <a class="button1 changlink" href="login.php">login</a>

            <input class="button1" type="submit">
        </div>
    </form>
</div>
</body>
</html>