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



$content=h($_GET['content']);

$filename='../account/'.$_SESSION['login_user'].'/service_'.$content.'.json';
if ( ($fp = fopen($filename, 'rb')) !== FALSE ){
    $json_str = fread($fp, filesize($filename));
    $json = json_decode($json_str, TRUE);
    $service=$json['service'];
    $price=$json['price'];
    $cycle_unit=$json['cycle_unit'];
    $registration_month=$json['registration_month'];
    $registration_day=$json['registration_day'];
    $memo=$json['memo'];

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
    <link rel="stylesheet" href="../css/content_change.css">
    <title>Document</title>
</head>
<body>
    <div class="topbar">
        <h1>詳細</h1>
        <a href="home.php"><img class="yajirusi" src="../system_img/yajirusi.png" alt="戻る"></a>
    </div>    
    
    <div class="main">
        <form action="content_change_process.php" method="POST">
                <input type="hidden" name="csrf_token" value="<?=$csrf_token?>">
                <input type="hidden" name="content" value="<?=$content?>">
                <div>
                    <label>サービス名</label>
                        <input class="textbox" type="text" name="service" value="<?=$service?>" required>
                </div>
                <div>
                <label>料金</label>
                    <input class="textbox" type="number" name="price" value="<?=$price?>" required>
                </div>
                <div>
                <label>頻度</label>
                    <!--input type="text" name="cycle" placeholder="1" required-->
                    <select class="selectbox" name="cycle_unit"><!--
                        <option value="day">日</option>
                        <option value="week">週</option>-->
                        <?php
                        if($cycle_unit=='month'){
                        ?>
                        <option value="month" selected>月</option>
                        <option value="year">年</option>
                        <?php
                        }else{
                        ?>
                        <option value="month">月</option>
                        <option value="year" selected>年</option>
                        <?php
                        }
                        ?>
                    </select>
                </div>
                <p class="text annotation">月払いの場合は月の入力は要りません。</p>
                <div>
                
                <label>決算日</label>
                    <div>
                
                    <select class="selectbox" name="registration_month">
                            <?php
                            $month_count = 1;
                            for  (; ; ){
                                if($month_count<13){
                                    if($registration_month==$month_count){
                                        echo '<option value="'.$month_count.'" selected>'.$month_count.'月</option>';
                                        $month_count++;
                                    }else{
                                        echo '<option value="'.$month_count.'">'.$month_count.'月</option>';
                                        $month_count++;
                                    }
                                }else{
                                    break;
                                }
                            }
                            ?>
                    </select>

                    <select class="selectbox" name="registration_day">
                        <?php
                        $day_count = 1;
                        for  (; ; ){
                            if($day_count<32){
                                if($registration_day==$day_count){
                                    echo '<option value="'.$day_count.'" selected>'.$day_count.'日</option>';
                                    $day_count++;
                                }else{
                                    echo '<option value="'.$day_count.'">'.$day_count.'日</option>';
                                    $day_count++;
                                }
                            }else{
                                break;
                            }
                        }
                        ?>
                    
                    </select>
                    </div>
                </div>
                <div>
                <label>メモ</label>
                <textarea class="textarea" name="memo"  placeholder="メモ"><?=$memo?></textarea>
                </div>

                <input class="update" value="更新" type="submit">
                
        </form>
    </div>
    <a href="content_delete.php?content=<?=$content?>"><img class="trash" src="../system_img/trash.png" alt="削除"></a>
</body>
</html>