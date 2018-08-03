<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
?>
<h3><?php echo Session::get_flash('success', 'ようこそ' . Auth::get_screen_name() . 'さん');
?></h3>
<ul class="nav nav-tabs">
    <li class="nav-item"><a href="oneDay">1日</a></li>
    <li class="nav-item"><a href="week">週間</a></li>
    <li class="nav-item"><a href="month">月間</a></li>
    <li class="nav-item"><a href="year">年間</a></li>
    <li class="nav-item"><a href="analysis">分析用</a></li>
</ul>
<h3 style="text-alin:center">分析用</h3>
仕様が不明確であり、表示データに不具合があったため、<br/>
どのような情報を表示させるか検討がつかなく表示をしていない。<br/>
１日のデータを表示をしていたため、日時データでの補完出来る認識である。
