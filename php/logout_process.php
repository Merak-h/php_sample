<?php
session_start();


//クロスサイトスクリプティング
function h($s) {return htmlspecialchars($s, ENT_QUOTES, "UTF-8");}


$roulte = h($_GET['roulte']);
if($roulte=='ture'){
    header('Location: home.php');
}
$_SESSION = array();
session_destroy();

?>

<!DOCTYPE html>
<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/html5reset-1.6.1.css">
    <link rel="stylesheet" href="../css/same_style.css">
<title>ログアウト</title>
</head>
<body>
<div class="bace">
<h1>ログアウトしました。</h1>
<div class="button"><a href='index.php'>log in</a></div>
</div>
</body>
</html>