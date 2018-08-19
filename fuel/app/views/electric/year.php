<?php
/**
 *
 * 作成日：2017/8/11
 * 更新日：2018/08/19
 * 作成者：戸田滉洋
 * 更新者：丸山　隼
 *
 */
/**
 * The Top Electric.
 *
 * 年間データグラフの表示
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

<?php echo Form::open(array('name' => 'search', 'method' => 'post', 'class' => 'form-horizontal')); ?>
<table>
<tr><th align="left">指定した日付の年の</br>使用電力情報が表示されます</th><th></tr></tr>
<tr>
    <th valign="top">
        <?php echo Form::input('oneyeardate', 'oneyeardate', array('type' => 'date')); ?>
        <?php echo Form::submit('submit', '決定', array('class' => 'btn btn-primary')); ?>
    </th>
    <td>
        <ul style="list-style:none;">
            <li><b>使用電力量</b>　　　<span id="total_set_1"></span>kwh </li>
            <li><b>最大デマンド値</b>　<span id="max_demand_1"></span>kW  </li>
            <li><b>CO2排出量</b>　　　-kg-CO2 </li>
            <li><b>電力量料金</b>　　　-円 </li>
        </ul>
    </td>
</tr>
<tr>
<th align="left"><p><input name="second_graph_flag" id="second_graph_flag" type = "checkbox" value="1">比較用グラフを表示する</p></th><td></td>
</tr>
<tr>
    <th valign="top">
        <?php echo Form::input('twoyeardate', 'twoyeardate', array('type' => 'date')); ?><input id="dummy_button" type="submit" class="btn btn-ptimary">
        <script>
            $("#dummy_button").css("visibility","hidden");
        </script>
    </th>
    <td>
        <ul style="list-style:none;">
            <li><b>使用電力量</b>　　　<span id="total_set_2"></span>kwh </li>
            <li><b>最大デマンド値</b>　<span id="max_demand_2"></span>kW  </li>
            <li><b>CO2排出量</b>　　　-kg-CO2 </li>
            <li><b>電力量料金</b>　　　-円 </li>
        </ul>
    </td>
</tr>
</table>
<?php echo Form::close(); ?>

<div id="chart"></div>

<!-- 日付データの保持 -->
<input type="hidden" id="param_date_1" name="param_date_1" value="">
<input type="hidden" id="param_date_2" name="param_date_2" value="">

<ul class="nav nav-tabs" style="border-bottom:none;">
	<li class="nav-item"><a href="sample">気温グラフを表示</a></li>
	<li class="nav-item"><a id="yeardemand">デマンドグラフを表示</a></li>
	<li class="nav-item"><a id="yearinfo">詳細表を表示</a></li>
</ul>

<script>
    var yearData = <?php echo json_encode($yearData); ?>;
    var year = yearData['result'];
    var total1 = yearData['total_set_1'];
    var total2 = yearData['total_set_2'];
    var max1 = yearData['max_demand_1'];
    var max2 = yearData['max_demand_2'];
    var targetDate1 = yearData['target_date_1'];
    var targetDate2 = yearData['target_date_2'];
    var checked_flg = yearData['checked_flg'];

    //電力量合計値セット
    $('#total_set_1').append(total1);
    $('#total_set_2').append(total2);
    //デマンド最大値セット
    $('#max_demand_1').append(max1);
    $('#max_demand_2').append(max2);
    //日付セット
    $('#form_oneyeardate').val(targetDate1);
    $('#form_twoyeardate').val(targetDate2);

    //詳細ページに遷移
    $('#yearinfo').click(function () {
        var param1 = $('#form_onemonthdate').val();
        var param2 = $('#form_onemonthdate').val();
        var param3 = checked_flg;
        var data={'param_date_1':param1,'param_date_2':param2,'second_graph_flag':param3};
        postForm('yearinfo',data);
    });

    //デマンドページに遷移
    $('#yeardemand').click(function () {
        var param1 = $('#form_onemonthdate').val();
        var param2 = $('#form_onemonthdate').val();
        var param3 = checked_flg;
        var data={'param_date_1':param1,'param_date_2':param2,'second_graph_flag':param3};
        postForm('yeardemand',data);
    });

    /* チャート表示処理 */
    if(checked_flg){
        //チェックボックスのデフォルト設定
        $('input[name="second_graph_flag"]').prop('checked', true);
        //チャート表示
        google.charts.load('current', {'packages': ['corechart']});
        google.setOnLoadCallback(drawChart);
        function drawChart() {
            var data = new google.visualization.arrayToDataTable(year);
            var options = {
                "title": "年間使用電力量",
                "titleTextStyle": {
                    "fontSize": 20
                },
                "vAxis": {
                    title: 'kw/h',
                },
                hAxis: {
                    title: 'month'
                },
                "width": 1000,
                "height": 600,
                seriesType: 'bars',
                series: {1: {type: 'line'}}
            };
            var chart = new google.visualization.ComboChart(document.getElementById('chart'));
            chart.draw(data, options);
        }
    }else{
        //チャート表示処理
        google.charts.load('current', {'packages': ['corechart']});
        google.setOnLoadCallback(drawChart);
        function drawChart() {
            var data = new google.visualization.arrayToDataTable(year);
            var options = {
                "title": "年間使用電力量",
                "titleTextStyle": {
                    "fontSize": 20
                },
                "vAxis": {
                    title: 'kw/h',
                },
                hAxis: {
                    title: 'month'
                },
                "width": 1000,
                "height": 600,
            };
            var chart = new google.visualization.ColumnChart(document.getElementById('chart'));
            chart.draw(data, options);
        }
    }
    //POST送信用
    var postForm = function(url, data) {
        var $form = $('<form/>', {'action': url, 'method': 'post'});
        for(var key in data) {
                $form.append($('<input/>', {'type': 'hidden', 'name': key, 'value': data[key]}));
        }
        $form.appendTo(document.body);
        $form.submit();
    };
</script>
