<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

 /**
  *
  * 作成日：2018/08/14
  * 更新日：2018/09/17
  * 作成者：戸田滉洋
  * 更新者：戸田滉洋
  *
  */
?>

<h3 style="text-alin:center">分析用</h3>
<ul class="nav nav-tabs">
    <li class="nav-item"><a href="oneDay">1日</a></li>
    <li class="nav-item"><a href="week">週間</a></li>
    <li class="nav-item"><a href="month">月間</a></li>
    <li class="nav-item"><a href="year">年間</a></li>
    <li class="nav-item"><a href="analysis">分析用</a></li>
</ul>

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
<div id="chart"></div>

<ul class="nav nav-tabs" style="border-bottom:none;">
	<li class="nav-item"><a href="analysisinfo">詳細表を表示</a></li>
</ul>
<script>

    var analysis_array = <?php echo json_encode($date_array); ?>;
//    console.log(analysis_array);
    var chartdata = [];
    // googechartで使用出来るように配列の変更
    for (key in analysis_array) {
//        console.log(key);
//        arrayData.push(key);
        chartdata.push([key, analysis_array[key]]);
    }
    
    //console.log(arrayData);
    // googlechart表示
    google.charts.load('current', {'packages': ['corechart']});
    google.setOnLoadCallback(drawChart);
    function drawChart() {
        var data = new google.visualization.arrayToDataTable(chartdata);
        var options = {
            "title": "使用電力量",
            "titleTextStyle": {
                "fontSize": 20
            },
            "vAxis": {
                title: 'kw/h',
            },
            hAxis: {
                title: 'hour'
            },
            "width": 900,
            "height": 600,
        };
        var chart = new google.visualization.ColumnChart(document.getElementById('chart'));
        chart.draw(data, options);
    }
</script>
