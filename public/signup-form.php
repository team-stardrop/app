<?php
session_start();
require_once '../functions.php';
require_once '../classes/UserLogic.php';

// ログインしているか判定
$result = UserLogic::checkLogin();

if($result) {
  header('Location: mypage-home.php');
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
        <link rel="stylesheet" href="../css/signup_form/main.css">
        <title>ユーザ登録画面</title>
    </head>
    <body>
        <div class="taggle-button-box">
            <a href="index.php" class="home">
                <div class="header-top-content-home-ex">ホーム</div>
                <div class="home-icon1"></div>
                <div class="home-icon2"></div>
            </a>
        </div>
        <div class="signup-form">
            <div class="signup-form-title-box">
                <h2 class="signup-form-title">新規登録</h2>
                <?php if (isset($login_err)) : ?>
                    <p class="signup-prompt"><?php echo $login_err; ?></p>
                <?php endif; ?>
            </div>
            <div class="container-signup">
                <form action="register.php" method="POST">
                    <div class="input-field-group">
                        <div class="input-field">
                            <input type="text" name="username" placeholder=ユーザ名>          
                            <!--<label for="username">ユーザ名：</label>-->
                        </div>
                        <div class="input-field">
                            <input type="email" name="email" placeholder=メールアドレス> 
                            <!--<label for="username">メールアドレス：</label>-->    
                        </div>
                        <div class="input-field">
                            <input type="password" name="password" placeholder=パスワード>
                            <!--<label for="password_">パスワード：</label>-->
                        </div>
                        <div class="input-field">
                            <input type="password" name="password_conf" placeholder=確認用パスワード>
                            <!--<label for="username">パスワード確認：</label>-->
                        </div> 
                        <input type="hidden" name="csrf_token" value="<?php echo h(setToken()); ?>">
                    </div>
                    <input type="submit" value="登録" class="submit">
                </form>
            </div>
            <a href="login-form.php" class="login">ログインはこちら</a>
        </div>
    </body>
</html>