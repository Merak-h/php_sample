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



$content=h($_POST['content']);
$service=h($_POST['service']);
$price=h($_POST['price']);
$cycle_unit=h($_POST['cycle_unit']);
$registration_month=h($_POST['registration_month']);
$registration_day=h($_POST['registration_day']);
$memo=h($_POST['memo']);

//Json形式に変換
$json = ['service' => $service,
        'price' => $price,
        'cycle_unit' => $cycle_unit,
        'registration_month' => $registration_month,
        'registration_day' => $registration_day,
        'memo' => $memo,
];
$json_str = json_encode($json);

$filename='../account/'.$_SESSION['login_user'].'/service_'.$content.'.json';
$filenamenew='../account/'.$_SESSION['login_user'].'/service_'.$service.'.json';


if (unlink($filename)){
    if ( ($fp = fopen($filenamenew, 'wb')) !== FALSE ){
        fwrite($fp,$json_str);
        fclose($fp);
        header('Location: home.php');
    }else{
        echo '変更に失敗しました。';
    }
  }else{
    echo '削除に失敗しました。';
  }


