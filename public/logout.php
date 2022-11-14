<?php
session_start();
require_once '../classes/UserLogic.php';

if(!$logout = filter_input(INPUT_POST, 'logout')){
  exit('不正なリクエストです');
}

//ログインしているか判定し，セッションが切れていたらログインを促す
$result = UserLogic::checkLogin();

if(!$result) {
  exit('セッションが切れたので，ログインし直してください');
}

//ログアウトする
UserLogic::logout();
?>
<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="../css/logout/main.css">
  <title>ログアウト</title>
</head>
<body>
    <div class="login-form">
        <div class="message-box">
            <h2 class="login-form-title">ログアウト完了</h2>
            <a href="./index.php" class="go-home">ホームへ</a>
        </div>
    </div>
</body>
</html>