<?php
/**
 *
 * 作成日：2017/1/12
 * 更新日：2017/1/23
 * 作成者：丸山　隼
 * 更新者：戸田滉洋
 *
 */
/**
 * The Top Electric.
 *
 * 月間グラフの詳細表示
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

<?php echo Form::open(array('name' => 'monthinfo', 'method' => 'post', 'class' => 'form-horizontal')); ?>
    <table id="electric-data-table" class="table table-bordered">
        <tr>
            <th style="text-align:center;" colspan="4">
                メイン詳細<br/>
                <input type="date" name="param_date_1" value="param_date_1" id="form_param_date_1" style="width:150px; height:20px">
                <input class="btn btn-primary" name="submit" value="表示" type="submit" id="form_submit">
            </th>
        </tr>
        <tr>
            <th>  日付  </th>
            <th>小計(kWh)</th>
            <th>最大デマンド値(kw)</th>
            <th>発生時刻</th>
            <th>気温(℃)</th>
            <th>湿度(%)</th>
        </tr>
    </table>
<?php echo Form::close(); ?>
<?php echo Html::anchor('https://darksky.net/poweredby/', 'Powered by Dark Sky'); ?>
<script>
    var electricData = <?php echo json_encode($electricData); ?>

    $('#form_param_date_1').val(electricData.param_date_1);

//電力量テーブル作成
    var onemonthData = electricData.onemonth_date;
    var onemonthTotal = electricData.onemonth_total;
    var emission1 = electricData.total_emission;
    var price1 = electricData.total_emission;

    $.each(onemonthData, function (key, value) {
        $('#electric-data-table').append('<tr><td style="width:50px;">' + key + '</td><td>' + value[0] + '</td><td>' + value[1] + '</td><td>' + value[2] + '</td><td>' + value['temperature'] + '</td><td>' + value['humidity'] + ' </td></tr>');
    });

    $('#electric-data-table').append('<tr><td style="width:90px;">  合計  </td><td>' + onemonthTotal + '</td><td> - </td><td> - </td><td> - </td><td> - </td></tr>');
    $('#electric-data-table').append('<tr><td> CO2排出量 </td><td>' + emission1 + '</td><td> - </td><td> - </td><td> - </td><td> - </td></tr>');
    $('#electric-data-table').append('<tr><td> 原油換算</td><td>' + price1 + '</td><td> - </td><td> - </td><td> - </td><td> - </td></tr>');

</script>