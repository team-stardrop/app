<?php

?>
<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>大喜利</title>
    <link rel="stylesheet" href="../css/index/main.css">
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
        <div class="main-header">
            <div class="main-header-text">
                <h2>動物</h2>
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
</body>

</html>