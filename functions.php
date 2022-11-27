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
 * いいねの数を表示
 * @param string コメントのid
 * @return void
 */

function print_favorite_count($comment_id) {
  $pdo = connect();
  $sql = "SELECT COUNT(*) AS cnt FROM `favorite` WHERE comment_id=$comment_id";
  $stmt = $pdo->query($sql);
  $count = $stmt->fetch(PDO::FETCH_ASSOC);

  echo $count['cnt'];
}

/**
 * お題を投稿した人のデータを取得
 * @param 
 * @return 
 */

function get_odai_posted_user($user_id) {
  $pdo = connect();
  $sql = "SELECT * FROM `users` WHERE id=$user_id";
  $stmt = $pdo->query($sql);
  $posted_user = $stmt->fetch(PDO::FETCH_ASSOC);
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