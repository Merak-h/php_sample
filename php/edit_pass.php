<?php
  session_start();
  if (!isset($_SESSION['login_user'])) {
    header('Location: login.php');
    exit;
}


//クロスサイトスクリプティング
function h($s) {return htmlspecialchars($s, ENT_QUOTES, "UTF-8");}


 // 暗号学的的に安全なランダムなバイナリを生成し、それを16進数に変換することでASCII文字列に変換します
 $toke_byte = openssl_random_pseudo_bytes(16);
 $csrf_token = bin2hex($toke_byte);
 // 生成したトークンをセッションに保存します
 $_SESSION['csrf_token'] = $csrf_token;


$error=h($_GET['error']);
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/html5reset-1.6.1.css">
    <link rel="stylesheet" href="../css/same_style.css">
    <link rel="stylesheet" href="../css/edit_pass.css">
    <title>password 変更</title>
</head>
<body>
    <div class="topbar">
    <h1>パスワード変更</h1>
    <a href="profile.php"><img class="yajirusi" src="../system_img/yajirusi.png" alt="戻る"></a>
    </div>
    <div class="main">
    <form action="edit_pass_process.php" method="POST">
        <input type="hidden" name="csrf_token" value="<?=$csrf_token?>">
       
       
        <?php
        if($error=='mismatch'){
            echo '<p class="text">パスワードが間違っています。</p>';
        }
        ?> 
        <div>
        <label>現在のパスワード</label>
        <input class="textbox" type="password" name="password">
        
        </div>
        <div>
        <input class="button1" type="submit">
        </div>
    </form>
    </div>
</body>
</html>