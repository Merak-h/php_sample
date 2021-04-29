<?php
  session_start();
  if (!isset($_SESSION['login_user'])) {
    header('Location: login.php');
    exit;
}
//クロスサイトスクリプティング
function h($s) {return htmlspecialchars($s, ENT_QUOTES, "UTF-8");}
$content=h($_GET['content']);

$filename='../account/'.$_SESSION['login_user'].'/service_'.$content.'.json';

//ファイルを削除する
if (unlink($filename)){
    header('Location: home.php');
  }else{
    echo $file.'の削除に失敗しました。';
  }
  ?>
  