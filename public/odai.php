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
}

$pdo = connect();
$odai_id = $_GET['odai_id'];
$odai =get_odai_data($_GET['odai_id']);
//お題が存在しない時
if(!$odai){
    header('Location: http://localhost:80/oogiri-app/public/index.php');
}
$posted_user = get_odai_posted_user($odai['user_id']);

//投稿フォームを打ち込んだとき
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
        
                header('Location: http://localhost:80/oogiri-app/public/odai.php?odai_id='.$odai_id.'');
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

//回答フォームを打ち込んだとき
if (!empty($_POST['post_answer_button'])) {
    //ログインしているか判定し，していなかったら投稿できない
    if (!$result) {
        $_SESSION['post_err'] = 'ユーザを登録してログインしてください';
        header('Location: odai.php?odai_id='.$odai_id.'');
        return;
    }
    //投稿が空の場合
    if (empty($_POST['answer'])) {
        $err_messages['answer'] = "記入されていません";
    } else {
        // お題を保存
        try {
            $stmt = $pdo->prepare("INSERT INTO `answers` (`answer`, `odai_id`, `user_id`, `post_date`) VALUES (:answer, :odai_id, :user_id, :post_date)");
            $stmt->bindParam(':answer', $_POST['answer'], PDO::PARAM_STR);
            $stmt->bindParam(':odai_id', $odai['id'], PDO::PARAM_STR);
            $stmt->bindParam(':user_id', $_POST['user_id'], PDO::PARAM_STR);
            $stmt->bindParam(':post_date', $_POST['post_date'], PDO::PARAM_STR);

            $stmt->execute();
            header('Location: odai.php?odai_id='.$odai_id.'');

            exit;
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }
}

//編集が完了したとき
if(!empty($_POST['updateButton'])){
    //投稿が空の場合
    if(empty($_POST['odai'])) {
      $err_messages['odai'] = "記入されていません";
    } else {
      try{
        $stmt = $pdo->prepare("UPDATE `odais` SET odai = :odai, post_date = :post_date WHERE id = :odai_id");
        $stmt->bindParam(':odai_id', $_POST['odai_id'], PDO::PARAM_STR);
        $stmt->bindParam(':odai', $_POST['odai'], PDO::PARAM_STR);
        $stmt->bindParam(':post_date', $_POST['post_date'], PDO::PARAM_STR);

        $stmt->execute();
  
        header('Location: http://localhost:80/oogiri-app/public/odai.php?odai_id='.$odai_id.'');
        exit;
      } catch (PDOException $e){
        echo $e->getMessage();
      }
    }
  }

$sql = "SELECT * FROM `answers` WHERE odai_id=$odai_id";
$answers = $pdo->query($sql);

//いいね機能
if (isset($_REQUEST['like']) && isset($login_user['id'])) {
    //過去にいいね済みであるか確認
    $my_like_cnt = check_favorite($_REQUEST['like'], $login_user['id']);
  
    //いいねのデータを挿入or削除
  if ($my_like_cnt['cnt'] < 1) {
    $press = $pdo->prepare('INSERT INTO favorite SET user_id=?, answer_id=?');
    $press->execute(array(
        $login_user['id'],
        $_REQUEST['like']
    ));
    header("Location: odai.php?odai_id=$odai_id");
    exit();
  } else {
    $cancel = $pdo->prepare('DELETE FROM favorite WHERE user_id=? AND answer_id=?');
    $cancel->execute(array(
      $login_user['id'],
      $_REQUEST['like']
    ));
    header("Location: odai.php?odai_id=$odai_id");
    exit();
  }
} else if(isset($_REQUEST['like']) && !isset($login_user['id'])) {
    $_SESSION['liked_user'] = 'いいねするにはログインしてください';
    header("Location: login_form.php");
    exit();
}

?>
<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>
    <script src="../script/index/notification.js"></script>
    <link rel="stylesheet" href="../css/odai/odai.css">
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

            <!-- 回答投稿フォーム -->
            <form class="main-content-post" method="POST">
                <input type="text" class="main-content-post-input" name="answer" placeholder="回答を記入．．．">
                <input type="submit" class="main-content-post-send" name="post_answer_button" value="回答"></input>
                <input type="hidden" name="user_id" value="<?php echo $login_user['id']; ?>">
                <input type="hidden" name="post_date" value="<?php echo date("Y-m-d H:i:s"); ?>">
                <input type="hidden" name="odai_id" value="<?php echo $odai_id; ?>">
            </form>

            <!-- 回答を表示 -->
            <?php foreach ($answers as $answer): ?>
            <div class="main-content-answer">
                <div class="main-content-answer-top">
                    <div class="main-content-answer-top-text"><?php echo $answer['answer']; ?></div>
                </div>
                <div class="main-content-answer-bottom">
                    <div class="main-content-answer-bottom-content">
                        <div class="main-content-answer-bottom-content-name"><?php print_username($answer['user_id']); ?></div>
                        <div class="main-content-answer-bottom-content-day"><?php echo $answer['post_date']; ?></div>
                        <?php
                            $my_like_cnt = check_favorite($answer['id'], $login_user['id']);
                            if ($my_like_cnt['cnt'] < 1):
                        ?>
                        <a class="main-content-answer-bottom-content-likeImg" href="odai.php?odai_id=<?php echo $odai_id; ?>&like=<?php echo h($answer['id']); ?>"></a>
                        <?php else : ?>
                        <a class="main-content-answer-bottom-content-clickedLikeImg" href="odai.php?odai_id=<?php echo $odai_id; ?>&like=<?php echo h($answer['id']); ?>"></a>
                        <?php endif; ?>
                        <div class="main-content-answer-bottom-content-likeNum"><?php print_favorite_count($answer['id']); ?></div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>

        </div>
        
        <!-- 編集と削除 -->
        <?php if($odai['user_id']==$login_user['id']): ?>
        <div class="slide">
            <div class="slide-bar">
                <div class="slide-bar-icon"></div>
            </div>
            <a class="slide-edit">編集する</a>
            <a class="slide-delete" href="delete.php?id=<?php echo $odai['id']; ?>">削除する</a>
        </div>
        <?php endif; ?>
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

    <!-- 編集モーダル -->
    <form class="postLayer-content-edit" method="POST">
        <div class="category">
            <ul class="category-content">
                <li>
                    <input type="radio" name="post-category" id="animal">
                    <label for="animal">動物</label>
                </li>
                <li>
                    <input type="radio" name="post-category" id="sport">
                    <label for="sport">スポーツ</label>
                </li>
                <li><input type="radio" name="post-category" id="job">
                    <label for="job">仕事</label>
                </li>
                <li>
                    <input type="radio" name="post-category" id="school">
                    <label for="school">学校</label>
                </li>
                <li>
                    <input type="radio" name="post-category" id="food">
                    <label for="food">食べ物</label>
                </li>
                <li>
                    <input type="radio" name="post-category" id="trip">
                    <label for="trip">旅行</label>
                </li>
                <li>
                    <input type="radio" name="post-category" id="love">
                    <label for="love">恋愛</label>
                </li>
                <li>
                    <input type="radio" name="post-category" id="event">
                    <label for="event">行事</label>
                </li>
                <li>
                    <input type="radio" name="post-category" id="other">
                    <label for="other">その他</label>
                </li>
            </ul>
        </div>

        <div class="form">
            <div class="form-top">
                <div class="form-top-top">
                    <a class="form-top-top-closeButton-editver">
                        <div class="form-top-top-closeButton-border-1"></div>
                        <div class="form-top-top-closeButton-border-2"></div>
                    </a>
                </div>
                <div class="form-top-bottom">
                    <div class="form-top-bottom-content">
                        <textarea placeholder="お題を記入．．．" name="odai"><?php echo $odai['odai']; ?></textarea>
                    </div>
                </div>
            </div>
            <div class="form-bottom">
                <a class="form-bottom-postButtonContent">
                    <input class="form-bottom-postButtonContent-text" type="submit" value="編集完了" name="updateButton">
                    <input type="hidden" name="odai_id" value="<?php echo $odai['id'] ?>">
                    <input type="hidden" name="user_id" value="<?php echo $login_user['id'] ?>">
                    <input type="hidden" name="post_date" value="<?php echo date("Y-m-d H:i:s") ?>">
                </a>
            </div>
        </div>
    </form>
</body>

<script src="../script/index/index.js"></script>
</html>