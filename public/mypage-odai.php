<?php
session_start();
require_once '../classes/UserLogic.php';
require_once '../functions.php';

date_default_timezone_set("Asia/Tokyo");

//ログインしているか判定し，していなかったら新規登録画面へ返す
$result = UserLogic::checkLogin();

if (!$result) {
    $_SESSION['login_err'] = 'ユーザを登録してログインしてください';
    header('Location: signup-form.php');
    return;
}

$pdo = connect();
$login_user = $_SESSION['login_user'];

//フォームを打ち込んだとき
if (!empty($_POST['submitButton'])) {
    //ログインしているか判定し，していなかったら投稿できない
    if (!$result) {
        $err_messages['post_err'] = 'ユーザを登録してログインしてください';
    } else {
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
                $stmt = $pdo->prepare("INSERT INTO `odais` (`odai`, `user_id`, `post_date` , `deadline`, `item_id`) VALUES (:odai, :user_id, :post_date, :deadline, :item_id)");
                $stmt->bindParam(':odai', $_POST['odai'], PDO::PARAM_STR);
                $stmt->bindParam(':user_id', $_POST['user_id'], PDO::PARAM_STR);
                $stmt->bindParam(':post_date', $_POST['post_date'], PDO::PARAM_STR);
                $stmt->bindParam(':deadline', $_POST['deadline'], PDO::PARAM_STR);
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
            
                    header('Location: mypage-odai.php');
                } catch (PDOException $e){
                    echo $e->getMessage();
                }
            } catch (PDOException $e) {
                echo $e->getMessage();
            }
        }
    }
}

// 新着順でお題を抽出
$login_user_id = $login_user['id'];
$sql = "SELECT * FROM `odais` WHERE user_id = $login_user_id ORDER BY id DESC";
$arrival_order_odai_array = $pdo->query($sql);

?>
<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/mypage/mypage.css">
    <link rel="stylesheet" href="../css/mypage/odai.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>
    <script src="../script/index/notification.js"></script>
    <title>マイページ</title>
</head>

<body>

    <header>
        <div class="header-top">
            <div class="header-top-content">
                <a href="index.php" class="header-top-content-home">
                    <div class="header-top-content-home-ex">ホーム</div>    
                    <div class="header-top-content-home-icon1"></div>
                    <div class="header-top-content-home-icon2"></div>
                </a>
                <a href="mypage-home.php" class="header-top-content-account">
                    <div class="header-top-content-account-ex">マイページ</div>    
                    <div class="header-top-content-account-icon"></div>
                </a>
                <div class="header-top-content-item">
                    <div class="header-top-content-item-ex">カテゴリー</div>
                    <div class="header-top-content-item-icon1"></div>
                    <div class="header-top-content-item-icon2"></div>
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
                <a href="signup-form.php" class="header-bottom-register">
                    <div class="header-bottom-register-ex">新規登録</div>
                    <div class="header-bottom-content-register-icon1"></div>
                    <div class="header-bottom-content-register-icon2"></div>
                </a>
                <a class="header-bottom-content-post">
                    <div class="header-bottom-content-post-ex">投稿</div>
                    <div class="header-bottom-content-post-icon1"></div>
                    <div class="header-bottom-content-post-icon2"></div>
                </a>
            </div>
        </div>
    </header>
    <!-- 投稿を表示 -->
    <main>
        <!-- エラーメッセージ表示 -->
        <?php include('../inc/error-messages.php'); ?>

        <div class="main-content">
            <div class="main-content-myInfo">
                <div class="main-content-myInfo-name"><?php echo h($login_user['username']) ?></div>
                <form class="main-content-myInfo-logout" action="logout.php" method="POST">
                    <input class="main-content-myInfo-logout-button" type="submit" name="logout" value="ログアウト">
                </form>
            </div>
            <div class="main-content-myContent">
                <div class="main-content-myContent-selector">
                    <div class="main-content-myContent-selector-content">
                        <a href="mypage-home.php" class="main-content-myContent-selector-content-home">ホーム</a>
                        <a href="#" class="main-content-myContent-selector-content-odai">お題</a>
                        <a href="mypage-answer.php" class="main-content-myContent-selector-content-answer">回答</a>
                        <a href="mypage-like.php" class="main-content-myContent-selector-content-like">いいねした回答</a>
                    </div>
                    <div class="main-content-myContent-coin">
                        <div class="main-content-myContent-coin-content"></div>
                    </div>
                    <div class="main-content-myContent-coinText"><?php echo h($login_user['point']) ?></div>
                </div>
                <div class="main-content-myContent-content">

                <div class="main-content-content">
                        <div class="main-content-content-name">
                            <div class="main-content-content-name-border"></div>
                            <div class="main-content-content-name-text">
                                <div class="main-content-content-name-text-orange">
                                    <div class="main-content-content-name-text-orange-text">お</div>
                                </div>
                                <div class="main-content-content-name-text-white">
                                    <div class="main-content-content-name-text-white-text">題</div>
                                </div>
                            </div>
                        </div>
                        <div class="main-content-content-posts">
                            <div class="main-content-content-posts-area">
                            <?php foreach ($arrival_order_odai_array as $odai) :?>
                                <a href="odai-arrivalOrder.php?odai_id=<?php echo $odai['id']; ?>" class="main-content-content-posts-area-post">
                                    <div class="main-content-content-posts-area-post-top">
                                        <div class="main-content-content-posts-area-post-content">
                                            <div class="main-content-content-posts-area-post-content-text"><?php echo $odai['odai'] ?></div>
                                        </div>
                                    </div>
                                    <div class="main-content-content-posts-area-post-bottom">
                                        <?php 
                                            //回答を2つ抽出
                                            $odai_id = $odai['id'];
                                            $sql = "SELECT * FROM `answers` WHERE odai_id = $odai_id ORDER BY favorite_count DESC LIMIT 2";
                                            $favorite_count_order_answer_array = $pdo->query($sql);
                                            foreach ($favorite_count_order_answer_array as $answer) :?>
                                        <div class="main-content-content-posts-area-post-bottom-top">
                                            <div class="main-content-content-posts-area-post-bottom-top-text"><?php echo $answer['answer'] ?></div>
                                        </div>
                                        <?php endforeach; ?>
                                    </div>
                                </a>
                            <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                    
                </div>
            </div>
        </div>
    </main>

    <!-- 投稿モーダル -->
    <?php include('../inc/post-modal.php'); ?>

    <script src="../script/index/index.js"></script>
</body>

</html>