<?php
/**
 *
 * 作成日：2017/8/11
 * 更新日：2017/1/23
 * 作成者：戸田滉洋
 * 更新者：丸山　隼
 *
 */
/**
 * The Top Electric.
 *
 * 週間データのグラフ表示
 * @package app
 * @extends Views
 */
?>
<?php
$dataArray = Model_Electric::weekdaydata();
$checkedFlg = json_encode($dataArray['checked_flg']);
$targetDate1 = json_encode($dataArray['target_date_1']);
$targetDate2 = json_encode($dataArray['target_date_2']);
$oneWeek = json_encode($dataArray['one_week']);
$twoWeek = json_encode($dataArray['two_week']);
$totalSet1 = json_encode($dataArray['total_set_1']);
$totalSet2 = json_encode($dataArray['total_set_2']);
?>

<h3><?php echo Session::get_flash('success', 'ようこそ' . Auth::get_screen_name() . 'さん'); ?></h3>
<ul class="nav nav-tabs">
    <li class="nav-item"><a href="oneDay">1日</a></li>
    <li class="nav-item"><a href="week">週間</a></li>
    <li class="nav-item"><a href="month">月間</a></li>
    <li class="nav-item"><a href="year">年間</a></li>
    <li class="nav-item"><a href="analysis">分析用</a></li>
</ul>

<?php echo '<div class="alert-error">' . Session::get_flash('error') . '</div>' ?>
<?php echo '<div class="alert-success">' . Session::get_flash('success') . '</div>' ?>

<?php echo Form::open(array('name' => 'login', 'method' => 'post', 'class' => 'form-horizontal')); ?>
<table>
<tr><th align="left">指定した日付から</br>過去１週間の使用電力用が表示されます</th><th></tr></tr>
<tr>
    <th valign="top">
        <?php echo Form::input('oneweekdate', 'oneweekdate', array('type' => 'date')); ?>
        <?php echo Form::submit('submit', '決定', array('class' => 'btn btn-primary')); ?>
    </th>
    <td>
        <ul style="list-style:none;">
            <li><b>使用電力量</b>　　　<?php echo $totalSet1; ?>kwh </li>
            <li><b>最大デマンド値</b>　-kW </li>
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
        <?php echo Form::input('twoweekdate', 'twodaydate', array('type' => 'date')); ?><input id="dummy_button" type="submit" class="btn btn-ptimary">
        <script>
            $("#dummy_button").css("visibility","hidden");
        </script>
    </th>
    <td>
        <ul style="list-style:none;">
            <li><b>使用電力量</b>　　　<?php echo $totalSet2; ?>kwh </li>
            <li><b>最大デマンド値</b>　-kW </li>
            <li><b>CO2排出量</b>　　　-kg-CO2 </li>
            <li><b>電力量料金</b>　　　-円 </li>
        </ul>
    </td>
</tr>
</table>

<?php echo Form::close(); ?>

<div id="chart"></div>
<div id="chart2"></div>

<form method="post" name="weekinfo" id="weekinfo" action="weekinfo" >
    <input type="hidden" id="param_date_1" name="param_date_1" value="">
    <input type="hidden" id="param_date_2" name="param_date_2" value="">

    <ul class="nav nav-tabs" style="border-bottom:none;">
        <li class="nav-item"><a id="weekinfo">詳細表を表示</a></li>
    </ul>
</form>

<script>
    var targetDate1 = <?php echo $targetDate1; ?>;
    var targetDate2 = <?php echo $targetDate2; ?>;
    $('#form_oneweekdate').val(targetDate1);
    $('#form_twoweekdate').val(targetDate2);
    var one_week = <?php echo $oneWeek; ?>;
    var two_week = <?php echo $twoWeek; ?>;

    /* チェックボックス */
    var checked_flg = <?php echo $checkedFlg; ?>;

    $('#weekinfo').click(function(){
        $('#param_date_1').val($('#form_oneweekdate').val());
        $('#param_date_2').val($('#form_twoweekdate').val());

        $('#weekinfo').submit();

    });

    /* チャート表示処理 */
    google.charts.load('current', {'packages': ['corechart']});
    google.setOnLoadCallback(drawChart);
    function drawChart() {
        var options = {
            "title": "週間使用電力量",
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
            seriesType: 'line',
        };
        var data = new google.visualization.arrayToDataTable(one_week);
        var chart = new google.visualization.LineChart(document.getElementById('chart'));
        chart.draw(data, options);

        if (checked_flg) {
            $('input[name="second_graph_flag"]').prop('checked', true);
            var data2 = new google.visualization.arrayToDataTable(two_week);
            var chart2 = new google.visualization.LineChart(document.getElementById('chart2'));
            chart2.draw(data2, options);
        }
    }
</script>