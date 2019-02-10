<?php
/**
 *
 * 作成日：2018/08/14
 * 更新日：2018/08/15
 * 作成者：戸田滉洋
 * 更新者：戸田滉洋
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
	<li class="nav-item"><a href="sample">気温グラフを表示</a></li>
	<li class="nav-item"><a id="oneday">電力グラフを表示</a></li>
	<li class="nav-item"><a id="onedayinfo">詳細表を表示</a></li>
</ul>

ピンポイント天気予報　※事業所周辺の天気予報
<table id="weather_table" class="table table-bordered">
    <tr id="weather_date"><th>日付</th></tr>
    <tr id="weather_time"><th>時間</th></tr>
    <tr id="weather_info"><th>天気</th></tr>
    <tr id="weather_temp"><th>気温（℃）</th></tr>
    <tr id="weather_rain"><th>降水量</br>(mm/h)</th></tr>
</table>

<div class="form-group">
    <label for="comment">Comment:</label>
    <?php echo '<div id="alert_error" class="alert-error">' . Session::get_flash('error') . '</div>' ?>
    <?php echo '<div id="alert_success" class="alert-success">' . Session::get_flash('success') . '</div>' ?>
    <textarea id="comment" class="form-control" rows="5" style="width:800px;"></textarea></br>
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


    $('#form_onedaydate').val(targetDate1);
    $('#form_twodaydate').val(targetDate2);

  //詳細ページに遷移
    $('#onedayinfo').click(function () {
       var param1 = $('#form_onedaydate').val();
       var param2 = $('#form_twodaydate').val();
       var data={'param_date_1':param1,'param_date_2':param2};
       postForm('onedayinfo',data);
    });

    //電力量ページに遷移
    $('#oneday').click(function () {
        var param1 = $('#form_onedaydate').val();
        var param2 = $('#form_twodaydate').val();
        var param3 = checked_flg;
        var data={'param_date_1':param1,'param_date_2':param2,'second_graph_flag':param3};
        postForm('oneday',data);
    });


    if (checked_flg) {
        //チェックフラグのデフォルト設定
        $('input[name="second_graph_flag"]').prop('checked', true);
        //チャート表示処理
        var chartdata = convertArray(oneday_demand, yesterday_demand, targetDate1, targetDate2, checked_flg);
        google.charts.load('current', {'packages': ['corechart']});
        google.setOnLoadCallback(drawChart);
        function drawChart() {
            var data = new google.visualization.arrayToDataTable(chartdata);
            var options = {
                "title": "デマンドグラフ",
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
                seriesType: 'line',
                series: {1: {type: 'bars'}}
            };
            var chart = new google.visualization.ComboChart(document.getElementById('chart'));
            chart.draw(data, options);
        }
    } else {
        var chartdata = convertArray(oneday_demand, yesterday_demand, targetDate1, targetDate2, checked_flg);
        google.charts.load('current', {'packages': ['corechart']});
        google.setOnLoadCallback(drawChart);
        function drawChart() {
            var data = new google.visualization.arrayToDataTable(chartdata);
            var options = {
                "title": "デマンドグラフ",
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
            var chart = new google.visualization.ColumnChart(document.getElementById('chart'));
            chart.draw(data, options);
        }
    }

    /* 天気予報表示処理 */
    getWeatherInfo(strDataArray.latitude, strDataArray.longitude);

    /* 当日と前日のデータをマージ */
    function convertArray(oneday_demand, yesterday_demand, targetDate1, targetDate2, checked_flg) {
        var arrayData = [];
        if (yesterday_demand.length > 0) {
            $.each(oneday_demand,
                    function (index, data) {
                        if (index == 0) {
                            arrayData.push(["", targetDate2, targetDate1])
                        } else {
                            arrayData.push([oneday_demand[index][0], yesterday_demand[index][1], oneday_demand[index][1]]);
                        }
                    }
            );
        } else {
            if (checked_flg) {
                $.each(oneday_demand,
                        function (index, data) {
                            if (index == 0) {
                                arrayData.push(["", targetDate2, targetDate1])
                            } else {
                                arrayData.push([oneday_demand[index][0], 0, oneday_demand[index][1]]);
                            }
                        }
                );
            } else {
                $.each(oneday_demand,
                        function (index, data) {
                            if (index == 0) {
                                arrayData.push(["", targetDate1])
                            } else {
                                arrayData.push([oneday_demand[index][0], oneday_demand[index][1]]);
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

    //天気予報取得メソッド
    function getWeatherInfo(latitude, longitude) {
        $.ajax({
            url: "https://api.openweathermap.org/data/2.5/forecast?lat=" + latitude + "&lon=" + longitude + "&units=metric&appid=1a91ac37fb0b64e5fbd1ad9ccc94b87b",
        }).fail(function () {
            // エラー処理
            console.log('天気情報の取得に失敗');
        }).done(function (res) {
            // 成功処理
            viewsWeatherInfo(res);
        });
    }

    //天気予報表示処理
    function viewsWeatherInfo(data) {
        //天気予報情報配列
        var list = data.list;
        var result = [];

        var imgUrlBase = "http://openweathermap.org/img/w/";
        var extension = ".png";
        var counter = 0;

        //現在時間取得
        var nowTime = new Date();
        var tempTime = new Date(list[0]['dt_txt']);

        //表示する予報情報分だけ取得する
        $.each(list, function (index, data) {
            if (counter < 8) {
                if (nowTime < new Date(data['dt_txt'])) {
                    result.push(data);
                    counter++;
                }
            }
        });

        //テーブルへの表示処理
        var weekArray = ['（日）', '(月)', '（火）', '（水）', '（木）', '（金）', '（土）'];

        tmpOneDate = new Date(result[0]['dt_txt']);
        tmpTwoDate = new Date(result[7]['dt_txt']);
        calcOneDate = '';
        calcTwoDate = '';
        oneMonth = '';
        twoMonth = '';
        oneDate = '';
        twoDate = '';
        oneWeek = '';
        twoWeek = '';
        oneColspan = 0;
        twoColspan = 0;

        if (tmpOneDate.getDate() == tmpTwoDate.getDate()) {
            calcFlg = false;
            var oneMonth = tmpOneDate.getMonth() + 1;//月
            var oneDate = tmpOneDate.getDate();//日
            var oneWeek = weekArray[tmpOneDate.getDay()];//曜日
        } else {
            calcFlg = true;
            calcOneDate = tmpOneDate.getDate();
            calcTwoDate = tmpTwoDate.getDate();

            var oneMonth = tmpOneDate.getMonth() + 1;//月
            var oneDate = tmpOneDate.getDate();//日
            var oneWeek = weekArray[tmpOneDate.getDay()];//曜日
            var twoMonth = tmpTwoDate.getMonth() + 1;//月
            var twoDate = tmpTwoDate.getDate();//日
            var twoWeek = weekArray[tmpTwoDate.getDay()];//曜日
        }

        $.each(result, function (index, data) {
            /* テーブル表示データ作成 */
            var dateTime = new Date(data['dt_txt']);

            var date = dateTime.getDate();
            if (calcFlg) {
                if (date == calcOneDate) {
                    oneColspan++;
                } else if (date == calcTwoDate) {
                    twoColspan++;
                }
            }

            //時間だけ抜き取る
            var hours = dateTime.getHours();
            //天気アイコン
            var weatherIconUrl = imgUrlBase + data['weather'][0]['icon'] + extension;
            //気温
            var temp = data['main']['temp'];
            //降水量
            var rain = data['rain'];
            //降水量０もしくは、APiからのレスポンスに降水量が含まれていない場合は０をセット
            if (!rain || Object.keys(rain).length == 0) {
                rain = 0;
            } else {
                rain = Math.round(rain['3h']);
            }
            /* テーブル作成 */
            $('#weather_time').append('<td>' + hours + '</td>');
            $('#weather_info').append('<td><img src="' + weatherIconUrl + '"></td>');
            $('#weather_temp').append('<td>' + Math.floor(temp) + '</td>');
            $('#weather_rain').append('<td>' + rain + '</td>');
        });
        //日付の行だけ別処理
        if (calcFlg) {
            $('#weather_date').append('<td colspan="' + oneColspan + '">' + oneMonth + '/' + oneDate + '</br>' + oneWeek + '</td>');
            $('#weather_date').append('<td colspan="' + twoColspan + '">' + twoMonth + '/' + twoDate + '</br>' + twoWeek + '</td>');
        } else {
            $('#weather_date').append('<td colspan="8">' + oneMonth + '/' + oneDate + '</br>' + oneWeek + '</td>');
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