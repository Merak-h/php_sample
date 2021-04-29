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


  $service = h($_POST['service']);
  $price = h($_POST['price']);
  $price = mb_convert_kana($price,'n','UTF-8');
  $cycle = h($_POST['cycle']);
  //$cycle = mb_convert_kana($cycle,'n','UTF-8');
  $cycle_unit = h($_POST['cycle_unit']);
  $registration_month = h($_POST['registration_month']);
  $registration_day = h($_POST['registration_day']);
  $memo = h($_POST['memo']);


//Json形式に変換
  $json = ['service' => $service,
        'price' => $price,
        'cycle_unit' => $cycle_unit,
        //'cycle' => $cycle,
        'registration_month' => $registration_month,
        'registration_day' => $registration_day,
        'memo' => $memo,
        ];
  $json_str = json_encode($json);
  $filename='../account/'.$_SESSION['login_user'].'/service_'.$service.'.json';
if (file_exists($filename)) {
    //ファイルが存在する
    header('Location: add_content.php?error=ture');
    exit;
} else {
    //ファイルga存在しない
    if ( ($fp = fopen($filename, 'wb')) !== FALSE ) {
        fwrite($fp, $json_str);
        fclose($fp);
      }
}
header('Location: home.php');

