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

if($result) {
  $login_user = $_SESSION['login_user'];
}

//フォームを打ち込んだとき
if(!empty($_POST['odai'])){
  //ログインしているか判定し，していなかったら投稿できない
  if (!$result) {
    $_SESSION['post_err'] = 'ユーザを登録してログインしてください';
    header('Location: index.php');
    return;
  }
  //投稿が空の場合
  if(empty($_POST['odai'])) {
    $err_messages['odai'] = "記入されていません";
  } else {
    try{
      $stmt = $pdo->prepare("INSERT INTO `odais` (`odai`, `user_id`, `post_date`) VALUES (:odai, :user_id, :post_date)");
      $stmt->bindParam(':odai', $_POST['odai'], PDO::PARAM_STR);
      $stmt->bindParam(':user_id', $_POST['user_id'], PDO::PARAM_STR);
      $stmt->bindParam(':post_date', $_POST['post_date'], PDO::PARAM_STR);
    
      $stmt->execute();

      header('Location: http://localhost:80/oogiri-app/public/index.php');
      exit;
    } catch (PDOException $e){
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
                    <div class="main-content-content-name-text">AAA</div>
                </div>
                <div class="main-content-content-posts">
                    <div class="main-content-content-posts-area">
                    <?php foreach($post_array as $post):
                      $users = get_odai_posted_user($post['user_id']);
                    ?>
                        <div class="main-content-content-posts-area-post">
                            <div class="main-content-content-posts-area-post-content">
                                <div class="main-content-content-posts-area-post-content-text"><?php echo $post['odai'] ?></div>
                            </div>
                            <div class="main-content-content-posts-area-post-meta">
                                <div class="main-content-content-posts-area-post-meta-name">
                                    <div class="main-content-content-posts-area-post-meta-name-text">名前：<?php foreach($users as $user): echo $user['username']; endforeach;?></div>
                                </div>
                                <div class="main-content-content-posts-area-post-meta-data">
                                    <div class="main-content-content-posts-area-post-meta-data-text"><?php echo $post['post_date']; ?></div>
                                </div>
                            </div>
                        </div>
                        <?php endforeach;?>
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