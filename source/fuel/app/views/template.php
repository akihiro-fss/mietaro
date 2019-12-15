<?php
/**
 *
 * 作成日：2017/07/16
 * 更新日：2017/12/23
 * 作成者：戸田滉洋
 * 更新者：戸田滉洋
 *
 */

/**
 * The Top Views.
 *
 * テンプレート
 * @package app
 * @extends Views
 */
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title><?php echo isset($title) ? $title : 'MIETARO'; ?></title>
        <meta name="viewport" content="width=device-width,minimum-scale=1">
        <?php echo Asset::css('bootstrap.min.css'); ?>
        <?php echo Asset::css('bootstrap-responsive.min.css'); ?>
        <!--自分専用スタイルシート-->
        <?php //echo Asset::css('my-style.css'); ?>
        <?php //echo Asset::js('jquery-1.7.2.min.js'); ?>
        <!-- <script src="https://code.jquery.com/jquery-2.1.4.min.js"></script> -->
        <?php echo Asset::js('jquery-2.1.4.min.js'); ?>
        <script src="https://www.google.com/jsapi"></script>
        <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
        <?php echo Asset::js('bootstrap.min.js'); ?>
    </head>
    <body>
        <div class="navbar">
            <div class="navbar-inner">
                <div class="container">
                    <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </a>
                    <?php echo Html::anchor('top/news', isset($title) ? $title : 'MIETARO', array('class' => 'brand')) ?>
                    <div class="nav-collapse">
                        <ul class="nav">
                            <?php if (Auth::check()): ?>
                                <li><?php echo Html::anchor('top/news', 'NEWS') ?></li>
                                <?php if (Auth::member(100) or Auth::member(50)): ?>
                                    <li><?php echo Html::anchor('admin/create', '新規ユーザ') ?></li>
                                    <li><?php echo Html::anchor('admin/select', 'ユーザ一覧') ?></li>
                                    <li><?php echo Html::anchor('BasicInfo/createstore', '新規店舗') ?></li>
                                <?php endif; ?>
                                <li><?php echo Html::anchor('admin/user', 'ユーザ情報') ?></li>
                                <li><?php echo Html::anchor('BasicInfo/basic', '各種設定') ?></li>
                                <li><?php echo Html::anchor('Electric/oneDay', 'データ参照') ?></li>
                                <li><?php echo Html::anchor('admin/logout', 'ログアウト') ?></li>
                            <?php endif; ?>
                        </ul>
                    </div>
                </div><!--/nav-->
            </div><!--/navbar-innner-->
        </div><!--/navbar-->
        <div class="container">
            <div id="header">
                <!--ヘッダーがあれば表示する-->
                <?php echo isset($header) ? $header : ''; ?>
            </div><!--/header-->
            <div class="row" id="content">
                <!--サイドバーがあれば表示する-->
                <div class="span2">
                    <?php echo isset($sidebar) ? $sidebar : ''; ?>
                </div>
                <!--ここにコンテンツを表示します。-->
                <div class="span10">
                    <?php echo isset($content) ? $content : ''; ?>
                </div>
            </div><!--/content-->
            <div id="footer" style="background-color:yellow; text-align: center;">
                <p>MIETARO</p>
                <p>表示速度{exec_time}s　使用メモリ{mem_usage}mb</p>
            </div><!--/footer-->
        </div><!--/container-->
    </body>
</html>
