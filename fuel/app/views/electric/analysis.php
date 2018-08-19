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
<script>


    var analysis_array = <?php echo json_encode($date_array); ?>;
//    console.log(analysis_array);
    var arrayData = [];
    //googechartで表示されるようにarrayを作成中
    for (key in analysis_array) {
//        console.log(key);
//        arrayData.push(key);
        arrayData.push([key, analysis_array[key]]);
    }

    //console.log(arrayData);
    google.charts.load('current', {'packages': ['corechart']});
    google.setOnLoadCallback(drawChart);
    function drawChart() {
        var target = document.getElementById('chart');
        var data = new google.visualization.arrayToDataTable(arrayData);
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
            "width": 800,
            "height": 500,
        };
        var chart = new google.visualization.ColumnChart(target);
        chart.draw(data, options);
    }
</script>
