<?php
/**
 *
 * 作成日：2017/1/11
 * 更新日：2017/1/23
 * 作成者：丸山　隼
 * 更新者：丸山　隼
 *
 */
/**
 * The Top Electric.
 *
 * 年間の詳細表示ページ
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

<?php echo Form::open(array('name' => 'yearinfo', 'method' => 'post', 'class' => 'form-horizontal')); ?>
    <table id="electric-data-table" class="table table-bordered">
        <tr>
            <th style="text-align:center;" colspan="6">
                メイン詳細</br>
                <input type="date" name="param_date_1" value="param_date_1" id="form_param_date_1" style="width:150px; height:20px">
                <input class="btn btn-primary" name="submit" value="表示" type="submit" id="form_submit">
            </th>
            <th style="text-align:center;" colspan="5">
                比較対象詳細</br>
                <input type="date" name="param_date_2" value="param_date_2" id="form_param_date_2" style="width:150px; height:20px">
                <input class="btn btn-primary" name="submit" value="表示" type="submit" id="form_submit">
            </th>
            <th colspan="3" style="text-align:center;">比較表</th>
        </tr>
        <tr>
            <th>  月 次  </th>
            <th>小計(kWh)</th>
            <th>最大デマンド値(kW)</th>
            <th>発生時刻</th>
            <th>気温(℃)</th>
            <th>湿度(%)</th>
            <th>小計(kWh)</th>
            <th>最大デマンド値(kW)</th>
            <th>発生時刻</th>
            <th>気温(℃)</th>
            <th>湿度(%)</th>
            <th>使用電力量(kwh)</th>
            <th>比率(％)</th>
        </tr>
    </table>
<?php echo Form::close(); ?>


<script>
    var electricData = <?php echo json_encode($electricData); ?>

    $('#form_param_date_1').val(electricData.param_date_1);
    $('#form_param_date_2').val(electricData.param_date_2);

//電力量テーブル作成
    var oneyearData = electricData.oneyear_date;
    var twoyearData = electricData.twoyear_date;
    var oneyearTotal = electricData.oneyear_total;
    var twoyearTotal = electricData.twoyear_total;
    var diffTotal = 0;

    $.each(oneyearData, function (key, value) {
        var diff = (value - twoyearData[key]);
        diffTotal += diff;
        var diffStr = '';
        if (diff > 0) {
            diffStr = '<td> -' + diff + '</td>';
        } else if (diff < 0) {
            diffStr = '<td> ' + diff + '</td>';
        } else {
            diffStr = '<td>' + diff + '</td>';
        }

        $('#electric-data-table').append('<tr><td style="width:50px;">' + key + '</td><td>' + value + '</td><td> - </td><td> - </td><td> - </td><td> - </td><td>' + twoyearData[key] + '</td><td> - </td><td> - </td><td> - </td><td> - </td>' + diffStr + '<td> - </td></tr>');
    });
    var diffTotalStr = '';
    if (diffTotalStr > 0) {
        diffTotalStr = '<td> -' + diffTotal + '</td>';
    } else if (diffTotalStr < 0) {
        diffTotalStr = '<td> ' + diffTotal + '</td>';
    } else {
        diffTotalStr = '<td>' + diffTotal + '</td>';
    }
    $('#electric-data-table').append('<tr><td style="width:50px;">  合計  </td><td>' + oneyearTotal + '</td><td> - </td><td>  </td><td> - </td><td> - </td><td>' + twoyearTotal + '</td><td> - </td><td>  </td><td> - </td><td> - </td>' + diffTotalStr + '<td> - </td></tr>');
    $('#electric-data-table').append('<tr><td style="width:50px;"> CO2排出量 </td><td colspan="5"> - <td colspan="5"> - </td><td colspan="2">-</td></tr>');
    $('#electric-data-table').append('<tr><td style="width:50px;"> 原油換算</td><td colspan="5"> - <td colspan="5"> - </td><td colspan="2">-</td></tr>');

</script>