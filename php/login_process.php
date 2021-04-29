<?php
  session_start();


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


  $login_id = h($_POST['login_id']);
  $password = h($_POST['login_pass']);

  foreach (glob('../account/'.$login_id.'/user.json') as $filename) {
    if ( ($fp = fopen($filename, 'rb')) !== FALSE ) {
      $json_str = fread($fp, filesize($filename));
      fclose($fp);

      $json = json_decode($json_str, TRUE);
      if ($json['login_id'] == $login_id && password_verify($password, $json['login_pass'])) {
        // ログインできた
        $_SESSION['login_user'] = $login_id;
        break;
      }
    }
  }
if (isset($_SESSION['login_user'])) {
    header('Location: home.php');
    exit;
} else {
    header('Location: login.php?errer=exist&id='.$login_id);
    exit;
} 
?>
