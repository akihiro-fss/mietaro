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
 * 月間グラフ表示画面
 * @package app
 * @extends Views
 */
?>

<?php
$dataArray = Model_Electric::monthdaydata();
$month = json_encode($dataArray['month_data']);
$targetDate1 = json_encode($dataArray['target_date_1']);
$targetDate2 = json_encode($dataArray['target_date_2']);
$checkedFlg = json_encode($dataArray['checked_flg']);
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

<?php echo Form::open(array('name' => 'search', 'method' => 'post', 'class' => 'form-horizontal')); ?>
<table>
<tr><th align="left">指定した日付の月の</br>使用電力情報が表示されます</th><th></tr></tr>
<tr>
    <th valign="top">
        <?php echo Form::input('onemonthdate', 'onemonthdate', array('type' => 'date')); ?>
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
        <?php echo Form::input('twomonthdate', 'twomonthdate', array('type' => 'date')); ?><input id="dummy_button" type="submit" class="btn btn-ptimary">
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

<form method="post" name="monthinfo" id="monthinfo" action="monthinfo" >
    <input type="hidden" id="param_date_1" name="param_date_1" value="">
    <input type="hidden" id="param_date_2" name="param_date_2" value="">

    <ul class="nav nav-tabs" style="border-bottom:none;">
        <li class="nav-item"><a href="sample">デマンドグラフを表示</a></li>
        <li class="nav-item"><a id="monthinfo">詳細表を表示</a></li>
    </ul>
</form>

<script>
    var targetDate1 = <?php echo $targetDate1; ?>;
    var targetDate2 = <?php echo $targetDate2; ?>;
    $('#form_onemonthdate').val(targetDate1);
    $('#form_twomonthdate').val(targetDate2);
    var month = <?php echo $month; ?>;

    /* チェックボックス */
    var checked_flg = <?php echo $checkedFlg; ?>;

    $('#monthinfo').click(function(){
        $('#param_date_1').val($('#form_onemonthdate').val());
        $('#param_date_2').val($('#form_twomonthdate').val());

        $('#monthinfo').submit();

    });

    if(checked_flg){
        //チェックフラグのデフォルト設定
        $('input[name="second_graph_flag"]').prop('checked', true);
        /* チャート表示処理 */
        google.charts.load('current', {'packages': ['corechart']});
        google.setOnLoadCallback(drawChart);
        function drawChart() {
            var data = new google.visualization.arrayToDataTable(month);
            var options = {
                "title": "月間使用電力量",
                "titleTextStyle": {
                    "fontSize": 20
                },
                "vAxis": {
                    title: 'kw/h',
                },
                hAxis: {
                    title: 'day'
                },
                "width": 900,
                "height": 600,
                seriesType: 'line',
                series: {0: {type: 'bars'}}
            };
            var chart = new google.visualization.ComboChart(document.getElementById('chart'));
            chart.draw(data, options);
        }
    }else{
        /* チャート表示処理 */
        google.charts.load('current', {'packages': ['corechart']});
        google.setOnLoadCallback(drawChart);
        function drawChart() {
            var data = new google.visualization.arrayToDataTable(month);
            var options = {
                "title": "月間使用電力量",
                "titleTextStyle": {
                    "fontSize": 20
                },
                "vAxis": {
                    title: 'kw/h',
                },
                hAxis: {
                    title: 'day'
                },
                "width": 900,
                "height": 600,
            };
            var chart = new google.visualization.ColumnChart(document.getElementById('chart'));
            chart.draw(data, options);
    		}
    }
</script>