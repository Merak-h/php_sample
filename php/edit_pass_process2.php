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



$password = h($_POST['password']);


//ファイルの呼び出し
$filename='../account/'.$login_id.'/user.json';
if ( ($fp = fopen($filename, 'rb')) !== FALSE ){
    $json_str = fread($fp, filesize($filename));
    $json = json_decode($json_str, TRUE);

    $first_name=$json['first_name'];
    $last_name=$json['last_name'];
    $login_id=$json['login_id'];
    $nickname=$json['nickname'];
    $saved_image_file_path=$json['user_icon'];
    fclose($fp);
}

//登録作業
$json = ['first_name' => $first_name,
         'last_name' => $last_name,
         'login_id' => $login_id,
         'login_pass' => password_hash($password, PASSWORD_DEFAULT),
         'nickname' => $nickname,
         'user_icon' => $saved_image_file_path];

$json_str = json_encode($json);
//ファイルに書き出し
if ( ($fp = fopen($filename, 'wb')) !== FALSE ) {
  fwrite($fp, $json_str);
  fclose($fp);
}
header('Location: profile.php');
    exit;