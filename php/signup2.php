<?php
session_start();
if (isset($_SESSION['login_user'])){
    header('Location: home.php');
}
//クロスサイトスクリプティング
function h($s) {return htmlspecialchars($s, ENT_QUOTES, "UTF-8");}


$errer = h($_GET['errer']);
// 先に保存したトークンと送信されたトークンが一致するか確認します
$csrf_token=h($_POST["csrf_token"]);
if (isset($csrf_token) 
&& $csrf_token === $_SESSION['csrf_token']) {
//後続処理

} elseif($errer != 'duplicate') {
    header("HTTP/1.1 400 Not Found");
    include ('400.php');
    exit;
}

 // 暗号学的的に安全なランダムなバイナリを生成し、それを16進数に変換することでASCII文字列に変換します
 $toke_byte = openssl_random_pseudo_bytes(16);
 $csrf_token = bin2hex($toke_byte);
 // 生成したトークンをセッションに保存します
 $_SESSION['csrf_token'] = $csrf_token;

 $get_first_name=h($_GET['first_name']);
 $get_last_name=h($_GET['last_name']);
$first_name=h($_POST['first_name']);
if($first_name==''){
    $first_name=null;
}
if(isset($first_name)){
}else{
    $first_name=$get_first_name;
}
$last_name=h($_POST['last_name']);
if($last_name==''){
    $last_name=null;
}
if(isset($last_name)){
}else{
    $last_name=$get_last_name;
}

?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/html5reset-1.6.1.css">
    <link rel="stylesheet" href="../css/same_style.css">
    <link rel="stylesheet" href="../css/index.css">
    <title>signup2</title>
</head>
<body>

    <h1>creat the login id, login password</h1>
    <?php
    if($errer == 'duplicate'){
        echo '<p class="text">このIDは既に使用されています。</p>';
    }
    $login_id=h($_GET['login_id']);
    if(isset($login_id)){
        $loginidtemp='ID; '.$login_id;
    }else{
        $loginidtemp='ID';
    }
    ?>
    <div class="main">
    <form name="creat_id" action="signup_process.php" method="POST">
       
        <input type="hidden" name="csrf_token" value="<?=$csrf_token?>">    
        
        <input type="hidden" name="first_name" value="<?= $first_name?>">
        
        <input type="hidden" name="last_name" value="<?= $last_name?>">
        <p id="id_error"></p>
        <div>
        <input class="textbox" type="text" name="login_id" placeholder="<?=$loginidtemp?>" required>
        </div>
        <div>
        <input class="textbox" type="password" name="login_pass" placeholder="password" required>
        </div>
        <div>
        <a class="button1 changlink" href="login.php">login</a>
        <input class="button1" type="submit">
        </div>
    </form>
</div>
</body>
</html>