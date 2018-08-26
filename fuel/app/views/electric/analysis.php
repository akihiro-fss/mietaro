<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

 /**
  *
  * 作成日：2018/08/14
  * 更新日：2018/08/25
  * 作成者：戸田滉洋
  * 更新者：戸田滉洋
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
<h3 style="text-alin:center">分析用</h3>
<table>
    <tr><th align="left">表示したい日付時間を指定してください</br>
  </th></tr>
<tr>
    <th align="left">
      <?php echo Form::open(array('name' => 'analysis', 'method' => 'post', 'class' => 'form-horizontal')); ?>
        表示開始時間<?php echo Form::input('starttime',$starttime, array('type' => 'datetime-local')); ?></br>
        表示終了時間<?php echo Form::input('endtime', $endtime, array('type' => 'datetime-local')); ?></br>
        <?php echo Form::submit('submit', '決定', array('class' => 'btn btn-primary')); ?></br>
    </th>
  </tr>
</table>
<div id="chart"></div>
<script>


    var analysis_array = <?php echo json_encode($date_array); ?>;
//    console.log(analysis_array);
    var chartdata = [];
    //googechartで使用出来るように配列の変更
    for (key in analysis_array) {
//        console.log(key);
//        arrayData.push(key);
        chartdata.push([key, analysis_array[key]]);
    }
    console.log(chartdata);
    //console.log(arrayData);
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
