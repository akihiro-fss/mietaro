<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

 /**
  *
  * 作成日：2018/09/16
  * 更新日：
  * 作成者：戸田滉洋
  * 更新者：
  *
  */
?>
<ul class="nav nav-tabs">
    <li class="nav-item"><a href="oneDay">1日</a></li>
    <li class="nav-item"><a href="week">週間</a></li>
    <li class="nav-item"><a href="month">月間</a></li>
    <li class="nav-item"><a href="year">年間</a></li>
    <li class="nav-item"><a href="analysis">分析用</a></li>
</ul>
<h3 style="text-alin:center">分析用詳細</h3>
<table>
    <?php echo Form::open(array('name' => 'analysis', 'method' => 'post', 'class' => 'form-horizontal')); ?>
    <tr>
        <th align="left">
            表示したい日付時間を指定してください</br>
        </th>
    </tr>
    <tr>
        <th align="left">
            表示開始時間</br>
            <?php echo Form::input('starttime', $starttime, array('type' => 'datetime-local')); ?>
        </th>
        <th align="left">
            表示終了時間</br>
            <?php echo Form::input('endtime', $endtime, array('type' => 'datetime-local')); ?></br>
        </th>
    </tr>
    <tr>
    <th align="left">
    <?php echo Form::submit('submit', '決定', array('class' => 'btn btn-primary'));?></th>
    </tr>
</table>
</br>
<table id="electric-data-table" class="table table-bordered">
    <tr>
    <th>時間</br></th>
    <th colspan="2">使用電力量(kWh)</br></th>
    <th>デマンド値(kW)</br></th>
    </tr>
    <?php
     $length = count($date_array);
    for ($i = 0;$i < $length; $i++) {
        echo "<tr>";
        echo "<td>";
        echo $date_array[$i];
        echo "</td>";
        echo "<td>";
        if ($electric[$date_array[$i]] == 0) {
            echo "";
        } else {
            echo $electric[$date_array[$i]];
        }
        echo "</td>";
        echo "<td>";
        if ($electric_data[$date_array[$i]] == 0) {
            echo "";
        } else {
            echo $electric_data[$date_array[$i]];
        }
        echo "</td>";
        echo "<td>";
        if ($demand[$date_array[$i]] == 0) {
            echo "";
        } else {
            echo $demand[$date_array[$i]];
        }
        echo "</td>";
        echo "</tr>";
    }
    ?>
</table>


    


