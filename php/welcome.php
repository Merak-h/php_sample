<?php
session_start();
if (!isset($_SESSION['login_user'])){
    header('Location: login.php');
}


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


$login_id = $_SESSION['login_user'];
$nickname=h($_POST['nickname']);
if($nickname ==''){
  $nickname = null;
}
$image_file = $_FILES['icon'];
//ファイルの有無の判定
if(!isset($_FILES['icon'])){
  $saved_image_file_path = null;
}else{
  // アップロードされた画像を保存する
  $saved_image_file_path = '../account/'.$login_id.'/'.$image_file['name'];
  move_uploaded_file(
    $image_file['tmp_name'],
    $saved_image_file_path
  );
}

//jsonを呼ぶ
$filename='../account/'.$login_id.'/user.json';
if ( ($fp = fopen($filename, 'rb')) !== FALSE ) {
    $json_str = fread($fp, filesize($filename));
    $json = json_decode($json_str, TRUE);
  

//jsonに変換
$json = array("first_name" => $json['first_name'],
            "last_name" => $json['last_name'],
            "login_id" => $json['login_id'],
            "login_pass" => $json['login_pass'],
            "nickname" => $nickname,
            "user_icon" => $saved_image_file_path);
$json_str = json_encode($json);
//特定のjsonフォルダを呼ぶ方法
if ( ($fp = fopen('../account/'.$login_id.'/user.json', 'wb')) !== FALSE ) {
    fwrite($fp, $json_str);
    fclose($fp);
  }}
//追記の方法
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/html5reset-1.6.1.css">
    <link rel="stylesheet" href="../css/same_style.css">
    <link rel="stylesheet" href="../css/index.css">
    <title>welcome</title>
</head>
<body>
<h1>さあ、始めよう！</h1>
<div class="main">
  <div>
    <a class="button1" href="home.php">始める</a>
  </div>
</div>
</body>
</html>