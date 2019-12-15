<?php
/**
 *
 * 作成日：2017/07/16
 * 最終更新日：2017/12/23
 * 作成者：戸田滉洋
 * 最終更新者：戸田滉洋
 *
 */
/**
 * The Top admin.
 *
 * ログイン画面
 * @package app
 * @extends Views
 */
?>
<div class="row">
    <div class="span4 offset1">
        <h2 style="text-align:center">ログイン</h2>
        <?php echo Form::open(array('name' => 'login', 'method' => 'post', 'class' => 'form-horizontal')); ?>
        <?php echo '<div class="alert-error">' . Session::get_flash('error') . '</div>' ?>

        <div class="control-group">
            <label class="control-label" for="username">ユーザー名</label>
            <div class="controls">
                <?php echo Form::input('username', Input::post('username')); ?>
            </div>
        </div>
        <div class="control-group">
            <label class="control-label" for="password">パスワード</label>
            <div class="controls">
                <?php echo Form::password('password'); ?>
            </div>
            <div class="form-actions" style="text-align:center">
                <?php echo Form::submit('submit', 'ログイン', array('class' => 'btn btn-primary span3')); ?>
            </div>
            <?php echo Form::close(); ?>
            <?php echo Html::anchor('admin/lostpass', 'パスワードをお忘れですか?'); ?>
        </div>
    </div><!--/span4 offset2-->
</div><!--/row-->