<?php
/**
 *
 * 作成日：2017/1/11
 * 更新日：2017/1/23
 * 作成者：丸山　隼
 * 更新者：戸田滉洋
 *
 */
/**
 * The Top Electric.
 *
 * 分析用のタブをブランクで表示するための画面
 * @package app
 * @extends Views
 */
?>

<h3><?php echo Session::get_flash('success', 'ようこそ' . Auth::get_screen_name() . 'さん'); ?></h3>
<ul class="nav nav-tabs">
    <li class="nav-item"><a href="oneDay">1日</a></li>
    <li class="nav-item"><a href="week">週間</a></li>
    <li class="nav-item"><a href="month">月間</a></li>
    <li class="nav-item"><a href="year">年間</a></li>
    <li class="nav-item"><a href="analysis">分析用</a></li>
</ul>
</div>