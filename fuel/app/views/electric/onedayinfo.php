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
 * 日時の詳細表示ページ
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

<?php echo Form::open(array('name' => 'onedayinfo', 'method' => 'post', 'class' => 'form-horizontal')); ?>
    <table id="electric-data-table" class="table table-bordered">
        <tr>
            <th></th>
            <th style="text-align:center;" colspan="5">
                メイン詳細<br/>
                <input type="date" name="param_date_1" value="param_date_1" id="form_param_date_1" style="width:150px; height:20px">
                <input class="btn btn-primary" name="submit" value="表示" type="submit" id="form_submit">
            </th>
            <th style="text-align:center;" colspan="5">
                比較対象詳細<br/>
                <input type="date" name="param_date_2" value="param_date_2" id="form_param_date_2" style="width:150px; height:20px">
                <input class="btn btn-primary" name="submit" value="表示" type="submit" id="form_submit">
            </th>
            <th colspan="3" style="text-align:center;">比較表</th>
        </tr>
        <tr>
            <th>  時 間  </th>
            <th>使用電力量(kWh)</th>
            <th>電力量料金(円)</th>
            <th>デマンド(kW)</th>
            <th>気温(℃)</th>
            <th>湿度(%)</th>
            <th>使用電力量(kwh)</th>
            <th>電力量料金(円)</th>
            <th>デマンド(kwh)</th>
            <th>気温(℃)</th>
            <th>湿度(%)</th>
            <th>使用電力量(kwh)</th>
            <th>電力量料金(円)</th>
            <th>比率(％)</th>
        </tr>
        <tr><td>0:00~</td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>
    </table>
<?php echo Form::close(); ?>
<?php echo Html::anchor('https://darksky.net/poweredby/', 'Powered by Dark Sky'); ?>
<script>
    var electricData = <?php echo json_encode($electricData); ?>

    $('#form_param_date_1').val(electricData.param_date_1);
    $('#form_param_date_2').val(electricData.param_date_2);

//電力量テーブル作成
    var onedayData = electricData.oneday_date;
    var twodayData = electricData.twoday_date;
    var onedayTotal = electricData.oneday_total;
    var twodayTotal = electricData.twoday_total;
    var emission1 = electricData.total_emission_1;
    var emission2 = electricData.total_emission_2;
    var price1 = electricData.total_price_1;
    var price2 = electricData.total_price_2;
    var cf = electricData.conversion_factor;
    var diffTotal = 0;
    var totalPrice1 = 0;
    var totalPrice2 = 0;

    $.each(onedayData, function (key, value) {
        //時間
        var time = key;
        //メイン詳細-使用電力量
        var electric1 = value[0];
        //メイン詳細-電力量料金
        var price1 = value[0] * cf;
        totalPrice1 += price1;
        //メイン詳細-デマンド
        var demand1 = value[1];
        //メイン詳細-気温
        var temperature1 = value['temperature'];
        //メイン詳細-湿度
        var humidity1 = value['humidity'];
        //比較対象詳細-使用電力量
        var electric2 =  twodayData[key][0];
        //比較対象詳細-電力量料金
        var price2 = twodayData[key][0] * cf;
        totalPrice2 += price2;
        //比較対象詳細-デマンド
        var demand2 = twodayData[key][1];
        //比較対象詳細-気温
        var temperature2 = twodayData[key]['temperature'];
        //比較対象詳細-湿度
        var humidity2 = twodayData[key]['humidity'];
        //比較表-使用電力量
        var diffElectric = parseInt(electric2)-parseInt(electric1);
        var diffElectricStr = diffElectric;
        if (diffElectric > 0) {
        	diffElectricStr = '+' + diffElectric;
        }
        //比較表-電力量料金
        var diffPrice = diffElectric * cf;
        var diffPriceStr = diffPrice;
        if (diffPrice > 0) {
        	diffPriceStr = '+' + diffPrice;
        }
        //比較表-比率
        if(electric2 != 0){
            var diffPercent = Math.round((electric1 / electric2 * 100)-100);
            var diffPercentStr = '';
            if(isNaN(diffPercent)){
            	diffPercentStr = '%';
            }else{
            	diffPercentStr = diffPercent + '%';
            }
        }else{
        	var diffPercentStr = '%';
        }
        $('#electric-data-table').append('<tr><td style="width:50px;">' + time + '</td><td>' + electric1 + '</td><td>' + price1 + '</td><td>' + demand1 + '</td><td>' + temperature1 +'</td><td>'+ humidity1 +'</td><td>' + electric2 + '</td><td>' + price2 + '</td><td>' + demand2 + '</td><td>' + temperature2 + '</td><td>' + humidity2 + '</td><td>' + diffElectricStr + '</td><td>' + diffPriceStr + '</td><td>' + diffPercentStr + '</td></tr>');
    });
    //比較表-合計-使用電力量
    var diffTotalElectric = parseInt(twodayTotal - onedayTotal);
    var diffTotalElectricStr = diffTotalElectric;
    if(diffTotalElectric > 0){
    	diffTotalElectricStr = '+' + diffTotalElectric;
    }
    //比較表-合計-電力量料金
    var diffTotalPrice = parseInt(totalPrice2 - totalPrice1);
    var diffTotalPriceStr = diffTotalElectric;
    if(diffTotalPrice > 0){
    	diffTotalPriceStr = '+' + diffTotalPrice;
    }
    //比較表-合計-比率

    if(twodayTotal != 0){
        var diffTotalPercent = Math.round((onedayTotal / twodayTotal * 100)-100);
        var diffTotalPercentStr = '';
        if(isNaN(diffTotalPercent)){
            diffTotalPercentStr = '%';
        }else{
        	diffTotalPercentStr = diffTotalPercent + '%';
        }
    }else{
     	diffTotalPercentStr = '%';
    }

    $('#electric-data-table').append('<tr><td style="width:50px;">  合計  </td><td>' + onedayTotal + '</td><td>' + totalPrice1 + '</td><td> - </td><td> - </td><td> - </td><td>' + twodayTotal + '</td><td>' + totalPrice2 + '</td><td> - </td><td> - </td><td> - </td><td>'+ diffTotalElectricStr +'</td><td>' + diffTotalPriceStr + '</td><td>' + diffTotalPercentStr + '</td></tr>');
    $('#electric-data-table').append('<tr><td style="width:50px;"> CO2排出量 </td><td colspan="5">' + emission1 + '<td colspan="5">' + emission2 + ' </td><td colspan="3"></td></tr>');
    $('#electric-data-table').append('<tr><td style="width:50px;"> 原油換算</td><td colspan="5">' + price1 + '<td colspan="5">' + price2 + '</td><td colspan="3"></td></tr>');

</script>