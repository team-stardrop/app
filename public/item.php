<?php

session_start();
require_once '../classes/UserLogic.php';
require_once '../dbconnect.php';
require_once '../functions.php';

date_default_timezone_set("Asia/Tokyo");

$item_id = $_GET['item_id'];
$item = get_item_data($_GET['item_id']);

$post_array = array();
$pdo = connect();
$err_messages = [];

//ログインしているか判定
$result = UserLogic::checkLogin();

if ($result) {
    $login_user = $_SESSION['login_user'];
}

//フォームを打ち込んだとき
if (!empty($_POST['submitButton'])) {
    //ログインしているか判定し，していなかったら投稿できない
    if (!$result) {
        $_SESSION['post_err'] = 'ユーザを登録してログインしてください';
        header('Location: index.php');
        return;
    }
    //投稿が空の場合
    if (empty($_POST['odai'])) {
        $err_messages['odai'] = "記入されていません";
    } else if (empty($_POST['post_category'])) {
        $err_messages['category'] = "カテゴリーが選択されていません";
    } else if ($login_user['point']<20) {
        $err_messages['point'] = "お題投稿には20ポイント必要です";
    } else {
        // お題を保存
        try {
            $stmt = $pdo->prepare("INSERT INTO `odais` (`odai`, `user_id`, `post_date` , `item_id`) VALUES (:odai, :user_id, :post_date, :item_id)");
            $stmt->bindParam(':odai', $_POST['odai'], PDO::PARAM_STR);
            $stmt->bindParam(':user_id', $_POST['user_id'], PDO::PARAM_STR);
            $stmt->bindParam(':post_date', $_POST['post_date'], PDO::PARAM_STR);
            $stmt->bindParam(':item_id', $_POST['post_category'], PDO::PARAM_STR);

            $stmt->execute();

            // ポイント処理
            try{
                $login_user['point'] = $login_user['point']-20;
                $_SESSION['login_user'] = $login_user;
                $stmt = $pdo->prepare("UPDATE `users` SET point = :point WHERE id = :user_id");
                $stmt->bindParam(':point', $login_user['point'], PDO::PARAM_STR);
                $stmt->bindParam(':user_id', $login_user['id'], PDO::PARAM_STR);
                
                $stmt->execute();
        
                header('Location: item.php?item_id='.$item_id.'');
                exit;
            } catch (PDOException $e){
                echo $e->getMessage();
            }

            exit;
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }
}

$sql = "SELECT id, odai, user_id, post_date FROM `odais` WHERE item_id = $item_id";
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
    <script src="../script/index/notification.js"></script>
    <title>大喜利</title>
</head>

<body>
    <header>
        <div class="header-top">
            <div class="header-top-content">
                <a href="index.php" class="header-top-content-home">
                    <div class="header-top-content-home-icon"></div>
                </a>
                <a href="mypage-home.php" class="header-top-content-account">
                    <div class="header-top-content-account-icon"></div>
                </a>
                <div class="header-top-content-item">
                    <div class="header-top-content-item-icon"></div>
                    <div class="header-top-content-item-subField"></div>
                    <div class="header-top-content-item-field">
                        <div class="header-top-content-item-field-title">カテゴリー</div>
                        <ul>
                            <li><a href="item.php?item_id=1">動物</a></li>
                            <li><a href="item.php?item_id=2">スポーツ</a></li>
                            <li><a href="item.php?item_id=3">仕事</a></li>
                            <li><a href="item.php?item_id=4">学校</a></li>
                            <li><a href="item.php?item_id=5">食べ物</a></li>
                            <li><a href="item.php?item_id=6">旅行</a></li>
                            <li><a href="item.php?item_id=7">恋愛</a></li>
                            <li><a href="item.php?item_id=8">行事</a></li>
                            <li><a href="item.php?item_id=9">その他</a></li>
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
                <a class="header-bottom-content-post">
                    <div class="header-bottom-content-post"></div>
                </a>
            </div>
        </div>
    </header>
    <!-- 投稿を表示 -->
    <main>
        <!-- エラーメッセージ表示 -->
        <div class="error">
            <?php if (isset($_SESSION['post_err'])) : ?>
                <script>
                    notification("<?php echo $_SESSION['post_err']; ?>");
                </script>
            <?php endif; ?>
            <?php if (isset($err_messages['odai'])) : ?>
                <script>
                    notification("<?php echo $err_messages['odai']; ?>");
                </script>
            <?php endif; ?>
            <?php if (isset($err_messages['category'])) : ?>
                <script>
                    notification("<?php echo $err_messages['category']; ?>");
                </script>
            <?php endif; ?>
            <?php if (isset($err_messages['point'])) : ?>
                <script>
                    notification("<?php echo $err_messages['point']; ?>");
                </script>
            <?php endif; ?>
        </div>
        
        <!-- お題表示 -->
        <div class="main-header">
            <div class="main-header-text">
                <h2><?php echo $item['item_name']; ?></h2>
            </div>
        </div>
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
                        <?php foreach ($post_array as $odai) :
                            $users = get_odai_posted_user($odai['user_id']);
                        ?>
                            <a href="odai.php?odai_id=<?php echo $odai['id']; ?>" class="main-content-content-posts-area-post">
                                <div class="main-content-content-posts-area-post-top">
                                    <div class="main-content-content-posts-area-post-content">
                                        <div class="main-content-content-posts-area-post-content-text"><?php echo $odai['odai'] ?></div>
                                    </div>
                                </div>
                            </a>
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
                        <a href="odai.php" class="main-content-content-posts-area-post">
                            <div class="main-content-content-posts-area-post-top">
                                <div class="main-content-content-posts-area-post-content">
                                    <div class="main-content-content-posts-area-post-content-text">ああああああああああああ</div>
                                </div>
                            </div>
                        </a>
                        <a href="odai.php" class="main-content-content-posts-area-post">
                            <div class="main-content-content-posts-area-post-top">
                                <div class="main-content-content-posts-area-post-content">
                                    <div class="main-content-content-posts-area-post-content-text">ああああああああああああ</div>
                                </div>
                            </div>
                        </a>
                        <a href="odai.php" class="main-content-content-posts-area-post">
                            <div class="main-content-content-posts-area-post-top">
                                <div class="main-content-content-posts-area-post-content">
                                    <div class="main-content-content-posts-area-post-content-text">ああああああああああああ</div>
                                </div>
                            </div>
                        </a>
                        <a href="odai.php" class="main-content-content-posts-area-post">
                            <div class="main-content-content-posts-area-post-top">
                                <div class="main-content-content-posts-area-post-content">
                                    <div class="main-content-content-posts-area-post-content-text">ああああああああああああ</div>
                                </div>
                            </div>
                        </a>
                        <a href="odai.php" class="main-content-content-posts-area-post">
                            <div class="main-content-content-posts-area-post-top">
                                <div class="main-content-content-posts-area-post-content">
                                    <div class="main-content-content-posts-area-post-content-text">ああああああああああああ</div>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <div class="postLayer"></div>
    <!-- 投稿モーダル -->
    <form class="postLayer-content" method="POST">
        <div class="category">
            <ul class="category-content">
                <li>
                    <input type="radio" name="post_category" id="animal" value="1">
                    <label for="animal">動物</label>
                </li>
                <li>
                    <input type="radio" name="post_category" id="sport" value="2">
                    <label for="sport">スポーツ</label>
                </li>
                <li><input type="radio" name="post_category" id="job" value="3">
                    <label for="job">仕事</label>
                </li>
                <li>
                    <input type="radio" name="post_category" id="school" value="4">
                    <label for="school">学校</label>
                </li>
                <li>
                    <input type="radio" name="post_category" id="food" value="5">
                    <label for="food">食べ物</label>
                </li>
                <li>
                    <input type="radio" name="post_category" id="trip" value="6">
                    <label for="trip">旅行</label>
                </li>
                <li>
                    <input type="radio" name="post_category" id="love" value="7">
                    <label for="love">恋愛</label>
                </li>
                <li>
                    <input type="radio" name="post_category" id="event" value="8">
                    <label for="event">行事</label>
                </li>
                <li>
                    <input type="radio" name="post_category" id="other" value="9">
                    <label for="other">その他</label>
                </li>
            </ul>
        </div>

        <div class="form">
            <div class="form-top">
                <div class="form-top-top">
                    <a class="form-top-top-closeButton">
                        <div class="form-top-top-closeButton-border-1"></div>
                        <div class="form-top-top-closeButton-border-2"></div>
                    </a>
                </div>
                <div class="form-top-bottom">
                    <div class="form-top-bottom-content">
                        <textarea placeholder="お題を記入．．．" name="odai"></textarea>
                    </div>
                </div>
            </div>
            <div class="form-bottom">
                <a class="form-bottom-postButtonContent">
                    <input class="form-bottom-postButtonContent-text" type="submit" value="投稿する" name="submitButton">
                    <input type="hidden" name="user_id" value="<?php echo $login_user['id'] ?>">
                    <input type="hidden" name="post_date" value="<?php echo date("Y-m-d H:i:s") ?>">
                </a>
            </div>
        </div>
    </form>

    <script src="../script/index/index.js"></script>
</body>

</html>