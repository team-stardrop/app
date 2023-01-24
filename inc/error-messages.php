<!-- エラーメッセージ表示 -->
<div class="error">
    <?php if (isset($err_messages['post_err'])) : ?>
        <script>
            notification("<?php echo $err_messages['post_err']; ?>");
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
    <?php if (isset($err_messages['answer'])) : ?>
        <script>
            notification("<?php echo $err_messages['answer']; ?>");
        </script>
    <?php endif; ?>
</div>