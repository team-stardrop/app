<?php
session_start();

require_once '../classes/UserLogic.php';

$result = UserLogic::checkLogin();
if($result) {
  header('Location: mypage.php');
  return;
}

$err = $_SESSION;

//セッションを消す
$_SESSION = array();
session_destroy();

?>
<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="../css/login_form/main.css">
  <title>ログイン画面</title>
</head>
<body>
    <div class="taggle-button-box">
        <a href="index.php" class="home">
            <div class="home-icon"></div>
        </a>
    </div>
    <div class="login-form">
        <div class="login-form-title-box">
            <h2 class="login-form-title">ログインフォーム</h2>
            <!-- エラーメッセージ -->
            <?php if (isset($err['msg'])) : ?>
                <p><?php echo $err['msg']; ?></p>
            <?php endif; ?>
        </div>
        <!-- 登録フォーム -->
        <div class="container-login">
            <form action="login.php" method="POST">
                <div class="input-field-group">
                    <div class="input-field">
                        <!--<label for="username">メールアドレス：</label>-->
                        <input type="email" name="email" placeholder=メールアドレス>
                    </div>
                    <?php if (isset($err['email'])) : ?>
                        <p class="error-message"><?php echo $err['email']; ?></p>
                    <?php endif; ?>
                    <div class="input-field-group">
                        <div class="input-field">
                        <!--<label for="password">パスワード：</label>-->
                        <input type="password" name="password" placeholder=パスワード>
                    </div>
                    <?php if (isset($err['password'])) : ?>
                        <p class="error-message"><?php echo $err['password']; ?></p>
                    <?php endif; ?>
                </div>
                <input type="submit" value="ログイン" class="submit">
            </form>
        </div>
        <a href="signup_form.php" class="login">新規登録はこちら</a>
    </div>
</body>
</html>