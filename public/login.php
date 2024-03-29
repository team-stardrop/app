<?php
session_start();

require_once '../classes/UserLogic.php';
ini_set('display_errors', true);

//エラーメッセージ
$err = [];

//バリデーション
if(!$email = filter_input(INPUT_POST, 'email')) {
  $err['email'] = 'メールアドレスを記入してください';
}

if(!$password = filter_input(INPUT_POST, 'password')) {
  $err['password'] = 'パスワードを記入してください';
}

if (count($err) > 0) {
  // エラーがあった場合は戻す
  $_SESSION = $err;
  header('Location: login-form.php');
  return;
}
//ログイン成功時
$result = UserLogic::login($email, $password);
//ログイン失敗時の処理
if(!$result) {
  header('Location: login.php');
  return;
}

$_SESSION['post_err'] = [];

?>
<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="../css/login/main.css">
  <title>ログイン完了画面</title>
</head>
<body>
    <div class="login-form">
        <div class="message-box">
            <h2 class="login-form-title">ログイン完了</h2>
            <a href="./index.php" class="go-home">ホーム</a>
            <a href="./mypage-home.php" class="go-mypage">マイページへ</a>
        </div>
    </div>
</body>
</html>