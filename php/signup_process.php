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



  $login_id = h($_POST['login_id']);
  $first_name=h($_POST['first_name']);
  $last_name=h($_POST['last_name']);
  if(!isset($first_name)&&!isset($last_name)){
    header('Location: signup.php');
    exit;
  }
  //重複確認
  $filename='../account/'.$login_id.'/user.json';
  if (file_exists($filename)) {
    header('Location: signup2.php?errer=duplicate&login_id='.$login_id.'&first_name='.$first_name.'&last_name='.$last_name);
    exit;
  }
  $password = h($_POST['login_pass']);
  //ディレクトリの作成
  $folda_path = '../account/'.$login_id;
  mkdir($folda_path);



  

//登録作業
  $filename = $folda_path.'/user.json';
  $json = ['first_name' => $first_name,
           'last_name' =>$last_name,
           'login_id' => $login_id,
           'login_pass' => password_hash($password, PASSWORD_DEFAULT)];
  $json_str = json_encode($json);

  if ( ($fp = fopen($filename, 'wb')) !== FALSE ) {
    fwrite($fp, $json_str);
    fclose($fp);
  }

  #login作業
    $_SESSION['login_user'] = $login_id;
    $_SESSION['user_name'] = $first_name;  
 if (isset($_SESSION['login_user'])) {
    header('Location: setup.php');
    exit;
} else {
    header('Location: login.php');
    exit;
} ?>
