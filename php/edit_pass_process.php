<?php
  session_start();
  if (!isset($_SESSION['login_user'])) {
    header('Location: login.php');
    exit;
}
$login_id = $_SESSION['login_user'];



//クロスサイトスクリプティング
function h($s) {return htmlspecialchars($s, ENT_QUOTES, "UTF-8");}

// 先に保存したトークンと送信されたトークンが一致するか確認します
$csrf_token=h($_POST["csrf_token"]);
if (isset($csrf_token) 
&& $csrf_token === $_SESSION['csrf_token']) {
//後続処理

} else {
    header("HTTP/1.1 400 Not Found");
    include ('400.php');
    exit;
}


 // 暗号学的的に安全なランダムなバイナリを生成し、それを16進数に変換することでASCII文字列に変換します
 $toke_byte = openssl_random_pseudo_bytes(16);
 $csrf_token = bin2hex($toke_byte);
 // 生成したトークンをセッションに保存します
 $_SESSION['csrf_token'] = $csrf_token;



$password = h($_POST['password']);


//ファイルの呼び出し
$filename='../account/'.$login_id.'/user.json';
if ( ($fp = fopen($filename, 'rb')) !== FALSE ){
    $json_str = fread($fp, filesize($filename));
    $json = json_decode($json_str, TRUE);
    fclose($fp);
}
if(!password_verify($password, $json['login_pass'])){
    header('Location: edit_pass.php?error=mismatch');
}else{
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/html5reset-1.6.1.css">
    <link rel="stylesheet" href="../css/same_style.css">
    <link rel="stylesheet" href="../css/edit_pass.css">
    <title>password 変更2</title>
</head>
<body>
    <div>
    <h1 class="topbar">パスワード変更</h1>
    <a href="profile.php"><img class="yajirusi" src="../system_img/yajirusi.png" alt="戻る"></a>
    </div>
    <div class="main">
    <form action="edit_pass_process2.php" method="POST">
        <input type="hidden" name="csrf_token" value="<?=$csrf_token?>">
        <div>
        <label>新しいパスワード</label>
        <input class="textbox" type="password" name="password">
        </div>
        <div>
        <input class="button1" type="submit">
        </div>
    </form>
    </div>
</body>
</html>

<?php
}
?>