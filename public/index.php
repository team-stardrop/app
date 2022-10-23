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
  <link rel="stylesheet" href="../css/index/main.css">
  <title>大喜利</title>
</head>
<body>

<header>
        <div class="header-top">
            <div class="header-top-content">
                <a class="header-top-content-home"></a>
                <a href="mypage.php" class="header-top-content-account"></a>
                <a href="item.php" class="header-top-content-item"></a>
            </div>
        </div>
        <div class="header-bottom">
            <div class="header-bottom-content">
                <a href="signup_form.php" class="header-bottom-register"></a>
                <a href="odai.php" class="header-bottom-content-post"></a>
            </div>
        </div>
    </header>
    <main>
        <div class="main-content">
            <div class="main-content-content">
                <div class="main-content-content-name">
                    <div class="main-content-content-name-text">AAA</div>
                </div>
                <div class="main-content-content-posts">
                    <div class="main-content-content-posts-area">
                        <div class="main-content-content-posts-area-post">
                            <div class="main-content-content-posts-area-post-content">
                                <div class="main-content-content-posts-area-post-content-text">あああああああああああ</div>
                            </div>
                            <div class="main-content-content-posts-area-post-meta">
                                <div class="main-content-content-posts-area-post-meta-name">
                                    <div class="main-content-content-posts-area-post-meta-name-text">名前</div>
                                </div>
                                <div class="main-content-content-posts-area-post-meta-data">
                                    <div class="main-content-content-posts-area-post-meta-data-text">2022-01-01</div>
                                </div>
                            </div>
                        </div>
                        <div class="main-content-content-posts-area-post">
                            <div class="main-content-content-posts-area-post-content">
                                <div class="main-content-content-posts-area-post-content-text">あああああああああああ</div>
                            </div>
                            <div class="main-content-content-posts-area-post-meta">
                                <div class="main-content-content-posts-area-post-meta-name">
                                    <div class="main-content-content-posts-area-post-meta-name-text">名前</div>
                                </div>
                                <div class="main-content-content-posts-area-post-meta-data">
                                    <div class="main-content-content-posts-area-post-meta-data-text">2022-01-01</div>
                                </div>
                            </div>
                        </div>
                        <div class="main-content-content-posts-area-post">
                            <div class="main-content-content-posts-area-post-content">
                                <div class="main-content-content-posts-area-post-content-text">あああああああああああ</div>
                            </div>
                            <div class="main-content-content-posts-area-post-meta">
                                <div class="main-content-content-posts-area-post-meta-name">
                                    <div class="main-content-content-posts-area-post-meta-name-text">名前</div>
                                </div>
                                <div class="main-content-content-posts-area-post-meta-data">
                                    <div class="main-content-content-posts-area-post-meta-data-text">2022-01-01</div>
                                </div>
                            </div>
                        </div>
                        <div class="main-content-content-posts-area-post">
                            <div class="main-content-content-posts-area-post-content">
                                <div class="main-content-content-posts-area-post-content-text">あああああああああああ</div>
                            </div>
                            <div class="main-content-content-posts-area-post-meta">
                                <div class="main-content-content-posts-area-post-meta-name">
                                    <div class="main-content-content-posts-area-post-meta-name-text">名前</div>
                                </div>
                                <div class="main-content-content-posts-area-post-meta-data">
                                    <div class="main-content-content-posts-area-post-meta-data-text">2022-01-01</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="main-content-content">
                <div class="main-content-content-name">
                    <div class="main-content-content-name-text">AAA</div>
                </div>
                <div class="main-content-content-posts">
                    <div class="main-content-content-posts-area">
                        <div class="main-content-content-posts-area-post">
                            <div class="main-content-content-posts-area-post-content">
                                <div class="main-content-content-posts-area-post-content-text">あああああああああああ</div>
                            </div>
                            <div class="main-content-content-posts-area-post-meta">
                                <div class="main-content-content-posts-area-post-meta-name">
                                    <div class="main-content-content-posts-area-post-meta-name-text">名前</div>
                                </div>
                                <div class="main-content-content-posts-area-post-meta-data">
                                    <div class="main-content-content-posts-area-post-meta-data-text">2022-01-01</div>
                                </div>
                            </div>
                        </div>
                        <div class="main-content-content-posts-area-post">
                            <div class="main-content-content-posts-area-post-content">
                                <div class="main-content-content-posts-area-post-content-text">あああああああああああ</div>
                            </div>
                            <div class="main-content-content-posts-area-post-meta">
                                <div class="main-content-content-posts-area-post-meta-name">
                                    <div class="main-content-content-posts-area-post-meta-name-text">名前</div>
                                </div>
                                <div class="main-content-content-posts-area-post-meta-data">
                                    <div class="main-content-content-posts-area-post-meta-data-text">2022-01-01</div>
                                </div>
                            </div>
                        </div>
                        <div class="main-content-content-posts-area-post">
                            <div class="main-content-content-posts-area-post-content">
                                <div class="main-content-content-posts-area-post-content-text">あああああああああああ</div>
                            </div>
                            <div class="main-content-content-posts-area-post-meta">
                                <div class="main-content-content-posts-area-post-meta-name">
                                    <div class="main-content-content-posts-area-post-meta-name-text">名前</div>
                                </div>
                                <div class="main-content-content-posts-area-post-meta-data">
                                    <div class="main-content-content-posts-area-post-meta-data-text">2022-01-01</div>
                                </div>
                            </div>
                        </div>
                        <div class="main-content-content-posts-area-post">
                            <div class="main-content-content-posts-area-post-content">
                                <div class="main-content-content-posts-area-post-content-text">あああああああああああ</div>
                            </div>
                            <div class="main-content-content-posts-area-post-meta">
                                <div class="main-content-content-posts-area-post-meta-name">
                                    <div class="main-content-content-posts-area-post-meta-name-text">名前</div>
                                </div>
                                <div class="main-content-content-posts-area-post-meta-data">
                                    <div class="main-content-content-posts-area-post-meta-data-text">2022-01-01</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
</body>
</html>