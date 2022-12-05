<?php
session_start();
require_once '../classes/UserLogic.php';
ini_set('display_errors', true);

//エラーメッセージ
$err = [];

$token = filter_input(INPUT_POST, 'csrf_token');
//トークンがない，もしくは一致しない場合，処理を中止
if (!isset($_SESSION['csrf_token']) || $token !== $_SESSION['csrf_token']) {
  exit('不正なリクエストです');
}

unset($_SESSION['csrf_token']);

//バリデーション
if(!$username = filter_input(INPUT_POST, 'username')) {
  $err[] = 'ユーザ名を記入してください';
}
if(!$email = filter_input(INPUT_POST, 'email')) {
  $err[] = 'メールアドレスを記入してください';
}
$password = filter_input(INPUT_POST, 'password');
//正規表現
if (!preg_match("/\A[a-z\d]{8,100}+\z/i", $password)) {
  $err[] = 'パスワードは英数字8文字以上100文字以下にしてください';
}
$password_conf = filter_input(INPUT_POST, 'password_conf');
if($password !== $password_conf) {
  $err[] = '確認用パスワードと異なっています';
}

$result = UserLogic::check_created_user_account($email);
if($result){
  $err[] = 'このメールアドレスのアカウントはすでに存在しています';
}

if (count($err) === 0) {
  //登録処理
  $hasCreated = UserLogic::createUser($_POST);

  if($hasCreated) {
    UserLogic::login($email, $password);
  } else {
    $err[] = '登録に失敗しました';
  }
}

?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/register/main.css">
    <title>ユーザ登録完了画面</title>
</head>
<body>
    <div class="register-form">
        <div class="message-box">
            <?php if (count($err) > 0) : ?>
                <?php foreach($err as $e) : ?>
                    <p class="message"><?php echo $e ?></p>
                <?php endforeach ?>
                <a href="./signup_form.php" class="return-signup">戻る</a>
            <?php else : ?>
                <p class="message">ユーザ登録が完了しました</p>
                <a href="./index.php" class="go-home">ホーム</a>
                <a href="./mypage-home.php" class="go-mypage">マイページへ</a>
            <?php endif ?>
        </div>
    </div>
</body>
</html>