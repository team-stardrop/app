<?php
session_start();
require_once '../functions.php';
require_once '../classes/UserLogic.php';

// ログインしているか判定
$result = UserLogic::checkLogin();

if($result) {
  header('Location: mypage.php');
  return;
}

$login_err = isset($_SESSION['login_err']) ? $_SESSION['login_err'] : null; //三項演算子
unset($_SESSION['login_err']);
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ユーザ登録画面</title>
</head>
<body>
<h2>ユーザ登録フォーム</h2>
<?php if (isset($login_err)) : ?>
  <p><?php echo $login_err; ?></p>
<?php endif; ?>
  <form action="register.php" method="POST">
    <p>
      <label for="username">ユーザ名：</label>
      <input type="text" name="username">
    </p>
    <p>
      <label for="username">メールアドレス：</label>
      <input type="email" name="email">
    </p>
    <p>
      <label for="password_">パスワード：</label>
      <input type="password" name="password">
    </p>
    <p>
      <label for="username">パスワード確認：</label>
      <input type="password" name="password_conf">
    </p>
    <input type="hidden" name="csrf_token" value="<?php echo h(setToken()); ?>">
    <p>
      <input type="submit" value="新規登録">
    </p>
  </form>
  <a href="login_form.php">ログインはこちら</a>
  <a href="index.php">戻る</a>
</body>
</html>