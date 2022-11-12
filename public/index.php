<?php

session_start();
require_once '../classes/UserLogic.php';
require_once '../dbconnect.php';
require_once '../functions.php';

date_default_timezone_set("Asia/Tokyo");

$post_array = array();
$pdo = connect();
$err_messages = [];

//ログインしているか判定
$result = UserLogic::checkLogin();

if ($result) {
    $login_user = $_SESSION['login_user'];
}

//フォームを打ち込んだとき
if (!empty($_POST['odai'])) {
    //ログインしているか判定し，していなかったら投稿できない
    if (!$result) {
        $_SESSION['post_err'] = 'ユーザを登録してログインしてください';
        header('Location: index.php');
        return;
    }
    //投稿が空の場合
    if (empty($_POST['odai'])) {
        $err_messages['odai'] = "記入されていません";
    } else {
        try {
            $stmt = $pdo->prepare("INSERT INTO `odais` (`odai`, `user_id`, `post_date`) VALUES (:odai, :user_id, :post_date)");
            $stmt->bindParam(':odai', $_POST['odai'], PDO::PARAM_STR);
            $stmt->bindParam(':user_id', $_POST['user_id'], PDO::PARAM_STR);
            $stmt->bindParam(':post_date', $_POST['post_date'], PDO::PARAM_STR);

            $stmt->execute();

            header('Location: http://localhost:80/oogiri-app/public/index.php');
            exit;
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }
}

$sql = "SELECT id, odai, user_id, post_date FROM `odais`";
$post_array = $pdo->query($sql);

?>
<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/index/main.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>
    <title>大喜利</title>
</head>

<body>

    <header>
        <div class="header-top">
            <div class="header-top-content">
                <a class="header-top-content-home">
                    <div class="header-top-content-home-icon"></div>
                </a>
                <a href="mypage.php" class="header-top-content-account">
                    <div class="header-top-content-account-icon"></div>
                </a>
                <div class="header-top-content-item">
                    <div class="header-top-content-item-icon"></div>
                    <div class="header-top-content-item-subField"></div>
                    <div class="header-top-content-item-field">
                        <div class="header-top-content-item-field-title">カテゴリー</div>
                        <ul>
                            <li>動物</li>
                            <li>スポーツ</li>
                            <li>仕事</li>
                            <li>学校</li>
                            <li>食べ物</li>
                            <li>旅行</li>
                            <li>恋愛</li>
                            <li>仕事</li>
                            <li>その他</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <div class="header-bottom">
            <div class="header-bottom-content">
                <a href="signup_form.php" class="header-bottom-register">
                    <div class="header-bottom-content-register-icon"></div>
                </a>
                <a href="odai.php" class="header-bottom-content-post">
                    <div class="header-bottom-content-post"></div>
                </a>
            </div>
        </div>
    </header>
    <!-- 投稿を表示 -->
    <main>
        <?php if (isset($_SESSION['post_err'])) : ?>
            <p><?php echo $_SESSION['post_err']; ?></p>
        <?php endif; ?>

        <!-- 投稿フォーム -->
        <form method="POST">
            <?php if (isset($err_messages['odai'])) : ?>
                <p><?php echo $err_messages['odai']; ?></p>
            <?php endif; ?>
            <textarea placeholder="お題を記入．．．" name="odai"></textarea>
            <input type="submit" value="投稿" name="submitButton">
            <input type="hidden" name="user_id" value="<?php echo $login_user['id'] ?>">
            <input type="hidden" name="post_date" value="<?php echo date("Y-m-d H:i:s") ?>">
        </form>

        <div class="main-content">
            <div class="main-content-content">
                <div class="main-content-content-name">
                    <div class="main-content-content-name-border"></div>
                    <div class="main-content-content-name-text">
                        <div class="main-content-content-name-text-orange">
                            <div class="main-content-content-name-text-orange-text">新</div>
                        </div>
                        <div class="main-content-content-name-text-white">
                            <div class="main-content-content-name-text-white-text">着</div>
                        </div>
                        <div class="main-content-content-name-text-white">
                            <div class="main-content-content-name-text-white-text">順</div>
                        </div>
                    </div>
                </div>
                <div class="main-content-content-posts">
                    <div class="main-content-content-posts-area">
                        <?php foreach ($post_array as $post) :
                            $users = get_odai_posted_user($post['user_id']);
                        ?>
                            <div class="main-content-content-posts-area-post">
                                <div class="main-content-content-posts-area-post-content">
                                    <div class="main-content-content-posts-area-post-content-text"><?php echo $post['odai'] ?></div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>

            <div class="main-content-content">
                <div class="main-content-content-name">
                    <div class="main-content-content-name-border"></div>
                    <div class="main-content-content-name-text">
                        <div class="main-content-content-name-text-orange">
                            <div class="main-content-content-name-text-orange-text">注</div>
                        </div>
                        <div class="main-content-content-name-text-white">
                            <div class="main-content-content-name-text-white-text">目</div>
                        </div>
                        <div class="main-content-content-name-text-white">
                            <div class="main-content-content-name-text-white-text">の</div>
                        </div>
                        <div class="main-content-content-name-text-white">
                            <div class="main-content-content-name-text-white-text">お</div>
                        </div>
                        <div class="main-content-content-name-text-white">
                            <div class="main-content-content-name-text-white-text">題</div>
                        </div>
                    </div>
                </div>
                <div class="main-content-content-posts">
                    <div class="main-content-content-posts-area">
                        <div class="main-content-content-posts-area-post">
                            <div class="main-content-content-posts-area-post-content">
                                <div class="main-content-content-posts-area-post-content-text">あああああああああああ</div>
                            </div>
                        </div>
                        <div class="main-content-content-posts-area-post">
                            <div class="main-content-content-posts-area-post-content">
                                <div class="main-content-content-posts-area-post-content-text">あああああああああああ</div>
                            </div>
                        </div>
                        <div class="main-content-content-posts-area-post">
                            <div class="main-content-content-posts-area-post-content">
                                <div class="main-content-content-posts-area-post-content-text">あああああああああああ</div>
                            </div>
                        </div>
                        <div class="main-content-content-posts-area-post">
                            <div class="main-content-content-posts-area-post-content">
                                <div class="main-content-content-posts-area-post-content-text">あああああああああああ</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <script src="../js/index/index.js"></script>
</body>

</html>