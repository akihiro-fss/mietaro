<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
?>
<h3><?php echo Session::get_flash('success', 'ようこそ' . Auth::get_screen_name() . 'さん');
?></h3>

<?php //Debug::dump($totaldata);?>
<?php Debug::dump($date_array);?>
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
    console.log(analysis_array);
    var arrayData = [];
    //googechartで表示されるようにarrayを作成中
    for ()
    console.log(arrayData);
    (function(){
      'use strict';

      function drawChart() {
        var target = document.getElementById('target');
        var data;
        var options = {
          title: 'My Chart',
          width: 500,
          height: 300,

        };

        var chart = new google.visualization.ColumnChart(target);
        //data = new google.visualization.arrayToDataTable(totaldata);
        //data.addColumn('datetime','data');

        chart.draw(data, options);
      }
      google.charts.load('current', {'packages': ['corechart']});
      google.charts.setOnLoadCallback(drawChart);

    })();
</script>
