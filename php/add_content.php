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



$error = h($_GET['error']);
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/html5reset-1.6.1.css">
    <link rel="stylesheet" href="../css/same_style.css">
    <link rel="stylesheet" href="../css/add_content.css">
    <title>add content</title>
</head>
<body>
    <div class="topbar">
    <h1>登録する</h1>
    <a href="home.php"><img class="yajirusi" src="../system_img/yajirusi.png" alt="戻る"></a>
    </div>
    <div class="main">
        <?php
        if($error=='ture'){
            echo '<p class="text">この名前のサービスは既に登録されています。詳細にて変更もしくは違う名前で登録してください。</p>';
        }
        ?>
    
    <form action="add_content_process.php" method="POST">
    <input type="hidden" name="csrf_token" value="<?=$csrf_token?>">
    <div>
        <label>サービス名</label>
            <input class="textbox" type="text" name="service" placeholder="サービス" required>
    </div>
    <div>
        <label>料金</label>
            <input class="textbox" type="number" name="price" placeholder="1,400" required>
    </div>
    <div>
        <label>頻度</label>
            <!--input type="text" name="cycle" placeholder="1" required-->
            <select class="selectbox" name="cycle_unit"><!--
                <option value="day">日</option>
                <option value="week">週</option>-->
                <option value="month" selected>月</option>
                <option value="year">年</option>
            </select>
    </div>
     <p class="text annotation">月払いの場合は月の入力は要りません。</p>
    <div>
        
        
        <label>決算日</label>
       
            <div>
        
            <select class="selectbox" name="registration_month">
                    <?php
                    $today_month=date("m");
                    $month_count = 1;
                    for  (; ; ){
                        if($month_count<13){
                            if($today_month==$month_count){
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
                $today_day = date("d");
                $day_count = 1;
                for  (; ; ){
                    if($day_count<32){
                        if($today_day==$day_count){
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
            <textarea class="textarea" name="memo" placeholder="メモ"></textarea>
    </div>
    <div>
            <input class="button1" type="submit" value="登録">

    </div>
    </form>
    </div>
</body>
</html>