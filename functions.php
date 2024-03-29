<?php

require_once 'dbconnect.php';

/**
 * XSS対策：エスケープ処理
 * 
 * @param string $str 対象の文字列
 * @return string 処理された文字列
 */
function h($str) {
  return htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
}

/**
 * CSRF対策
 * @param void
 * @return string $csrf_token
 */
function setToken() {
  // トークンを生成
  // フォームからそのトークンを送信
  // 送信後の画面でそのトークンを照会
  // 処理が終わればトークンを削除
  $csrf_token = bin2hex(random_bytes(32));
  $_SESSION['csrf_token'] = $csrf_token;

  return $csrf_token;
}

/**
 * コメント表示
 * @param string commentのid
 * @return array commentの配列
 */
function get_comment($comment_id) {
  $pdo = connect();
  $sql = "SELECT id,user_id,comment FROM `comments` WHERE post_id=$comment_id";
  $comment_array = array();
  return $comment_array = $pdo->query($sql);
}

/**
 * 回答したユーザー名を表示
 * @param string userのid
 * @return void
 */
function print_username($user_id) {
  $pdo = connect();
  $sql = "SELECT username FROM `users` WHERE id=$user_id";
  $stmt = $pdo->query($sql);
  $username = $stmt->fetch(PDO::FETCH_ASSOC);
  if(empty($username)){
    echo '名無しユーザー';
  } else {
    echo $username['username'];
  }
}

/**
 * お題を投稿した人のデータを取得
 * @param string お題を投稿したuserのid
 * @return 
 */

function get_odai_posted_user($user_id) {
  $pdo = connect();
  $sql = "SELECT * FROM `users` WHERE id=$user_id";
  $stmt = $pdo->query($sql);
  $posted_user = $stmt->fetch(PDO::FETCH_ASSOC);
  if(empty($posted_user['username'])){
    $posted_user['username'] = '名無しユーザー';
  }
  return $posted_user;
}

/**
 * カテゴリーデータを取得
 * 
 */

function get_item_data($item_id) {
  $pdo = connect();
  $sql = "SELECT id, item_name  FROM `items` WHERE id = $item_id";
  $stmt = $pdo->query($sql);
  $item = $stmt->fetch(PDO::FETCH_ASSOC);
  return $item;
}

/**
 * お題データを取得
 * 
 */

function get_odai_data($odai_id) {
  $pdo = connect();
  $sql = "SELECT * FROM `odais` WHERE id=$odai_id";
  $stmt = $pdo->query($sql);
  $odai = $stmt->fetch(PDO::FETCH_ASSOC);
  return $odai;
}

/**
 * いいね済みか判定
 * @param string post_id
 * @param string $login_user_id
 * @return string 
 */

function check_favorite($like, $login_user_id){
  $pdo = connect();
  $pressed = $pdo->prepare('SELECT COUNT(*) AS cnt FROM favorite WHERE user_id=? AND answer_id=?');
  $pressed->execute(array(
    $login_user_id,
    $like
  ));

  return $pressed->fetch();
}

/**
 * いいねの数を表示
 * @param string コメントのid
 * @return void
 */

function print_favorite_count($answer_id) {
    $pdo = connect();
    $sql = "SELECT COUNT(*) AS cnt FROM `favorite` WHERE answer_id=$answer_id";
    $stmt = $pdo->query($sql);
    $count = $stmt->fetch(PDO::FETCH_ASSOC);
  
    add_favorite_count_into_answer($answer_id, $count['cnt']);
    echo 'いいね　';
    echo $count['cnt'];
  }

/**
 * いいねの数をanswersテーブルに保存
 * @param string 回答のid
 * @param string いいね数のid
 * @return void
 */

function add_favorite_count_into_answer($answer_id, $count) {
  $pdo = connect();
  try {
    $stmt = $pdo->prepare("UPDATE `answers` SET favorite_count = :count WHERE id = :answer_id");
    $stmt->bindParam(':count', $count, PDO::PARAM_STR);
    $stmt->bindParam(':answer_id', $answer_id, PDO::PARAM_STR);
    
    $stmt->execute();
    
  } catch (PDOException $e) {
    echo $e->getMessage();
  }
}

/**
 * いいね数が一位の回答が一つかどうかの確認
 * 
 */

function notEqual_favorite_count($odai_id) {
  $pdo = connect();

  $sql = "SELECT * FROM `answers` WHERE odai_id=$odai_id ORDER BY favorite_count DESC LIMIT 2";
  $stmt = $pdo->query($sql);
  $answers = $stmt->fetchAll(PDO::FETCH_ASSOC);
  // var_dump($answers);
  if($answers[0]['favorite_count']!=$answers[1]['favorite_count']){
    return true;
  } else {
    return false;
  }
}

/**
 * いいね数が一位の回答が一つかどうかの確認
 * 
 */

function notZero_answer_count($odai_id) {
  $pdo = connect();
  $sql = "SELECT COUNT(*) AS CNT FROM `answers` WHERE odai_id=$odai_id";
  $stmt = $pdo->query($sql);
  $count = $stmt->fetch(PDO::FETCH_ASSOC);
  if($count['CNT']!=0){
    return true;
  } else {
    return false;
  }
}

/**
 * ベストアンサー処理（回答者にポイントを追加
 */

function best_answer_process($odai_id) {
  // お題IDから一番いいねの多い回答を1つ取得
  $pdo = connect();
  $sql = "SELECT * FROM `answers` WHERE odai_id=$odai_id ORDER BY favorite_count DESC LIMIT 1";
  $stmt = $pdo->query($sql);
  $answer = $stmt->fetch(PDO::FETCH_ASSOC);
  if($answer){
    try {
      // その回答投稿者のデータを抽出
      $user_id = $answer['user_id'];
      $sql = "SELECT * FROM `users` WHERE id=$user_id";
      $stmt = $pdo->query($sql);
      $answer_posted_user = $stmt->fetch(PDO::FETCH_ASSOC);
  
    } catch (PDOException $e) {
      echo $e->getMessage();
    }
    
    // ポイント追加
    $answer_posted_user['point'] += 200;
    $stmt = $pdo->prepare("UPDATE `users` SET point = :point WHERE id = :user_id");
    $stmt->bindParam(':point', $answer_posted_user['point'], PDO::PARAM_STR);
    $stmt->bindParam(':user_id', $answer_posted_user['id'], PDO::PARAM_STR);
    
    $stmt->execute();
  }
}