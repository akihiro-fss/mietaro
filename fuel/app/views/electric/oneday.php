<?php
/**
 *
 * 作成日：2017/8/11
 * 更新日：2018/08/19
 * 作成者：戸田滉洋
 * 更新者：丸山　隼
 *
 * The Top Electric.
 *
 * 日時データグラフ及びコメント追加画面
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
    <tr><th align="left">表示したい日付を指定してください</th></tr>
<tr>
    <th valign="top">
        <?php echo Form::input('onedaydate', 'onedaydate', array('type' => 'date')); ?>
        <?php echo Form::submit('submit', '決定', array('class' => 'btn btn-primary')); ?>
    </th>
    <td>
        <ul style="list-style:none;">
            <li><b>使用電力量</b>　　　<span id="total_set_1"></span>kwh </li>
            <li><b>最大デマンド値</b> 　<span id="max_demand_1"></span>kW </li>
            <li><b>CO2排出量</b>　　　<span id="total_emission_1"></span>kg-CO2 </li>
            <li><b>電力量料金</b>　　　<span id="total_price_1"></span>円 </li>
        </ul>
    </td>
</tr>
<tr>
    <th align="left"><p><input name="second_graph_flag" id="second_graph_flag" type = "checkbox" value="1">比較用グラフを表示する</p></th><td></td>
</tr>
<tr>
    <th valign="top">
        <?php echo Form::input('twodaydate', 'twodaydate', array('type' => 'date')); ?><input id="dummy_button" type="submit" class="btn btn-ptimary">
        <script>
            $("#dummy_button").css("visibility", "hidden");
        </script>
    </th>
    <td>
        <ul style="list-style:none;">
            <li><b>使用電力量</b>　　　<span id="total_set_2"></span>kwh </li>
            <li><b>最大デマンド値</b> 　<span id="max_demand_2"></span>kW </li>
            <li><b>CO2排出量</b>　　　<span id="total_emission_2"></span>kg-CO2 </li>
            <li><b>電力量料金</b>　　　<span id="total_price_2"></span>円 </li>
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
	<li class="nav-item"><a id="temperture_graph">気温グラフを表示</a></li>
	<li class="nav-item"><a id="onedaydemand">デマンドグラフを表示</a></li>
	<li class="nav-item"><a id="onedayinfo">詳細表を表示</a></li>
</ul>

ピンポイント天気予報　※事業所周辺の天気予報
<table id="weather_table" class="table table-bordered">
    <tr id="weather_date"><th>日付</th></tr>
    <tr id="weather_time"><th>時間</th></tr>
    <tr id="weather_info"><th>天気</th></tr>
    <tr id="weather_temp"><th>気温（℃）</th></tr>
    <tr id="weather_rain"><th>降水量<br/>(mm/h)</th></tr>
</table>
<div id="temperture_chart"></div>
<input type="hidden" id="temperture_chart_status" value=0>

<div class="form-group">
    <label for="comment">Comment:</label>
    <?php echo '<div id="alert_error" class="alert-error">' . Session::get_flash('error') . '</div>' ?>
    <?php echo '<div id="alert_success" class="alert-success">' . Session::get_flash('success') . '</div>' ?>
    <textarea id="comment" class="form-control" rows="5" style="width:800px;"></textarea><br/>
    <input id="comment_button" class="btn btn-primary" type="submit" value="記録">
</div>

<script>
    var onedayData = <?php echo json_encode($onedayData); ?>;
    var oneday = onedayData['oneday'];
    var yesterday = onedayData['yesterday'];
    var oneday_demand = onedayData['oneday_demand'];
    var yesterday_demand = onedayData['yesterday_demand'];
    var total1 = onedayData['total_set_1'];
    var total2 = onedayData['total_set_2'];
    var max1 = onedayData['max_demand_1'];
    var max2 = onedayData['max_demand_2'];
    var emission1 = onedayData['total_emission_1'];
    var emission2 = onedayData['total_emission_2'];
    var price1 = onedayData['total_price_1'];
    var price2 = onedayData['total_price_2'];
    var checked_flg = onedayData['checked_flg'];
    var targetDate1 = onedayData['target_date_1'];
    var targetDate2 = onedayData['target_date_2'];
    var str_id = onedayData['str_id'];
    var strDataArray = onedayData['str_data_array'];
    var weatherInfoTableData = onedayData['weather_info']['weatherinfotabledata'];
    var weatherInfoGraphData = onedayData['weather_info']['weatherinfographdata'];

    //電力量合計値セット
    $('#total_set_1').append(total1);
    $('#total_set_2').append(total2);
    //デマンド最大値セット
    $('#max_demand_1').append(max1);
    $('#max_demand_2').append(max2);
    //CO2排出量セット
    $('#total_emission_1').append(emission1);
    $('#total_emission_2').append(emission2);
    //電力量料金セット
    $('#total_price_1').append(price1);
    $('#total_price_2').append(price2);
    //日付フォームセット
    $('#form_onedaydate').val(targetDate1);
    $('#form_twodaydate').val(targetDate2);
    //気温グラフはデフォルトでは非表示
    $('#temperture_chart').hide();

    //詳細ページに遷移
    $('#onedayinfo').click(function () {
       var param1 = $('#form_onedaydate').val();
       var param2 = $('#form_twodaydate').val();
       var data={'param_date_1':param1,'param_date_2':param2};
       postForm('onedayinfo',data);
    });

    //デマンドページに遷移
    $('#onedaydemand').click(function () {
        var param1 = $('#form_onedaydate').val();
        var param2 = $('#form_twodaydate').val();
        var param3 = checked_flg;
        var data={'param_date_1':param1,'param_date_2':param2,'second_graph_flag':param3};
        postForm('onedaydemand',data);
    });

    if (checked_flg) {
        //チェックフラグのデフォルト設定
        $('input[name="second_graph_flag"]').prop('checked', true);
        //チャート表示処理
        var chartdata = convertArray(oneday, yesterday, targetDate1, targetDate2, checked_flg);
        google.charts.load('current', {'packages': ['corechart']});
        google.setOnLoadCallback(drawChart);
        function drawChart() {
            var data = new google.visualization.arrayToDataTable(chartdata);
            var options = {
                "title": "使用電力量",
                "titleTextStyle": {"fontSize": 20},
                "vAxis": {title: 'kw/h',},
                "hAxis": {title: 'hour'},
                "width": 900,
                "height": 600,
                seriesType: 'line',
                series: {1: {type: 'bars'}}
            };
            var chart = new google.visualization.ComboChart(document.getElementById('chart'));
            chart.draw(data, options);
        }
    } else {
        var chartdata = convertArray(oneday, yesterday, targetDate1, targetDate2, checked_flg);
        google.charts.load('current', {'packages': ['corechart']});
        google.setOnLoadCallback(drawChart);
        function drawChart() {
            var data = new google.visualization.arrayToDataTable(chartdata);
            var options = {
                "title": "使用電力量",
                "titleTextStyle": {"fontSize": 20},
                "vAxis": {title: 'kw/h'},
                "hAxis": {title: 'hour'},
                "width": 900,
                "height": 600,
            };
            var chart = new google.visualization.ColumnChart(document.getElementById('chart'));
            chart.draw(data, options);
        }
    }

    /* 天気予報表示処理 */
    displayWeatherInfo(weatherInfoTableData,weatherInfoGraphData);

    /* 当日と前日のデータをマージ */
    function convertArray(oneday, yesterday, targetDate1, targetDate2, checked_flg) {
        var arrayData = [];
        if (yesterday.length > 0) {
            $.each(oneday,
                    function (index, data) {
                        if (index == 0) {
                            arrayData.push(["", targetDate2, targetDate1])
                        } else {
                            arrayData.push([oneday[index][0], yesterday[index][1], oneday[index][1]]);
                        }
                    }
            );
        } else {
            if (checked_flg) {
                $.each(oneday,
                        function (index, data) {
                            if (index == 0) {
                                arrayData.push(["", targetDate2, targetDate1])
                            } else {
                                arrayData.push([oneday[index][0], 0, oneday[index][1]]);
                            }
                        }
                );
            } else {
                $.each(oneday,
                        function (index, data) {
                            if (index == 0) {
                                arrayData.push(["", targetDate1])
                            } else {
                                arrayData.push([oneday[index][0], oneday[index][1]]);
                            }
                        }
                );
            }
        }
        return arrayData;
    }

    /* onloadでコメントを取得 */
    getOnedayComment(str_id, targetDate1);

    /* 記録ボタンが押下された時 */
    $('#comment_button').click(function (e) {
        var comment = $('#comment').val();
        addOnedayComment(str_id, targetDate1, comment);
    });

    /* 気温グラフ表示・非表示ボタンが押された時の動作 */
    $('#temperture_graph').click(function(e){
        //現在の表示ステータス取得
        var tgs = $('#temperture_chart_status').val();
        //表示・非表示処理
        if(tgs == 0){
            $('#temperture_chart').show();
            $('#temperture_chart_status').val(1);
            $('#temperture_graph').text('気温グラフを非表示');
        }else{
            $('#temperture_chart').hide();
            $('#temperture_chart_status').val(0);
            $('#temperture_graph').text('気温グラフを表示');
        }
    });

    /* コメント取得 */
    function getOnedayComment(str_id, target_date) {
        $.ajax({
            type: 'POST',
            url: '/mietaro/public/Electric/oneDay/getOnedayComment',
            data: {
                "target_date": target_date,
                "str_id": str_id
            }
        }).fail(function () {
            // エラー処理
            console.log('コメントの取得に失敗');
        }).done(function (res) {
            // 成功処理
            var res = $.parseJSON(res)
            $('#comment').val("");
            $('#comment').val(res.comment);

        });
    }

    /* コメント追加 */
    function addOnedayComment(str_id, target_date, comment) {
        $.ajax({
            type: 'POST',
            url: '/mietaro/public/Electric/oneDay/addOnedayComment',
            data: {
                "str_id": str_id,
                "target_date": target_date,
                "comment": comment
            }
        }).fail(function () {
            // エラー処理
            console.log('コメントの追加に失敗');
            $('#alert_error').html('コメントの更新に失敗しました');
        }).done(function (res) {
            // 成功処理
            $('#comment').val("");
            $('#comment').val(res);
            $('#alert_success').html('コメントを更新しました');
        });
    }

    //天気予報表示処理v2
    function  displayWeatherInfo(weatherInfoTableData,weatherInfoGraphData){
        //天気予報表の作成
        var onecolspan = 0;
        var twocolspan = 0;
        //日付要素の結合フラグ
        var mergeFlg = false;
        //最初の要素
        var firstelement = weatherInfoTableData[0];
        //最後の要素
        var endelement = weatherInfoTableData[weatherInfoTableData.length-1];
        if(firstelement['date'] != endelement['date']){
        	mergeFlg = true;
        }
        $.each(weatherInfoTableData, function (index, data) {
            //時間
            var hour = data['hour'];
            //天気アイコン
            var weatherIconUrl = data['icon_info'];
            //気温
            var temperture = data['temperture'];
            //降水量
            var rain = data['rain'];
            //テーブル作成
            $('#weather_time').append('<td>' + hour + '</td>');
            $('#weather_info').append('<td><img src="' + weatherIconUrl + '"></td>');
            $('#weather_temp').append('<td>' + temperture + '</td>');
            $('#weather_rain').append('<td>' + rain + '</td>');
            //日付要素を結合する必要がある場合の処理
            if(mergeFlg){
                if(data['date'] == firstelement['date']){
                	onecolspan++;
                }else if(data['date'] == endelement['date']){
                	twocolspan++;
                }
            }
        });
        //日付行だけ別処理
        if(mergeFlg){
            $('#weather_date').append('<td style="text-align:center;" colspan="' + onecolspan + '">' + firstelement['date'] + '</br>(' + firstelement['week'] + ')</td>');
            $('#weather_date').append('<td style="text-align:center;" colspan="' + twocolspan + '">' + endelement['date'] + '</br>(' + endelement['week'] + ')</td>');
        }else{
           $('#weather_date').append('<td style="text-align:center;" colspan="8">' + firstelement['date'] + '</br>(' + firstelement['week'] + ')</td>');
        }

        //気温グラフを作成(googlechart)
        chartdata_temperture = weatherInfoGraphData;
        google.charts.load('current', {'packages': ['corechart']});
        google.setOnLoadCallback(drawChart_temperture);
        	function drawChart_temperture() {
            	var data_temperture = new google.visualization.arrayToDataTable(chartdata_temperture);
	            var options_temperture = {
	                "title": "気温グラフ",
	                "titleTextStyle": {"fontSize": 20},
	                "vAxis": {title: '℃',},
	                "hAxis": {title: '時'},
	                "width": 900,
	                "height": 600,
	                seriesType: 'line',
	                series: {1: {type: 'bars'}}
	            };
	            var chart_temperture = new google.visualization.ComboChart(document.getElementById('temperture_chart'));
	            chart_temperture.draw(data_temperture, options_temperture);
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