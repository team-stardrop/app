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
                <input type="hidden" name="deadline" value="<?php echo date("Y-m-d H:i:s", strtotime("7 day")) ?>">
            </a>
        </div>
    </div>
</form>