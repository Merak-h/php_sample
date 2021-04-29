<?php
session_start();
if (!isset($_SESSION['login_user'])){
    header('Location: login.php');
}


//クロスサイトスクリプティング
function h($s) {return htmlspecialchars($s, ENT_QUOTES, "UTF-8");}

// 先に保存したトークンと送信されたトークンが一致するか確認します
$csrf_token=h($_POST["csrf_token"]);
$method=h($_GET['method']);
if($method!='skip'){
    if (isset($csrf_token) 
    && $csrf_token === $_SESSION['csrf_token']) {
    //後続処理

    } else {
        header("HTTP/1.1 400 Not Found");
        include ('400.php');
        exit;
    }
}



 // 暗号学的的に安全なランダムなバイナリを生成し、それを16進数に変換することでASCII文字列に変換します
 $toke_byte = openssl_random_pseudo_bytes(16);
 $csrf_token = bin2hex($toke_byte);
 // 生成したトークンをセッションに保存します
 $_SESSION['csrf_token'] = $csrf_token;


$nickname=h($_POST['nickname']);
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/html5reset-1.6.1.css">
    <link rel="stylesheet" href="../css/same_style.css">
    <link rel="stylesheet" href="../css/setup2.css">
    <title>set up</title>
</head>
<body>
<h1>次にアイコンを作ろう！</h1>
<form action="welcome.php" method="POST">
    <input type="hidden" name="csrf_token" value="<?=$csrf_token?>">
    <input type="hidden" name="nickname" value="<?=$nickname?>">
    <input class="button1 skiplink" type="submit" value="スキップする">
</form>
<div class="main">
<form action="welcome.php" method="POST" enctype="multipart/form-data">
    <input type="hidden" name="csrf_token" value="<?=$csrf_token?>">
    <input type="hidden" name="nickname" value="<?=$nickname?>">
    <div class="icon-area">
        <div class="icon">
                <img class="icon_img" src="../system_img/profileicon.png" alt="user icon">
                <img class="icon_img2"id="preview" src="data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw==" style="max-width:200px;">
                <label class="icon-mask2">
                写真を選択
                <input type="file" name="icon" accept='image/*' onchange="previewImage(this);">
                </label>
        </div>
    </div>
    <div>
    <input class="button1 buttonerea" type="submit" value="決定">
    </div>
</form>
</div>
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