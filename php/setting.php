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


//jsonを呼ぶ
$filename='../account/'.$_SESSION['login_user'].'/user.json';
if ( ($fp = fopen($filename, 'rb')) !== FALSE ) {
    $json_str = fread($fp, filesize($filename));
    $json = json_decode($json_str, TRUE);
    $user_id=$json['login_id'];
    $nickname=$json['nickname'];
    $first_name=$json['first_name'];
    $last_name=$json['last_name'];
    $imgpass=$json['user_icon'];

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
    <link rel="stylesheet" href="../css/setting.css">
    <title>Edit Profile</title>
</head>
<body>
<div class="back">
    <a href="profile.php"><img class="yajirusi" src="../system_img/yajirusi.png" alt="戻る"></a>
</div>
<form action="setting_process.php" method="POST" enctype="multipart/form-data">
<input type="hidden" name="csrf_token" value="<?=$csrf_token?>">
<div class="icon-area">
    <div class="icon">
            <img class="icon_img" src="<?=$profile?>" alt="user icon">
            <img class="icon_img2"id="preview" src="data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw==" style="max-width:200px;">
            <label class="icon-mask2">
            <p class="text">写真を選択</p>
            <input type="file" name="icon" accept='image/*' onchange="previewImage(this);">
            </label>
    </div>
</div>

<div class="main">
<?php
    $errer = h($_GET['errer']);
    if($errer == 'duplicate'){
        echo '<p class="text">このIDは既に使用されています。</p>';
    }
?>
<div>
    <label>ID</label>
    <input class="textbox" type="text" name="login_id" value="<?=$user_id?>">
</div>
<div>
    <label>ニックネーム</label>
    <input class="textbox" type="text" name="nickname" value="<?=$nickname?>">
</div>
<div>
    <label>First Name</label>
    <input class="textbox" type="text" name="first_name" value="<?=$first_name?>">
</div>
<div>
    <label>Last Name</label>
    <input class="textbox" type="text" name="last_name" value="<?=$last_name?>">
</div>
<div>
    <input class="button1" type="submit" value="更新">
</div>
</div>
</form>




    
<script>
function previewImage(obj)
{
	var fileReader = new FileReader();
	fileReader.onload = (function() {
		document.getElementById('preview').src = fileReader.result;
	});
	fileReader.readAsDataURL(obj.files[0]);
}

</script>
</body>
</html>
<?php
