<?php

session_start();
require_once '../classes/UserLogic.php';
require_once '../dbconnect.php';
require_once '../functions.php';

date_default_timezone_set("Asia/Tokyo");

//ログインしているか判定
$result = UserLogic::checkLogin();

if ($result) {
    $login_user = $_SESSION['login_user'];
    $_SESSION['post_err'] = [];
}

$pdo = connect();
$odai_id = $_GET['odai_id'];
$odai =get_odai_data($_GET['odai_id']);
//お題が存在しない時
if(!$odai){
    header('Location: index.php');
}
$posted_user = get_odai_posted_user($odai['user_id']);

//投稿フォームを打ち込んだとき
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
    
                    header('Location: odai-like.php?odai_id='.$odai_id.'');
                } catch (PDOException $e){
                    echo $e->getMessage();
                }
    
                exit;
            } catch (PDOException $e) {
                echo $e->getMessage();
            }
        }
    }
}

$sql = "SELECT * FROM `answers` WHERE odai_id=$odai_id ORDER BY favorite_count DESC LIMIT 1";
$stmt = $pdo->query($sql);
$bestanswer = $stmt->fetch(PDO::FETCH_ASSOC);

$bestanswer_id = $bestanswer['id'];

$sql = "SELECT * FROM `answers` WHERE odai_id=$odai_id ORDER BY favorite_count DESC";
$answers = $pdo->query($sql);
?>
<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>
    <script src="../script/index/notification.js"></script>
    <link rel="stylesheet" href="../css/bestAnswer/bestAnswer.css">
    <title>クスッと</title>
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
                    <div class="header-top-content-account-icon1"></div>
                    <div class="header-top-content-account-icon2"></div>
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

            <!-- お題表示 -->
            <div class="main-content-odai">
                <div class="main-content-odai-text">
                    <div class="main-content-odai-text-content"><?php echo $odai['odai']; ?></div>
                </div>
                <div class="main-content-odai-meta">
                    <div class="main-content-odai-meta-name"><?php echo $posted_user['username']; ?></div>
                    <div class="main-content-odai-meta-day"><?php echo $odai['post_date']; ?></div>
                </div>
            </div>

            <!-- ベストアンサー -->
            <div class="main-content-bestAns">
                <div class="main-content-bestAns-background"></div>
                <div class="main-content-bestAns-content">
                    <div class="main-content-bestAns-content-img"></div>
                    <div class="main-content-bestAns-content-text">
                        <div class="main-content-bestAns-content-text-content"><?php echo $bestanswer['answer']; ?></div>
                    </div>
                    <div class="main-content-bestAns-content-meta">
                        <div class="main-content-bestAns-content-meta-name"><?php print_username($bestanswer['user_id']); ?></div>
                        <div class="main-content-bestAns-content-meta-day"><?php echo $bestanswer['post_date']; ?></div>
                        <div class="main-content-answer-bottom-content-likeNum"><?php print_favorite_count($bestanswer['id']); ?></div>
                    </div>
                </div>
            </div>

            <div class="main-content-border">
                <div class="main-content-border-line"></div>
                <div class="main-content-border-text">その他の回答</div>
            </div>

            <!-- その他の回答を表示 -->
            <?php foreach ($answers as $answer): ?>
            <div class="main-content-answer">
                <div class="main-content-answer-top">
                    <div class="main-content-answer-top-text"><?php echo $answer['answer']; ?></div>
                </div>
                <div class="main-content-answer-bottom">
                    <div class="main-content-answer-bottom-content">
                        <div class="main-content-answer-bottom-content-name"><?php print_username($answer['user_id']); ?></div>
                        <div class="main-content-answer-bottom-content-day"><?php echo $answer['post_date']; ?></div>
                        <div class="main-content-answer-bottom-content-likeNum"><?php print_favorite_count($answer['id']); ?></div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>

    </main>

    <!-- 投稿モーダル -->
    <?php include('../inc/post-modal.php'); ?>

</body>

<script src="../script/index/index.js"></script>

</html>