<?php 

session_start();
require_once '../classes/UserLogic.php';

//ログインしているか判定
$result = UserLogic::checkLogin();

if($result) {
  $login_user = $_SESSION['login_user'];
}

?>
<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>大喜利</title>
</head>
<body>

  <a href="mypage.php">マイページへ</a>
  <a href="signup_form.php">登録はこちら</a>
  <a href="login_form.php">ログインはこちら</a>
  <a href="item.php">項目</a>
  <a href="odai.php">投稿です</a>

</body>
</html>