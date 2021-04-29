<?php
  session_start();
  if (!isset($_SESSION['login_user'])) {
    header('Location: login.php');
    exit;
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


$login_id = $_SESSION['login_user'];
//POSTの引数の引き継ぎ
$first_name=h($_POST['first_name']);
$last_name=h($_POST['last_name']);
$newlogin_id=h($_POST['login_id']);
$nickname=h($_POST['nickname']);

//重複確認
$newfilename='../account/'.$newlogin_id.'/user.json';
if($login_id!=$newlogin_id){
  if (file_exists($newfilename)) {
    header('Location: setting.php?errer=duplicate&login_id='.$newlogin_id);
    exit;
  }
}



$image_file = $_FILES['icon'];
//ファイルの有無の判定
if(isset($_FILES['icon'])){
  // アップロードされた画像を保存する
  $new_saved_image_file_path = '../account/'.$login_id.'/'.$image_file['name'];
  move_uploaded_file(
    $image_file['tmp_name'],
    $new_saved_image_file_path
  );
}

//ファイルの呼び出し/import/削除
$filename='../account/'.$login_id.'/user.json';
if ( ($fp = fopen($filename, 'rb')) !== FALSE ){
    $json_str = fread($fp, filesize($filename));
  if ( ($fp = fopen($filename, 'wb')) !== FALSE ) {
    $json = json_decode($json_str, TRUE);
    $pass=$json['login_pass'];
    $saved_image_file_path=$json['user_icon'];
    ftruncate($fp, 0);

    fclose($fp);
}
  
}
//画像の差し替え
if(file_exists($new_saved_image_file_path) && exif_imagetype($new_saved_image_file_path)){
  $saved_image_file_path= $new_saved_image_file_path;
}

//ID差し替えによる所変更
if($login_id!=$newlogin_id){
  //ディレクトリの名前変更
  $newdirectoryname='../account/'.$newlogin_id;
  $directoryname='../account/'.$login_id;
  rename($directoryname,$newdirectoryname);
  //画像の名前抽出
  $img_name=str_replace('../account/'.$login_id.'/','',$saved_image_file_path);
  //login_idの変更
  $login_id=$newlogin_id;
  //画像パスの変更
  $saved_image_file_path='../account/'.$login_id.'/'.$img_name;
  //セッションのlogin_userの変更
  $_SESSION['login_user'] = $login_id;
  //$filenameの変更
  $filename='../account/'.$login_id.'/user.json';

}


//登録作業
$json = ['first_name' => $first_name,
         'last_name' => $last_name,
         'login_id' => $login_id,
         'login_pass' => $pass,
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

?>