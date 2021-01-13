<?php
session_start();
header('X-FRAME-OPTIONS: SAMEORIGIN');
$mode = 'input';
$errmessage = array();
if(isset($_POST['back']) && $_POST['back']){

}elseif(isset($_POST['confirm']) && $_POST['confirm']){
  if(!$_POST['name']){
    $errmessage[] = '名前を入力してください。';
  }elseif(!preg_match("/([\x{3005}\x{3007}\x{303b}\x{3400}-\x{9FFF}\x{F900}-\x{FAFF}\x{20000}-\x{2FFFF}])(.*|)/u", $_POST['name'])){
    $errmessage[] = '名前は漢字で入力してください。';
  }elseif(mb_strlen($_POST['name']) > 100){
    $errmessage[] = '名前は100文字以内にしてください。';
  }
  $_SESSION['name'] = htmlspecialchars($_POST['name'], ENT_QUOTES);

  if(!$_POST['sub-name']){
    $errmessage[] = 'ふりがなを入力してください。';
  }elseif(!preg_match("/^([　 \t\r\n]|[ぁ-ん]|[ー])+$/u", $_POST['sub-name'])){
    $errmessage[] = 'ふりがなはひらがなで入力してください。';
  }elseif(mb_strlen($_POST['sub-name']) > 100){
    $errmessage[] = 'ふりがなは100文字以内にしてください。';
  }
  $_SESSION['sub-name'] = htmlspecialchars($_POST['sub-name'], ENT_QUOTES);

  if(!isset($_POST['sex'])){
    $errmessage[] = '性別を選択してください。';
  }
  $_SESSION['sex'] = $_POST['sex'];

  if(!$_POST['year'] || !$_POST['month'] || !$_POST['day']){
    $errmessage[] = '生年月日を入力してください。';
  }elseif(!preg_match("/^[0-9]+$/", $_POST['year']) || !preg_match("/^[0-9]+$/", $_POST['month']) || !preg_match("/^[0-9]+$/", $_POST['day'])){
    $errmessage[] = '生年月日は半角数字のみで入力してください。';
  }elseif($_POST['year'] < 1900 || $_POST['year'] > 2020 || $_POST['month'] < 1 || $_POST['month'] > 12 || $_POST['day'] < 1 || $_POST['day'] > 31){
    $errmessage[] = '正しい生年月日を入力してください。';
  }
  $_SESSION['year']  = htmlspecialchars($_POST['year'], ENT_QUOTES);
  $_SESSION['month'] = htmlspecialchars($_POST['month'], ENT_QUOTES);
  $_SESSION['day']   = htmlspecialchars($_POST['day'], ENT_QUOTES);

  $mail = str_replace(PHP_EOL, '', $_POST['email']);
  $mail2 = str_replace(PHP_EOL, '', $_POST['confirm-email']);
  if(!$_POST['email'] || !$_POST['confirm-email']){
    $errmessage[] = 'Eメールを入力してください。';
  }elseif($_POST['email'] !== $_POST['confirm-email']){
    $errmessage[] = 'Eメール（確認用）がEメールと一致しません。';
  }elseif(!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL) || !filter_var($_POST['confirm-email'], FILTER_VALIDATE_EMAIL)){
    $errmessage[] = 'メールアドレスが不正です。';
  }elseif(mb_strlen($_POST['email']) > 200 || mb_strlen($_POST['confirm-email']) > 200){
    $errmessage[] = 'Eメールは200文字以内にしてください。';
  }
  $_SESSION['email'] = htmlspecialchars($mail, ENT_QUOTES);
  $_SESSION['confirm-email'] = htmlspecialchars($mail2, ENT_QUOTES);

  $tel = str_replace(array('-', 'ー', '−', '―', '‐'), '', $_POST['tel']);
  $tel2 = str_replace(array('-', 'ー', '−', '―', '‐'), '', $_POST['confirm-tel']);
  if(!$_POST['tel'] || !$_POST['confirm-tel']){
    $errmessage[] = '電話番号を入力してください。';
  }elseif($_POST['tel'] !== $_POST['confirm-tel']){
    $errmessage[] = '電話番号（確認用）が電話番号と一致しません。';
  }elseif(!preg_match("/^0\d{9,10}$/", $tel) || !preg_match("/^0\d{9,10}$/", $tel2)){
    $errmessage[] = '正しい電話番号を入力してください。';
  }
  $_SESSION['tel'] = htmlspecialchars($_POST['tel'], ENT_QUOTES);
  $_SESSION['confirm-tel'] = htmlspecialchars($_POST['confirm-tel'], ENT_QUOTES);

  if(!$_POST['post'] || !$_POST['pref01'] || !$_POST['addr01']){
    $errmessage[] = '住所を入力してください。';
  }elseif(!preg_match("/^(([0-9]{3}-[0-9]{4})|([0-9]{7}))$/", $_POST['post'])){
    $errmessage[] = '正しい住所を入力してください。';
  }elseif(mb_strlen($_POST['pref01']) > 4){
    $errmessage[] = '都道府県は、4文字以内で入力してください。';
  }elseif(mb_strlen($_POST['addr01']) > 200){
    $errmessage[] = '市区町村番地は、200文字以内で入力してください。';
  }elseif(mb_strlen($_POST['addr02']) > 200){
    $errmessage[] = 'マンション/ビル名は、200文字以内で入力してください。';
  }
  $_SESSION['post']   = htmlspecialchars($_POST['post'], ENT_QUOTES);
  $_SESSION['pref01'] = htmlspecialchars($_POST['pref01'], ENT_QUOTES);
  $_SESSION['addr01'] = htmlspecialchars($_POST['addr01'], ENT_QUOTES);
  $_SESSION['addr02'] = htmlspecialchars($_POST['addr02'], ENT_QUOTES);

  if(!$_POST['message']){
    $errmessage[] = 'お問い合わせ内容を入力してください。';
  }elseif(mb_strlen($_POST['message']) > 500){
    $errmessage[] = 'お問い合わせ内容は500文字以内にしてください。';
  }
  $_SESSION['message'] = htmlspecialchars($_POST['message'], ENT_QUOTES);

  if(isset($_POST['question']) && is_array($_POST['question'])) {
    $_SESSION['question'] = implode("/", $_POST['question']);
  }else{
    $_SESSION['question'] = '';
  }

  if(!isset($_POST['agree'])){
    $errmessage[] = '個人情報の取扱いに同意がなければ、お問い合わせできません。';
    $_SESSION['agree'] = false;
  }else {
    $_SESSION['agree'] = true;
  }

  if($errmessage){
    $mode = 'input';
  }else{
    $token = bin2hex(random_bytes(32));
    $_SESSION['token'] = $token;
    $mode = 'confirm';
  }
}elseif(isset($_POST['send']) && $_POST['send']){
  if(!$_POST['token'] || !$_SESSION['token'] || !$_SESSION['email']){
    $errmessage[] = '不正な処理が実行されました。';
    $_SESSION     = array();
    $mode         = 'input';
  }elseif($_POST['token'] !== $_SESSION['token']){
    $errmessage[] = '不正な処理が実行されました。';
    $_SESSION     = array();
    $mode         = 'input';
  }else{
    $message = "お問い合わせを受け付けました。\r\n"
             . "[名前] " . $_SESSION['name'] . "\r\n"
             . "[ふりがな] " . $_SESSION['sub-name'] . "\r\n"
             . "[性別] " . $_SESSION['sex'] . "\r\n"
             . "[生年月日] " . $_SESSION['year'] . "年" . $_SESSION['month'] . "月" . $_SESSION['day'] . "日" . "\r\n"
             . "[email] " . $_SESSION['email'] . "\r\n"
             . "[電話番号] " . $_SESSION['tel'] . "\r\n"
             . "[住所] 〒" . $_SESSION['post'] . "\r\n"
             . $_SESSION['pref01'] . $_SESSION['addr01'] . $_SESSION['addr02'] . "\r\n"
             . "[お問い合わせ内容]" . "\r\n"
             . preg_replace("/\r\n|\r|\n/", "\r\n", $_SESSION['message']) . "\r\n"
             . "[当サービスを知った理由]" . "\r\n"
             . $_SESSION['question'];
    mail($_SESSION['email'], 'お問い合わせありがとうございます。', $message);
    mail('nijirou.0310@gmail.com', 'お問い合わせありがとうございます。', $message);
    $_SESSION = array();
    $mode = 'send';
  }
}else{
  $_SESSION['name']          = '';
  $_SESSION['sub-name']      = '';
  $_SESSION['sex']           = '';
  $_SESSION['year']          = '';
  $_SESSION['month']         = '';
  $_SESSION['day']           = '';
  $_SESSION['email']         = '';
  $_SESSION['confirm-email'] = '';
  $_SESSION['tel']           = '';
  $_SESSION['confirm-tel']   = '';
  $_SESSION['post']          = '';
  $_SESSION['pref01']        = '';
  $_SESSION['addr01']        = '';
  $_SESSION['addr02']        = '';
  $_SESSION['message']       = '';
  $_SESSION['question']      = '';
  $_SESSION['agree']         = false;
}
?>
<!DOCTYPE html>
<html lang="ja">
<head>

  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,minimum-scale=1,initial-scale=1">
  <meta name="format-detection" content="telephone=no">
  <title>お問い合わせフォーム</title>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
  <script src="https://ajaxzip3.github.io/ajaxzip3.js" charset="UTF-8"></script>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
  <script src="jquery.ah-placeholder.js"></script>
  <style>
  html, body, h1, h2, h6, p, div{
    margin:0;
    padding:0;
  }

  *{
    box-sizing:border-box;
  }

  html{
    font-size:75%;
  }

  body{
    margin:50px auto 75px auto;
    padding:0 10px;
    max-width:600px;
    font-family:'Hiragino Kaku Gothic ProN W3', sans-serif;
    font-size:1.6em;
    letter-spacing:0.05em;
  }

  h1{
    text-align:center;
    margin-bottom:60px;
    font-weight:normal;
    font-size:2.4rem;
  }

  .alert{
    position:relative;
    bottom:50px;
  }

  .error{
    color:rgb(245, 50, 50);
  }

  .label{
    display:block;
    margin-bottom:30px;
    height:42px;
  }

  .badge{
    position:relative;
    bottom:2px;
    margin-left:5px;
  }

  .input{
    position:absolute;
    left:50%;
    padding:5px 10px;
    width:290px;
  }

  input::placeholder{
    color:rgb(150, 150, 150);
  }

  input:-ms-input-placeholder{
    color:rgb(150, 150, 150);
  }

  input::-ms-input-placeholder{
    color:rgb(150, 150, 150);
  }

  .sex-wrapper{
    margin-bottom:30px;
  }

  .sex{
    display:inline-block;
    position:absolute;
    left:50%;
  }

  .radio{
    position:relative;
    top:2px;
    margin-right:10px;
    width:18px;
    height:18px;
  }

  .age-wrapper{
    margin-bottom:30px;
    height:42px;
  }

  .age{
    display:inline-block;
    position:absolute;
    right:calc(50% - 290px);
  }

  .age-input{
    padding:5px 10px;
    width:80px;
  }

  .no-spin::-webkit-inner-spin-button,
  .no-spin::-webkit-outer-spin-button{
    -webkit-appearance: none;
    margin:0;
    -moz-appearance:textfield;
  }

  h6{
    font-size:1.2rem;
  }

  .message-label{
    display:block;
    margin-bottom:60px;
  }

  textarea{
    width:100%;
    height:200px;
  }

  h2{
    text-align:center;
    margin-bottom:15px;
    font-weight:normal;
    font-size:2rem;
  }

  p{
    text-align:center;
    margin-bottom:30px;
  }

  .question-wrapper{
    display:flex;
    flex-wrap:wrap;
    margin-bottom:60px;
  }

  .question{
    margin-bottom:10px;
    border-radius:10px;
    padding:10px 20px;
    width:48%;
    background-color:rgb(220, 220, 220);
  }

  .question:nth-child(2n+1){
    margin-right:2%;
  }

  .question:nth-child(2n){
    margin-left:2%;
  }

  .checkbox{
    position:relative;
    top:2px;
    margin-right:10px;
    width:18px;
    height:18px;
  }

  .agree-box{
    margin-bottom:30px;
    border:1px solid gray;
    border-radius:5px;
    padding:10px;
    height:200px;
    overflow:scroll;
  }

  .confirm{
    margin-bottom:30px;
  }

  .btn-wrapper{
    text-align:center;
    margin-top:60px;
  }

  .btn{
    padding:7px 16px;
    font-size:2rem;
  }

  .btn-secondary{
    margin-right:50px;
  }

  @media screen and (max-width:670px){
    html{
      font-size:62.5%;
    }

    .label{
      height:auto;
    }

    .input{
      position:static;
      width:100%;
    }

    .sex{
      display:block;
      position:static;
    }

    .age-wrapper{
      height:auto;
    }

    .age{
      display:block;
      position:static;
    }

    .age-input{
      width:70px;
    }

    textarea{
      height:130px;
    }

    .question{
      margin-bottom:5px;
      width:100%;
    }

    .question:nth-child(2n+1){
      margin-right:0;
    }

    .question:nth-child(2n){
      margin-left:0;
    }

    .agree-box{
      height:130px;
    }
  }
  <?php
  if(in_array('名前を入力してください。', $errmessage) || in_array('名前は漢字で入力してください。', $errmessage) || in_array('名前は100文字以内にしてください。', $errmessage)){
    echo '.name-input{border:1px solid rgb(245, 50, 50); border-radius:3px; background-color:#f8d7da;}';
  }
  if(in_array('ふりがなを入力してください。', $errmessage) || in_array('ふりがなはひらがなで入力してください。', $errmessage) || in_array('ふりがなは100文字以内にしてください。', $errmessage)){
    echo '.sub-name-input{border:1px solid rgb(245, 50, 50); border-radius:3px; background-color:#f8d7da;}';
  }
  if(in_array('生年月日を入力してください。', $errmessage) || in_array('生年月日は半角数字のみで入力してください。', $errmessage) || in_array('正しい生年月日を入力してください。', $errmessage)){
    echo '.age-input{border:1px solid rgb(245, 50, 50); border-radius:3px; background-color:#f8d7da;}';
  }
  if(in_array('Eメールを入力してください。', $errmessage) || in_array('Eメール（確認用）がEメールと一致しません。', $errmessage) || in_array('メールアドレスが不正です。', $errmessage) || in_array('Eメールは200文字以内にしてください。', $errmessage)){
    echo '.email-input{border:1px solid rgb(245, 50, 50); border-radius:3px; background-color:#f8d7da;}';
  }
  if(in_array('電話番号を入力してください。', $errmessage) || in_array('電話番号（確認用）が電話番号と一致しません。', $errmessage) || in_array('正しい電話番号を入力してください。', $errmessage)){
    echo '.tel-input{border:1px solid rgb(245, 50, 50); border-radius:3px; background-color:#f8d7da;}';
  }
  if(in_array('住所を入力してください。', $errmessage)){
    echo '.address-input{border:1px solid rgb(245, 50, 50); border-radius:3px; background-color:#f8d7da;}';
  }elseif(in_array('正しい住所を入力してください。', $errmessage)){
    echo '.address-input[name="post"]{border:1px solid rgb(245, 50, 50); border-radius:3px; background-color:#f8d7da;}';
  }elseif(in_array('都道府県は、4文字以内で入力してください。', $errmessage)){
    echo '.address-input[name="pref01"]{border:1px solid rgb(245, 50, 50); border-radius:3px; background-color:#f8d7da;}';
  }elseif(in_array('市区町村番地は、200文字以内で入力してください。', $errmessage)){
    echo '.address-input[name="addr01"]{border:1px solid rgb(245, 50, 50); border-radius:3px; background-color:#f8d7da;}';
  }elseif(in_array('マンション/ビル名は、200文字以内で入力してください。', $errmessage)){
    echo '.address-input[name="addr02"]{border:1px solid rgb(245, 50, 50); border-radius:3px; background-color:#f8d7da;}';
  }
  if(in_array('お問い合わせ内容を入力してください。', $errmessage) || in_array('お問い合わせ内容は500文字以内にしてください。', $errmessage)){
    echo 'textarea{border:1px solid rgb(245, 50, 50); border-radius:3px; background-color:#f8d7da;}';
  }
  ?>
  </style>

</head>
<body>

  <?php if($mode === 'input'){ ?>
  <form action="./index.php" method="post">

    <h1>お問い合わせフォーム</h1>

    <?php
      if($errmessage){
        echo '<div class="alert alert-danger" role="alert">入力に誤りがあるか、未入力になっています。下記について再度ご確認のうえ、修正してください。</div>';
      }
    ?>

    <?php
    if(isset($_POST['confirm']) && $_POST['confirm']){
      if(!$_POST['name']){
        echo '<div class="error">名前を入力してください。</div>';
      }elseif(!preg_match("/([\x{3005}\x{3007}\x{303b}\x{3400}-\x{9FFF}\x{F900}-\x{FAFF}\x{20000}-\x{2FFFF}])(.*|)/u", $_POST['name'])){
        echo '<div class="error">名前は漢字で入力してください。</div>';
      }elseif(mb_strlen($_POST['name']) > 100){
        echo '<div class="error">名前は100文字以内にしてください。</div>';
      }
    }
    ?>
    <label class="label">
      名前<span class="badge badge-primary">必須</span>
      <input type="text" name="name" value="<?php echo $_SESSION['name'] ?>" placeholder="山田 太郎" class="input name-input">
    </label>

    <?php
    if(isset($_POST['confirm']) && $_POST['confirm']){
      if(!$_POST['sub-name']){
        echo '<div class="error">ふりがなを入力してください。</div>';
      }elseif(!preg_match("/^([　 \t\r\n]|[ぁ-ん]|[ー])+$/u", $_POST['sub-name'])){
        echo '<div class="error">ふりがなはひらがなで入力してください。</div>';
      }elseif(mb_strlen($_POST['sub-name']) > 100){
        echo '<div class="error">ふりがなは100文字以内にしてください。</div>';
      }
    }
    ?>
    <label class="label">
      ふりがな<span class="badge badge-primary">必須</span>
      <input type="text" name="sub-name" value="<?php echo $_SESSION['sub-name'] ?>" placeholder="やまだ たろう" class="input sub-name-input">
    </label>

    <?php
    if(isset($_POST['confirm']) && $_POST['confirm']){
      if(!isset($_POST['sex'])){
        echo '<div class="error">性別を選択してください。</div>';
      }
    }
    ?>
    <div class="sex-wrapper">
      性別<span class="badge badge-primary">必須</span>
      <div class="sex">
        <label>男<input type="radio" name="sex" value="男" <?php if($_SESSION['sex'] === '男'){ ?> checked="checked" <?php } ?> class="radio"></label>
        <label>女<input type="radio" name="sex" value="女" <?php if($_SESSION['sex'] === '女'){ ?> checked="checked" <?php } ?> class="radio"></label>
        <label>その他<input type="radio" name="sex" value="その他" <?php if($_SESSION['sex'] === 'その他'){ ?> checked="checked" <?php } ?> class="radio"></label>
      </div>
    </div>

    <?php
    if(isset($_POST['confirm']) && $_POST['confirm']){
      if(!$_POST['year'] || !$_POST['month'] || !$_POST['day']){
        echo '<div class="error">生年月日を入力してください。</div>';
      }elseif(!preg_match("/^[0-9]+$/", $_POST['year']) || !preg_match("/^[0-9]+$/", $_POST['month']) || !preg_match("/^[0-9]+$/", $_POST['day'])){
        echo '<div class="error">生年月日は半角数字のみで入力してください。</div>';
      }elseif($_POST['year'] < 1900 || $_POST['year'] > 2020 || $_POST['month'] < 1 || $_POST['month'] > 12 || $_POST['day'] < 1 || $_POST['day'] > 31){
        echo '<div class="error">正しい生年月日を入力してください。</div>';
      }
    }
    ?>
    <div class="age-wrapper">
      生年月日<span class="badge badge-primary">必須</span>
      <div class="age">
        <label><input type="number" name="year" value="<?php echo $_SESSION['year'] ?>" placeholder="2000" class="no-spin age-input">年</label>
        <label><input type="number" name="month" value="<?php echo $_SESSION['month'] ?>" placeholder="1" class="no-spin age-input">月</label>
        <label><input type="number" name="day" value="<?php echo $_SESSION['day'] ?>" placeholder="23" class="no-spin age-input">日</label>
      </div>
      <h6>※半角数字のみ</h6>
    </div>

    <?php
    if(isset($_POST['confirm']) && $_POST['confirm']){
      if(!$_POST['email'] || !$_POST['confirm-email']){
        echo '<div class="error">Eメールを入力してください。</div>';
      }elseif($_POST['email'] !== $_POST['confirm-email']){
        echo '<div class="error">Eメール（確認用）がEメールと一致しません。</div>';
      }elseif(!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL) || !filter_var($_POST['confirm-email'], FILTER_VALIDATE_EMAIL)){
        echo '<div class="error">メールアドレスが不正です。</div>';
      }elseif(mb_strlen($_POST['email']) > 200 || mb_strlen($_POST['confirm-email']) > 200){
        echo '<div class="error">Eメールは200文字以内にしてください。</div>';
      }
    }
    ?>
    <label class="label">
      Eメール<span class="badge badge-primary">必須</span>
      <input type="email" name="email" value="<?php echo $_SESSION['email'] ?>" placeholder="example.0123@icloud.com" class="input email-input">
    </label>

    <label class="label">
      Eメール（確認用）<span class="badge badge-primary">必須</span>
      <input type="email" name="confirm-email" value="<?php echo $_SESSION['confirm-email'] ?>" placeholder="example.0123@icloud.com" class="input email-input">
    </label>

    <?php
    if(isset($_POST['confirm']) && $_POST['confirm']){
      if(!$_POST['tel'] || !$_POST['confirm-tel']){
        echo '<div class="error">電話番号を入力してください。</div>';
      }elseif($_POST['tel'] !== $_POST['confirm-tel']){
        echo '<div class="error">電話番号（確認用）が電話番号と一致しません。</div>';
      }elseif(!preg_match("/^0\d{9,10}$/", $tel) || !preg_match("/^0\d{9,10}$/", $tel2)){
        echo '<div class="error">正しい電話番号を入力してください。</div>';
      }
    }
    ?>
    <label class="label">
      電話番号<span class="badge badge-primary">必須</span>
      <input type="tel" name="tel" value="<?php echo $_SESSION['tel'] ?>" placeholder="012-3456-7890" class="input tel-input">
    </label>

    <label class="label">
      電話番号（確認用）<span class="badge badge-primary">必須</span>
      <input type="tel" name="confirm-tel" value="<?php echo $_SESSION['confirm-tel'] ?>" placeholder="012-3456-7890" class="input tel-input">
    </label>

    <?php
    if(isset($_POST['confirm']) && $_POST['confirm']){
      if(!$_POST['post'] || !$_POST['pref01'] || !$_POST['addr01']){
        echo '<div class="error">住所を入力してください。</div>';
      }elseif(!preg_match("/^(([0-9]{3}-[0-9]{4})|([0-9]{7}))$/", $_POST['post'])){
        echo '<div class="error">正しい住所を入力してください。</div>';
      }elseif(mb_strlen($_POST['pref01']) > 4){
        echo '<div class="error">都道府県は、4文字以内で入力してください。</div>';
      }elseif(mb_strlen($_POST['addr01']) > 200){
        echo '<div class="error">市区町村番地は、200文字以内で入力してください。</div>';
      }elseif(mb_strlen($_POST['addr02']) > 200){
        echo '<div class="error">マンション/ビル名は、200文字以内で入力してください。</div>';
      }
    }
    ?>
    住所
    <label class="label">
      〒<span class="badge badge-primary">必須</span>
      <input type="text" name="post" value="<?php echo $_SESSION['post'] ?>" placeholder="123-4567" onKeyUp="AjaxZip3.zip2addr(this,'','pref01','addr01');" class="input address-input">
    </label>

    <label class="label">
      都道府県<span class="badge badge-primary">必須</span>
      <input type="text" name="pref01" value="<?php echo $_SESSION['pref01'] ?>" placeholder="東京都" class="input address-input">
    </label>

    <label class="label">
      市区町村番地<span class="badge badge-primary">必須</span>
      <input type="text" name="addr01" value="<?php echo $_SESSION['addr01'] ?>" placeholder="山田市南山田1-2-3" class="input address-input">
    </label>

    <label class="label">
      マンション/ビル名
      <input type="text" name="addr02" value="<?php echo $_SESSION['addr02'] ?>" placeholder="アパート山田101" class="input address-input">
    </label>

    <?php
    if(isset($_POST['confirm']) && $_POST['confirm']){
      if(!$_POST['message']){
        echo '<div class="error">お問い合わせ内容を入力してください。</div>';
      }elseif(mb_strlen($_POST['message']) > 500){
        echo '<div class="error">お問い合わせ内容は500文字以内にしてください。</div>';
      }
    }
    ?>
    <label class="message-label">
      お問い合わせ内容<span class="badge badge-primary">必須</span>
      <textarea name="message"><?php echo $_SESSION['message'] ?></textarea>
    </label>

    <h2>簡単なアンケートにご協力ください。</h2>
    <p>Q. 当サービスはどのように知りましたか？ 該当するものにチェックを入れてください。（複数選択可）</p>
    <div class="question-wrapper">
      <label class="question"><input type="checkbox" name="question[]" value="ホームページ" <?php if(strpos($_SESSION['question'], 'ホームページ') !== false){ ?> checked="checked" <?php } ?> class="checkbox">ホームページ</label>
      <label class="question"><input type="checkbox" name="question[]" value="知人の紹介" <?php if(strpos($_SESSION['question'], '知人の紹介') !== false){ ?> checked="checked" <?php } ?> class="checkbox">知人の紹介</label>
      <label class="question"><input type="checkbox" name="question[]" value="Twitter" <?php if(strpos($_SESSION['question'], 'Twitter') !== false){ ?> checked="checked" <?php } ?> class="checkbox">Twitter</label>
      <label class="question"><input type="checkbox" name="question[]" value="Instagram" <?php if(strpos($_SESSION['question'], 'Instagram') !== false){ ?> checked="checked" <?php } ?> class="checkbox">Instagram</label>
      <label class="question"><input type="checkbox" name="question[]" value="FaceBook" <?php if(strpos($_SESSION['question'], 'FaceBook') !== false){ ?> checked="checked" <?php } ?> class="checkbox">FaceBook</label>
      <label class="question"><input type="checkbox" name="question[]" value="その他" <?php if(strpos($_SESSION['question'], 'その他') !== false){ ?> checked="checked" <?php } ?> class="checkbox">その他</label>
    </div>

    <h2>個人情報の取り扱いについて</h2>
    <p>下記事項をご確認の上、同意していただける場合は[同意する]にチェックを入れてください。</p>
    <div class="agree-box"><<ここに個人情報保護方針が入ります。>> <<ここに個人情報保護方針が入ります。>> <<ここに個人情報保護方針が入ります。>> <<ここに個人情報保護方針が入ります。>> <<ここに個人情報保護方針が入ります。>> <<ここに個人情報保護方針が入ります。>>
    <<ここに個人情報保護方針が入ります。>> <<ここに個人情報保護方針が入ります。>> <<ここに個人情報保護方針が入ります。>> <<ここに個人情報保護方針が入ります。>> <<ここに個人情報保護方針が入ります。>> <<ここに個人情報保護方針が入ります。>>
    <<ここに個人情報保護方針が入ります。>> <<ここに個人情報保護方針が入ります。>> <<ここに個人情報保護方針が入ります。>> <<ここに個人情報保護方針が入ります。>> <<ここに個人情報保護方針が入ります。>> <<ここに個人情報保護方針が入ります。>></div>
    <?php
    if(isset($_POST['confirm']) && $_POST['confirm']){
      if(!isset($_POST['agree'])){
        echo '<div class="error">個人情報の取扱いに同意がなければ、お問い合わせできません。</div>';
      }
    }
    ?>
    <label><input type="checkbox" name="agree" value="" <?php if($_SESSION['agree'] === true){ ?> checked="checked" <?php } ?> class="checkbox">個人情報の取扱いに同意する<span class="badge badge-primary">必須</span></label>

    <div class="btn-wrapper"><input type="submit" name="confirm" value="確認" class="btn btn-primary btn-lg" /></div>

  </form>
  <?php }elseif($mode === 'confirm'){ ?>
  <form action="./index.php" method="post">

    <input type="hidden" name="token" value="<?php echo $_SESSION['token']; ?>">
    <h1>入力内容の確認</h1>

    <div class="confirm">[名前] <?php echo $_SESSION['name'] ?></div>
    <div class="confirm">[ふりがな] <?php echo $_SESSION['sub-name'] ?></div>
    <div class="confirm">[性別] <?php echo $_SESSION['sex'] ?></div>
    <div class="confirm">[生年月日] <?php echo $_SESSION['year'] . "年" . $_SESSION['month'] . "月" . $_SESSION['day'] . "日" ?></div>
    <div class="confirm">[Eメール] <?php echo $_SESSION['email'] ?></div>
    <div class="confirm">[電話番号] <?php echo $_SESSION['tel'] ?></div>
    <div class="confirm">
      [住所] 〒<?php echo $_SESSION['post'] ?><br>
      <?php echo $_SESSION['pref01'] . $_SESSION['addr01'] . $_SESSION['addr02'] ?>
    </div>
    <div class="confirm">
      [お問い合わせ内容]<br>
      <?php echo nl2br($_SESSION['message']) ?>
    </div>
    <div class="confirm">
      [当サービスを知った理由]<br>
      <?php echo $_SESSION['question'] ?>
    </div>

    <div class="btn-wrapper">
      <input type="submit" name="back" value="戻る" class="btn btn-secondary btn-lg" />
      <input type="submit" name="send" value="送信" class="btn btn-primary btn-lg" />
    </div>

  </form>
  <?php }else{ ?>
  送信しました。お問い合わせありがとうございました。
  <?php } ?>
  <script>

    $('[placeholder]').ahPlaceholder({
	    placeholderColor : 'silver',
	    placeholderAttr : 'placeholder',
	    likeApple : true
	  });

  </script>

</body>
</html>
