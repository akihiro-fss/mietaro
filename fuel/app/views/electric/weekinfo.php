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
 * 週間の詳細ページ
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


<?php echo Form::open(array('name' => 'weekinfo', 'method' => 'post', 'class' => 'form-horizontal')); ?>
    <table id="electric-data-table-1" class="table table-bordered">
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
        </tr>
    </table>
    <br/><br/>
    <table id="electric-data-table-2" class="table table-bordered">
        <tr>
            <th style="text-align:center;" colspan="4">
                比較対象詳細<br/>
                <input type="date" name="param_date_2" value="param_date_2" id="form_param_date_2" style="width:150px; height:20px">
                <input class="btn btn-primary" name="submit" value="表示" type="submit" id="form_submit">
            </th>
        </tr>
        <tr>
            <th>  日付  </th>
            <th>小計(kWh)</th>
            <th>最大デマンド値(kw)</th>
            <th>発生時刻</th>
        </tr>
    </table>
<?php echo Form::close(); ?>

<script>
    var electricData = <?php echo json_encode($electricData); ?>

    $('#form_param_date_1').val(electricData.param_date_1);
    $('#form_param_date_2').val(electricData.param_date_2);

//電力量テーブル作成
    var oneweekData = electricData.oneweek_date;
    var twoweekData = electricData.twoweek_date;
    var oneweekTotal = electricData.oneweek_total;
    var twoweekTotal = electricData.twoweek_total;
    var emission1 = electricData.total_emission_1;
    var emission2 = electricData.total_emission_2;
    var price1 = electricData.total_price_1;
    var price2 = electricData.total_price_2;

    $.each(oneweekData, function (key, value) {
        $('#electric-data-table-1').append('<tr><td style="width:50px;">' + key + '</td><td>' + value['total'] + '</td><td>' + value['demand_kw'] + '</td><td>' + value['electric_at'] + '</td></tr>');
    });

    $.each(twoweekData, function (key, value) {
        $('#electric-data-table-2').append('<tr><td style="width:50px;">' + key + '</td><td>' + value['total'] + '</td><td>' + value['demand_kw'] + '</td><td>' + value['electric_at'] + '</td></tr>');
    });

    $('#electric-data-table-1').append('<tr><td style="width:90px;">  合計  </td><td>' + oneweekTotal + '</td><<td> - </td><td> - </td></tr>');
    $('#electric-data-table-1').append('<tr><td> CO2排出量 </td><<td>' + emission1 + '</td><td> - </td><td> - </td></tr>');
    $('#electric-data-table-1').append('<tr><td> 原油換算</td><td>' + price1 + '</td><td> - </td><td> - </td></tr>');
    $('#electric-data-table-2').append('<tr><td style="width:90px;">  合計  </td><td>' + twoweekTotal + '</td><<td> - </td><td> - </td></tr>');
    $('#electric-data-table-2').append('<tr><td> CO2排出量 </td><<td>'+ emission2 +'</td><td> - </td><td> - </td></tr>');
    $('#electric-data-table-2').append('<tr><td> 原油換算</td><td>' + price2 + '</td><td> - </td><td> - </td></tr>');

</script>