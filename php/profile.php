<?php
  session_start();
  if (!isset($_SESSION['login_user'])) {
    header('Location: login.php');
    exit;
}
//jsonを呼ぶ
$filename='../account/'.$_SESSION['login_user'].'/user.json';
if ( ($fp = fopen($filename, 'rb')) !== FALSE ) {
    $json_str = fread($fp, filesize($filename));
    $json = json_decode($json_str, TRUE);
    $user_id=$json['login_id'];
    $imgpass=$json['user_icon'];
    //user nameを設定（nicknameもしくはfirst_name）
    if($json['nickname']==''){
      $user_name = $json['first_name'];
    }else{
      $user_name = $json['nickname'];
    }
    //iconの有無を確認
    if(file_exists($imgpass) && exif_imagetype($imgpass)){
        $profile= $imgpass;
      }else{
        $profile='../system_img/profileicon.png';
      }
fclose($fp);
}
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/html5reset-1.6.1.css">
    <link rel="stylesheet" href="../css/same_style.css">
    <link rel="stylesheet" href="../css/profile.css">
    <title>profile</title>
</head>
<body>
<div class="back">
    <a href="home.php"><img class="yajirusi" src="../system_img/yajirusi.png" alt="戻る"></a>
</div>

<div class="icon-area">
    <a href="setting.php">
        <div class="icon">
            <img class="icon_img" src="<?=$profile?>" alt="user icon">
            <div class="icon-mask"><p class="text">変更する</p></div>
        </div>
    </a>
</div>


<div class="username">
    <h1><?=$user_name?>さん</h1>
    <p>ID: <?=$user_id?></p>
</div>
<ul class="list">
    <li>
        <a class="text2" href="setting.php">アカウント設定 ></a>
    </li>
    <li>
        <a class="text2" href="edit_pass.php">パスワード変更 ></a>
    </li>
    <li>
        <a class="text2" href="info.php">このアプリについて></a>
    </li>
</ul>
<div class="link">
    <a class="text" href="logout.php">ログアウト</a>
    <a class="text" href="delete_acount.php">アカウントを削除</a>
</div>
</body>
</html>