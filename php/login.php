<?php
session_start();
if (isset($_SESSION['login_user'])){
    header('Location: home.php');
}


//クロスサイトスクリプティング
function h($s) {return htmlspecialchars($s, ENT_QUOTES, "UTF-8");}



 // 暗号学的的に安全なランダムなバイナリを生成し、それを16進数に変換することでASCII文字列に変換します
 $toke_byte = openssl_random_pseudo_bytes(16);
 $csrf_token = bin2hex($toke_byte);
 // 生成したトークンをセッションに保存します
 $_SESSION['csrf_token'] = $csrf_token;


$login_id = h($_GET['id']);
$errer = h($_GET['errer']);
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/html5reset-1.6.1.css">
    <link rel="stylesheet" href="../css/same_style.css">
    <link rel="stylesheet" href="../css/index.css">
    <title>login</title>

</head>
<body>
    <h1>login</h1>
    <?php
    if($errer == 'exist'){
        echo '<p class="text">IDもしくはpasswordが間違っています</p>';
    }
    ?>
    <div class="main">
        <form action="login_process.php" method="POST">
            <div>
            <input type="hidden" name="csrf_token" value="<?=$csrf_token?>">    
        </div>
        <div>
            <input class="textbox" type="text" name="login_id" placeholder="ID" value="<?=$login_id?>">
        </div>
        <div>
            <input class="textbox" type="password" name="login_pass" placeholder="Password">
        </div>
        <div>
            
            <a class="button1 changlink" href="signup.php">sign up</a>
            <input class="button1" type="submit">
        </div>
        </form>
</body>
</html>