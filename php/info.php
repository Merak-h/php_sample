<?php
  session_start();
  if (!isset($_SESSION['login_user'])) {
    header('Location: login.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/html5reset-1.6.1.css">
    <link rel="stylesheet" href="../css/same_style.css">
    <link rel="stylesheet" href="../css/info.css">
    <title>情報</title>
</head>
<body>
    <div class="topbar">
        <h1>このアプリについて</h1>
        <a href="profile.php"><img class="yajirusi" src="../system_img/yajirusi.png"></a>
    </div>
    <div class="main">
        <h2>アプリの目的</h2>
        <p>このアプリの目的は、私生活で利用しているサブスクリプションサービスの管理と見える化です。</p>
        <h2>使い方</h2>
        <ul>
            <li>
                <h3>サービスの登録</h3>
                <p class="text">右下の＋ボタンから登録できます。</p>
            </li>
            <li>
                <h3>サービスの情報変更</h3>
                <p> class="text"サービスリストの右上にある詳細から変更できます。</p>
            </li>
            <li>
                <h3>ユーザー設定</h3>
                <p class="text">ホーム画面の右上のアイコンからアカウント設定でできます。</p>
            </li>
        </ul>
    </div>

</body>
</html>