<?php
/**
 *
 * 作成日：2017/8/11
 * 更新日：2017/12/30
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

<?php
$dataArray = Model_Electric::onedaydata();
$strId = json_encode($dataArray['str_id']);
$strDataArray = json_encode($dataArray['str_data_array']);
$checkedFlg = json_encode($dataArray['checked_flg']);
$totalSet1 = json_encode($dataArray['total_set_1']);
$totalSet2 = json_encode($dataArray['total_set_2']);
$targetDate1 = json_encode($dataArray['target_date_1']);
$targetDate2 = json_encode($dataArray['target_date_2']);
$oneday = json_encode($dataArray['oneday']);
$yesterday = json_encode($dataArray['yesterday']);
?>

<h3><?php echo Session::get_flash('success', 'ようこそ' . Auth::get_screen_name() . 'さん'); ?></h3>
<ul class="nav nav-tabs">
    <li class="nav-item"><a href="oneDay">1日</a></li>
    <li class="nav-item"><a href="week">週間</a></li>
    <li class="nav-item"><a href="month">月間</a></li>
    <li class="nav-item"><a href="year">年間</a></li>
    <li class="nav-item"><a href="sample">分析用</a></li>
</ul>

<?php echo Form::open(array('name' => 'search', 'method' => 'post', 'class' => 'form-horizontal')); ?>
<table>
    <tr><th align="left">表示したい日付を指定してください</th><th></tr></tr>
<tr>
    <th valign="top">
        <?php echo Form::input('onedaydate', 'onedaydate', array('type' => 'date')); ?>
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
        <?php echo Form::input('twodaydate', 'twodaydate', array('type' => 'date')); ?><input id="dummy_button" type="submit" class="btn btn-ptimary">
        <script>
            $("#dummy_button").css("visibility", "hidden");
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

<form method="post" name="onedayinfo" id="onedayinfo" action="onedayinfo" >
    <input type="hidden" id="param_date_1" name="param_date_1" value="">
    <input type="hidden" id="param_date_2" name="param_date_2" value="">

    <ul class="nav nav-tabs" style="border-bottom:none;">
        <li class="nav-item"><a href="sample">気温グラフを表示</a></li>
        <li class="nav-item"><a href="sample">デマンドグラフを表示</a></li>
        <li class="nav-item"><a id="onedayinfo">詳細表を表示</a></li>
    </ul>
</form>

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
    var targetDate1 = <?php echo $targetDate1; ?>;
    var targetDate2 = <?php echo $targetDate2; ?>;
    var str_id = <?php echo $strId; ?>;
    var strDataArray = <?php echo $strDataArray; ?>;

    $('#form_onedaydate').val(targetDate1);
    $('#form_twodaydate').val(targetDate2);
    var oneday = <?php echo $oneday; ?>;
    var yesterday = <?php echo $yesterday; ?>;
    /* チェックボックス */
    var checked_flg = <?php echo $checkedFlg; ?>;

    $('#onedayinfo').click(function () {
        $('#param_date_1').val($('#form_onedaydate').val());
        $('#param_date_2').val($('#form_twodaydate').val());

        $('#onedayinfo').submit();

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
                "title": "\u4f7f\u7528\u96fb\u529b\u91cf",
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
        var chartdata = convertArray(oneday, yesterday, targetDate1, targetDate2, checked_flg);
        google.charts.load('current', {'packages': ['corechart']});
        google.setOnLoadCallback(drawChart);
        function drawChart() {
            var data = new google.visualization.arrayToDataTable(chartdata);
            var options = {
                "title": "\u4f7f\u7528\u96fb\u529b\u91cf",
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

</script>