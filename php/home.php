<?php
  session_start();
  if (!isset($_SESSION['login_user'])) {
    header('Location: login.php');
    exit;
}
?>
<?php
//アカウント情報
//jsonを呼ぶ
$filename='../account/'.$_SESSION['login_user'].'/user.json';
if ( ($fp = fopen($filename, 'rb')) !== FALSE ) {
    $json_str = fread($fp, filesize($filename));
    $json = json_decode($json_str, TRUE);
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
$year=date('Y');
$month=date('n');
$day=date('j');
?>


<!--　-----------------------------ここからhtml---------------------------------------- -->
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/html5reset-1.6.1.css">
    <link rel="stylesheet" href="../css/same_style.css">
    <link rel="stylesheet" href="../css/home.css">
    <title>home</title>
</head>

<body>

    <div class="top_bar">
      <h1>SUBSC!</h1>
      <div class="iconarea">
        <a href="profile.php">
          <div class="icon">
                  <img class="icon_img" src="<?=$profile?>" alt="user icon">
                  <div class="icon-mask"></div>
          </div>
          <p class="iconname"><?=$user_name?></p>
        </a>
      </div>
    </div>
            <?php
              //コンテンツjsonファイルからリストを取得し、多次元配列へ変換して保存。
              $all_contents_array=[];
              foreach (glob('../account/'.$_SESSION['login_user'].'/service_*.json') as $filename) {
                if ( ($fp = fopen($filename, 'rb')) !== FALSE ) {
                  $json_str = fread($fp, filesize($filename));
                  fclose($fp);
                  $json = json_decode($json_str, TRUE);

                  $contents_array=array(
                    'service'=>$json['service'],
                    'price'=>$json['price'],
                    'cycle_unit'=>$json['cycle_unit'],
                    //'cycle'=>$json['cycle'],
                    'registration_month'=>$json['registration_month'],
                    'registration_day'=>$json['registration_day'],
                    'memo'=>$json['memo'],
                );
                array_push($all_contents_array,$contents_array);
                  
                }}
                //echo$all_contents_array[0]['price'];
            ?>
              
          

    <div class="content-area">
      <div class="remind">
      <?php
        //支払日が今日か
        $repet=0;
        
        while ($repet<count($all_contents_array,)){

          $remind ='<div><p>今日は'.$all_contents_array[$repet]['service'].'('.$all_contents_array[$repet]['price'].'円)'.'の支払日です。</div>';

          if($all_contents_array[$repet]['cycle_unit']=='month'){
            if($month==1||$month==3||$month==5||$month==7||$month==8||$month==10||$month==12){
              if($all_contents_array[$repet]['registration_day']==$day){
                echo $remind;
              }
            }elseif($month!=2){
              if($all_contents_array[$repet]['registration_day']<=30){
                if($all_contents_array[$repet]['registration_day']==$day){
                  echo $remind;
                }
              }elseif($day==30){
                echo $remind;
              }
            }elseif($all_contents_array[$repet]['registration_day']<=28){
              if($all_contents_array[$repet]['registration_day']==$day){
                echo $remind;
              }
            }elseif(date('L')){
              if($day==29){
                echo $remind;
              }
            }elseif($day==28){
              echo $remind;
            }
          }elseif($all_contents_array[$repet]['registration_month']==2&&$all_contents_array[$repet]['registration_day']==29){
            if(date('L')){
              if($all_contents_array[$repet]['registration_month']==$month&&$all_contents_array[$repet]['registration_day']==$day){
                echo $remind;
              }
            }elseif($month==2&&$day==28){
              echo $remind;
            }
          }elseif($all_contents_array[$repet]['registration_month']==$month&&$all_contents_array[$repet]['registration_day']==$day){
            echo $remind;
          }
          $repet+=1;
        }?>
    </div>
    
    <div class="monthlysum">
        <h2>今月の合計支払い金額</h2>
        <?php
          //合計金額の計算
          $repet=0;
          $total_fee=0;
          while ($repet<count($all_contents_array,)){
            if($all_contents_array[$repet]['cycle_unit']=='month'){
              $total_fee=$total_fee+$all_contents_array[$repet]['price'];
            }elseif($month==$all_contents_array[$repet]['registration_month']){
              $total_fee=$total_fee+$all_contents_array[$repet]['price'];
            }
            $repet+=1;
        }
        ?>
        <div>
          <p class="caution_text"><?=number_format($total_fee)?></p><p>円</p>
        </div>
    </div>

  

    <div class="contents">
        <div class="detale">
          <h3>頻度</h3>
          <h3>次回支払日</h3>
          <h3>価格</h3>
        </div>
                  <?php 
                      
                      $repet=0;
                      while ($repet<count($all_contents_array,)){
                  
                    
                    //頻度を表示
                    switch($all_contents_array[$repet]['cycle_unit']){
                      case 'month':
                        $paysycle= '毎月';
                        break;
                      case 'year':
                        $paysycle= '毎年';
                        break;
                    }
                  
                    //次の支払日
                    switch($all_contents_array[$repet]['cycle_unit']){
                      case 'month':
                        if($day<=$all_contents_array[$repet]['registration_day']){
                          $date= $month.'/'.$all_contents_array[$repet]['registration_day'];
                        }elseif($month==12){
                          $date= '1/'.$all_contents_array[$repet]['registration_day'];
                        }else{
                          $monthi=$month+1;
                          $date= $monthi.'/'.$all_contents_array[$repet]['registration_day'];
                        }

                        break;
                      case 'year':
                        $yeari=$year+1;
                        if($all_contents_array[$repet]['registration_month']<$month){
                          
                          $date= $yeari.'.'.$all_contents_array[$repet]['registration_month'].'/'.$all_contents_array[$repet]['registration_day'];
                        }elseif($all_contents_array[$repet]['registration_month']>$month){
                          $date= $year.'.'.$all_contents_array[$repet]['registration_month'].'/'.$all_contents_array[$repet]['registration_day'];
                        }elseif($day<=$all_contents_array[$repet]['registration_day']){
                          $date= $year.'.'.$all_contents_array[$repet]['registration_month'].'/'.$all_contents_array[$repet]['registration_day'];
                        }else{
                          $date= $yeari.'.'.$all_contents_array[$repet]['registration_month'].'/'.$all_contents_array[$repet]['registration_day'];
                        }
                        break;
                    }
                  ?>
      <div class="object">
        <div class="objecttop">
          <h3><?=$all_contents_array[$repet]['service']?></h3>
          <p class="text"><a class="more" href="content_change.php?content=<?=$all_contents_array[$repet]['service']?>">詳細</a></p>
        </div>
        <div class="detale">
          <p class="text"><?=$paysycle?></p>
          <p class="text"><?=$date?></p>
          <p class="text"><?=number_format($all_contents_array[$repet]['price'])?>円</p>
        </div>
      </div>
          
                  <?php
                        $repet+=1;
                    }
                  ?>
 
    </div>

    <div class="add-content">
      <a href="add_content.php"><img src="../system_img/add_content.png" alt="add content"></a>
    </div>
</body>
</html>