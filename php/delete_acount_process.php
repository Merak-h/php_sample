<?php
session_start();


//クロスサイトスクリプティング
function h($s) {return htmlspecialchars($s, ENT_QUOTES, "UTF-8");}


$roulte = h($_GET['roulte']);
$directoryname='../account/'.$_SESSION['login_user'];



?>

<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="../css/html5reset-1.6.1.css">
    <link rel="stylesheet" href="../css/base_style.css">
    <link rel="stylesheet" href="../css/logout.css">
<title>アカウント削除</title>
</head>
<body>
<?php
if($roulte=='ture'){
    foreach (new \RecursiveDirectoryIterator($directoryname, \FilesystemIterator::SKIP_DOTS) as $file) {
        unlink($file);
    }
    if (rmdir($directoryname)) {
        ?><h1>アカウントを削除しました。</h1><?php
    }
}
$_SESSION = array();
session_destroy();

?>
<div class="main">
<div class="button"><a href='index.php'>始める</a></div>
</div>
</body>
</html>