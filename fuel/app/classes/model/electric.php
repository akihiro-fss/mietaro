<?php
/**
 *
 * 作成日：2017/08/03
 * 更新日：2018/08/19
 * 作成者：戸田滉洋
 * 更新者：丸山　隼
 *
 */

/**
 * The Electric Model.
 *
 * 電力量のデータの転送
 * @package app
 * @extends Model
 *
 *
 */
use Orm\Observer;
class Model_Electric extends \orm\Model {

    protected static $_table_name = 'Electric';
    protected static $_primary_key = array('electric_id');
    protected static $_properties = array(
        'electric_id',
        'electric_at',
        'str_id',
        'electric_kw',
        'created_at',
    );
    protected static $_observers = array(
        'Orm\Observer_CreatedAt' => array(
            'events' => array('before_insert'),
            'mysql_timestamp' => false,
        ),
    );

    /**
     * DBデータ取得
     */
    private static function DbData($str_id, $start, $end) {
        //条件に-30分補正
        $start = date('Y-m-d H:i:s',strtotime("+30 minutes",strtotime($start)));
        $end = date('Y-m-d H:i:s',strtotime("+30 minutes",strtotime($end)));
        //出力結果に+30分補正
        $sql = "SELECT electric_at - INTERVAL 30 MINUTE AS electric_at, str_id, electric_kw FROM Electric WHERE str_id = $str_id and electric_at BETWEEN '$start' AND '$end'";
        $query = \DB::query($sql)->execute();
        return $query;
    }

    /**
     * SELECT用メソッド
     */
    private static function selectElectricData($str_id, $start, $end) {
        //条件に-30分補正
        $start = date('Y-m-d H:i:s',strtotime("+30 minutes",strtotime($start)));
        $end = date('Y-m-d H:i:s',strtotime("+30 minutes",strtotime($end)));
        //出力結果に+30分補正
        $sql = "SELECT electric_at - INTERVAL 30 MINUTE AS electric_at, str_id, electric_kw, demand_kw FROM Electric WHERE str_id = $str_id and electric_at BETWEEN '$start' AND '$end'";
        return \DB::query($sql)->execute()->as_array();
    }
    
    /**
     * 平均値計算用(demand_kw用)SELECTメソッド
     */
    private static function selectDemandData($str_id, $start, $end) {
        //条件に-30分補正
        $start = date('Y-m-d H:i:s',strtotime("+30 minutes",strtotime($start)));
        $end = date('Y-m-d H:i:s',strtotime("+30 minutes",strtotime($end)));
        $sql = "SELECT MAX(demand_kw) as 'demand_kw' FROM Electric WHERE str_id = $str_id and electric_at BETWEEN '$start' AND '$end'";
        return \DB::query($sql)->execute()->current();
    }

    /**
     * 月間データ用SELECT
     */
    private static function selectElectricDataForMonth($str_id, $start, $end) {
        //条件に-30分補正
        $start = date('Y-m-d H:i:s',strtotime("+30 minutes",strtotime($start)));
        $end = date('Y-m-d H:i:s',strtotime("+30 minutes",strtotime($end)));
        //出力結果に+30分補正
        $sql = "SELECT electric_at - INTERVAL 30 MINUTE AS electric_at, str_id, electric_kw FROM Electric WHERE str_id = $str_id AND electric_at >= '$start' AND electric_at < '$end'";
        return \DB::query($sql)->execute()->as_array();
    }

    /**
     * 店舗の基本情報取得
     */
    private static function selectBasicInfoForStrId($str_id){
    	$sql = "SELECT * FROM BasicInfo WHERE str_id = $str_id";
    	return \DB::query($sql)->execute()->current();
    }

    /**
     * 2018-08-12
     * １日の電力量表示データ取得メソッド
     * （v2 1h->30minでグラフ表示できるように）
     */
    public static function onedaydata() {
        $secondGraphFlg = Input::post('second_graph_flag');
        $onedaydate = date('Y-m-d');
        $twodaydate = '';
        if (Input::method() == 'POST') {
            $onedaydate = Input::post('onedaydate');
            if(empty($onedaydate)){
                $onedaydate = Input::post('param_date_1');
                if(empty($onedaydate)){
                    $onedaydate = date('Y-m-d');
                }
            }
            if($secondGraphFlg){
                $twodaydate = Input::post('twodaydate');
                if(empty($twodaydate)){
                    $twodaydate = Input::post('param_date_2');
                    if(empty($twodaydate)){
                        $twodaydate = date('Y-m-d');
                    }
                }
            }
            $oneday_st = date('Y-m-d 00:00:00', strtotime($onedaydate));
            //$oneday_end = date('Y/m/d 23:59:59', strtotime($onedaydate));
            $twoday_st = date('Y/m/d 00:00:00', strtotime($twodaydate));
            //$twoday_end = date('Y/m/d 23:59:59', strtotime($twodaydate));
        } else {
            $oneday_st = date("Y/m/d 00:00:00");
            //$oneday_end = date("Y/m/d 23:59:59");
            $twoday_st = date('Y/m/d 00:00:00', strtotime('-1 days'));
            //$twoday_end = date('Y/m/d 23:59:59', strtotime('-1 days'));
        }
        //Authのインスタンス化
        $auth = Auth::instance();
        $str_id = $auth->get_str_id();

        //店舗の基本情報取得
        $strData = self::selectBasicInfoForStrId($str_id);

        //CO2排出係数
        $emisionFactor = (float)$strData['emission_factor'];

        //原油換算係数
        $conversionFactor = (float)$strData['conversion_factor'];

        //第一指定日の電力量データ取得
        $result_oneday = self::calcOnedayData($str_id,$oneday_st);

        //第二指定日の電力量データ取得
        $result_twoday = array(
            'result' => array(),
            'total' => 0
        );

        if($secondGraphFlg){
            $tmp_result = self::calcOnedayData($str_id,$twoday_st);
            $result_twoday = array(
                'result' => $tmp_result['result'],
                'total' => $tmp_result['total']
            );
            $checkedFlg = 1;
        }else{
            $checkedFlg = 0;
        }

        //第一指定日のデマンドデータ取得
        $result_demand_oneday = self::calcOnedayDemandData($str_id,$oneday_st);

        //第二指定日のデマンドデータ取得
        $result_demand_twoday = array(
            'result' => array(),
            'max_demand' => 0
        );
        if($secondGraphFlg){
            $tmp_result = self::calcOnedayDemandData($str_id,$twoday_st);
            $result_demand_twoday = array(
                'result' => $tmp_result['result'],
                'max_demand' => $tmp_result['max_demand']
            );
            $checkedFlg = 1;
        }else{
            $checkedFlg = 0;
        }

        //店舗データ取得
        $strDataArray = Model_BasicInfo::getStrDataByStrId($str_id);

        //レスポンスデータを整理
        $resultsArray = array(
            'str_id' => $str_id,
            'str_data_array' => $strDataArray,
            'target_date_1' => $onedaydate,
            'target_date_2' => $twodaydate,
            'checked_flg' => $checkedFlg,
            'oneday' => $result_oneday['result'],
            'yesterday' => $result_twoday['result'],
            'oneday_demand' => $result_demand_oneday['result'],
            'yesterday_demand' => $result_demand_twoday['result'],
            'total_set_1' => $result_oneday['total'],
            'total_set_2' => $result_twoday['total'],
            'max_demand_1' => $result_demand_oneday['max_demand'],
            'max_demand_2' => $result_demand_twoday['max_demand'],
        	'total_emission_1' => floor($result_oneday['total'] * $emisionFactor),
        	'total_emission_2' => floor($result_twoday['total'] * $emisionFactor),
        	'total_price_1' => floor($result_oneday['total'] * $conversionFactor),
        	'total_price_2' => floor($result_twoday['total'] * $conversionFactor),
        	'conversion_factor' => $conversionFactor
        );

        return $resultsArray;
    }

    /**
     * 一週間分のデータ取得
     */
    public static function weekdaydata() {
        $secondGraphFlg = Input::post('second_graph_flag');
        $weekdate = date('Y-m-d');
        $twoweekdate = "";
        if (Input::method() == 'POST') {
            $weekdate = Input::post('oneweekdate');
            if($weekdate == ""){
                $weekdate = date('Y-m-d');
            }
            $week_st = date('Y-m-d 00:00:00', strtotime("-1 week",strtotime($weekdate)));
            $week_end = date('Y-m-d 23:59:59', strtotime($weekdate));
            if($secondGraphFlg){
                $twoweekdate = Input::post('twoweekdate');
                if($twoweekdate == ""){
                    $twoweekdate = date('Y-m-d', strtotime("-1 week"));
                }
            }
            $week_ago_st = date('Y-m-d 00:00:00', strtotime("-1 week",strtotime($twoweekdate)));
            $week_ago_end = date('Y-m-d 23:59:59', strtotime($twoweekdate));
        } else {
            $week_st = date("Y-m-d 00:00:00", strtotime("-1 week"));
            $week_end = date("Y-m-d 23:59:59");
            $week_ago_st = date('Y-m-d 00:00:00', strtotime("-2 week"));
            $week_ago_end = date('Y-m-d 23:59:59', strtotime("-1 week"));
        }
        //Authのインスタンス化
        $auth = Auth::instance();
        $str_id = $auth->get_str_id();

        //店舗の基本情報取得
        $strData = self::selectBasicInfoForStrId($str_id);

        //CO2排出係数
        $emisionFactor = (float)$strData['emission_factor'];

        //原油換算係数
        $conversionFactor = (float)$strData['conversion_factor'];

        $result_a_week = Model_Electric::selectElectricData($str_id, $week_st, $week_end);
        $result_a_week_ago = array();
        if(!is_null($secondGraphFlg)){
            //比較グラフの表示ー有効
            $result_a_week_ago = Model_Electric::selectElectricData($str_id, $week_ago_st, $week_ago_end);
            $checkedFlg = 1;
        }else{
            //比較グラフの表示ー無効
            $checkedFlg = 0;
        }
        $result = Model_Electric::convertDataForWeek($result_a_week,$result_a_week_ago,$weekdate,$twoweekdate);

        return array(
            'target_date_1' => $weekdate,
            'target_date_2' => $twoweekdate,
            'checked_flg' => $checkedFlg,
            'one_week' => $result['one_week'],
            'two_week' => $result['two_week'],
            'total_set_1' => $result['total_one_week'],
            'total_set_2' => $result['total_two_week'],
        	'max_demand_1' => $result['max_demand_one_week'],
        	'max_demand_2' => $result['max_demand_two_week'],
        	'total_emission_1' => floor($result['total_one_week'] * $emisionFactor),
        	'total_emission_2' => floor($result['total_two_week'] * $emisionFactor),
        	'total_price_1' => floor($result['total_one_week'] * $conversionFactor),
        	'total_price_2' => floor($result['total_two_week'] * $conversionFactor),
        );
    }

    /**
     * 一ヶ月分のデータ取得
     */
    public static function monthdaydata() {
        $secondGraphFlg = Input::post('second_graph_flag');
        $onemonthdate = date('Y-m-d');
        $twomonthdate = "";
        if (Input::method() == 'POST') {
            $onemonthdate = Input::post('onemonthdate');
            if(empty($onemonthdate)){
                $onemonthdate = Input::post('param_date_1');
                if(empty($onemonthdate)){
                    $onemonthdate = date('Y-m-d');
                }
            }
            if($secondGraphFlg){
                $twomonthdate = Input::post('twomonthdate');
                if(empty($twomonthdate)){
                    $twomonthdate = Input::post('param_date_2');
                    if(empty($twomonthdate)){
                        $twomonthdate = date('Y-m-d');
                    }
                }
            }
            $month_st = date('Y-m-1 00:00:00', strtotime($onemonthdate));
            $month_end = date('Y-m-d 23:59:59', strtotime("-1 days ",strtotime(date('Y-m-1 00:00:00', strtotime("+1 MONTH ",strtotime($month_st))))));
            $month_ago_st = date('Y-m-1 00:00:00', strtotime($twomonthdate));
            $month_ago_end = date('Y-m-d 23:59:59', strtotime("-1 days ",strtotime(date('Y-m-1 00:00:00', strtotime("+1 MONTH ",strtotime($month_ago_st))))));
        } else {
            $month_st = date("Y-m-1 00:00:00", strtotime($onemonthdate));
            $month_end = date('Y-m-d 23:59:59', strtotime("-1 days ",strtotime(date('Y-m-1 00:00:00', strtotime("+1 MONTH ",strtotime($month_st))))));
            $month_ago_st = date('Y-m-1 00:00:00', strtotime("-1 month -1days",strtotime(date('Y-m-d hh:mm:ss'))));
            $month_ago_end = date('Y-m-d 23:59:59', strtotime("-1 days ",strtotime(date('Y-m-1 00:00:00', strtotime("+1 MONTH ",strtotime($month_ago_st))))));
        }

        //Authのインスタンス化
        $auth = Auth::instance();
        $str_id = $auth->get_str_id();

        //店舗の基本情報取得
        $strData = self::selectBasicInfoForStrId($str_id);

        //CO2排出係数
        $emisionFactor = (float)$strData['emission_factor'];

        //原油換算係数
        $conversionFactor = (float)$strData['conversion_factor'];

        $result_a_month = Model_Electric::selectElectricData($str_id, $month_st, $month_end);
        $result_a_month_ago=array();
        if($secondGraphFlg){
            $result_a_month_ago = Model_Electric::selectElectricData($str_id, $month_ago_st, $month_ago_end);
            //前月グラフデータ表示有効
            $checkedFlg = 1;
        }else{
            //前月グラフデータ表示無効
            $checkedFlg = 0;
        }

        $result = Model_Electric::convertDataForMonth($result_a_month,$result_a_month_ago,$month_st,$month_end,$month_ago_st,$month_ago_end,$checkedFlg);

        return array(
        	'result' => $result['result'],
        	'result_demand' => $result['result_demand'],
        	'target_date_1' => $onemonthdate,
        	'target_date_2' => $twomonthdate,
        	'checked_flg' => $checkedFlg,
        	'total_set_1' => $result['total_one_month'],
        	'total_set_2' => $result['total_two_month'],
        	'max_demand_1' => $result['max_demand_one_month'],
        	'max_demand_2' => $result['max_demand_two_month'],
        	'total_emission_1' => floor($result['total_one_month'] * $emisionFactor),
        	'total_emission_2' => floor($result['total_two_month'] * $emisionFactor),
        	'total_price_1' => floor($result['total_one_month'] * $conversionFactor),
        	'total_price_2' => floor($result['total_two_month'] * $conversionFactor),
        );
    }

    /**
     * 一年分のデータ取得
     */
    public static function yeardata() {
        $secondGraphFlg = Input::post('second_graph_flag');
        $oneyeardate = date('Y-m-d');
        $twoyeardate = "";
        if (Input::method() == 'POST') {
            $oneyeardate = Input::post('oneyeardate');
            if(empty($oneyeardate)){
                $oneyeardate = Input::post('param_date_1');
                if(empty($oneyeardate)){
                    $oneyeardate = date('Y-m-d');
                }
            }
            if($secondGraphFlg){
                $twoyeardate = Input::post('twoyeardate');
                if(empty($twoyeardate)){
                    $twoyeardate = Input::post('param_date_2');
                    if(empty($twoyeardate)){
                        $twoyeardate = date('Y-m-d');
                    }
                }
            }
            $oneYearsdate_st = date('Y-01-01 00:00:00', strtotime($oneyeardate));
            $oneYearsdate_end = date('Y-12-31 23:59:59', strtotime($oneyeardate));
            $twoYearsdate_st = date('Y-01-01 00:00:00', strtotime($twoyeardate));
            $twoYearsdate_end = date('Y-12-31 23:59:59', strtotime($twoyeardate));
        } else {
            $oneYearsdate_st = date('Y-01-01 00:00:00');
            $oneYearsdate_end = date('Y-12-31 23:59:59');
            $twoYearsdate_st = date('Y-01-01 00:00:00',strtotime('-1 years',strtotime($oneYearsdate_st)));
            $twoYearsdate_end = date('Y-12-31 23:59:59',strtotime('-1 years',strtotime($oneYearsdate_end)));
        }


        //Authのインスタンス化
        $auth = Auth::instance();
        $str_id = $auth->get_str_id();

        //店舗の基本情報取得
        $strData = self::selectBasicInfoForStrId($str_id);

        //CO2排出係数
        $emisionFactor = (float)$strData['emission_factor'];

        //原油換算係数
        $conversionFactor = (float)$strData['conversion_factor'];

        $result_one_years = Model_Electric::selectElectricData($str_id, $oneYearsdate_st, $oneYearsdate_end);

        $result_two_years=array();
        if($secondGraphFlg){
            $result_two_years = Model_Electric::selectElectricData($str_id, $twoYearsdate_st, $twoYearsdate_end);
            $checkedFlg = 1;
        }else{
            $checkedFlg = 0;
        }

        $result = self::convertDataForYear($result_one_years,$result_two_years,$oneYearsdate_st,$twoYearsdate_st,$checkedFlg);

        return array(
            'result' => $result['result'],
            'result_demand' => $result['result_demand'],
            'target_date_1' => $oneyeardate,
            'target_date_2' => $twoyeardate,
            'checked_flg' => $checkedFlg,
            'total_set_1' => $result['total_one_year'],
            'total_set_2' => $result['total_two_year'],
            'max_demand_1' => $result['max_demand_one_year'],
            'max_demand_2' => $result['max_demand_two_year'],
        	'total_emission_1' => floor($result['total_one_year'] * $emisionFactor),
        	'total_emission_2' => floor($result['total_two_year'] * $emisionFactor),
        	'total_price_1' => floor($result['total_one_year'] * $conversionFactor),
        	'total_price_2' => floor($result['total_two_year'] * $conversionFactor),
        );
    }

    //sideberに表示するための月間使用電力量取得
    public static function getSideBerData() {
        $monthdata = date('Y-m-d');
        $month_st = date('Y-m-1 00:00:00', strtotime($monthdata));
        $month_end = date('Y-m-d 23:59:59', strtotime("-1 days ", strtotime(date('Y-m-1 00:00:00', strtotime("+1 MONTH ", strtotime($month_st))))));
        //Authのインスタンス化
        $auth = Auth::instance();
        $str_id = $auth->get_str_id();
        $result_a_month = Model_Electric::DbData($str_id, $month_st, $month_end);
        return $result_a_month;
    }

    /**
     * 指定日付の30分毎の電力量の平均値を計算し表示用配列に変換する
     * @access datetime Y-m-d 00:00:0
     * @return arrayobject
     */
    public static function calcOnedayData($str_id,$datetime){
        $start = $datetime;
        $end = date("Y-m-d H:i:s",strtotime($start . "+23 hour +59 minute +59 seconds"));
        $result = Model_Electric::selectElectricData($str_id,$start,$end); 
        //配列のキーとなる値の初期化
        $key = 0.5;
        $count = 1;
        //配列の初期化
        $result_array = array(
            array(
                0=>'',
                1=>'電力量'
            )
        );
        //必要情報を取得するまで繰り返す（1日分の電力量データ30分毎の平均値）
        $total = 0;
        $calc_start = $start;
        $calc_end = date("Y-m-d H:i:s",strtotime($start . " +30 minute"));
        while(1){
            $tmp_array = array(
                0 => ($key * $count).'h',
                1 => 0
            );
            foreach($result as $data){
                if($data['electric_at'] >= $calc_start && $data['electric_at'] <= $calc_end){
                        //配列を作成
                        $tmp_array[1] = (int)$data['electric_kw'];
                }
            }
            //合計値加算
            $total += $tmp_array[1];
            //結果用配列にプッシュ
            array_push($result_array,$tmp_array);
            //1日分のデータ（48個＋グラフ用要素1個）揃ったらループ終了
            if(count($result_array) > 48){
                break;
            }
            //配列のキーを加算
            $count++;
            //計算範囲をシフト
            $calc_start = date("Y-m-d H:i:s",strtotime($calc_start . "+30 minute"));
            $calc_end = date("Y-m-d H:i:s",strtotime($calc_end . "+30 minute"));
        }
        return array(
            'result' => $result_array,
            'total'  => $total
        );
    }

    /**
     * 指定日付の30分毎のデマンド値の平均値を計算し表示用配列に変換する
     * @access datetime Y-m-d 00:00:0
     * @return arrayobject
     */
    public static function calcOnedayDemandData($str_id,$datetime){
        $start = $datetime;
        $end = date("Y-m-d H:i:s",strtotime($start . "+23 hour +59 minute +59 seconds"));
        $result = Model_Electric::selectElectricData($str_id,$start,$end);
        //配列のキーとなる値の初期化
        $key = 0.5;
        $count = 1;
        //配列の初期化
        $result_array = array(
            array(
                0=>'',
                1=>'デマンド'
            )
        );
        //必要情報を取得するまで繰り返す（1日分の電力量データ30分毎の平均値）
        $total = 0;
        $max = 0;
        $calc_start = $start;
        $calc_end = date("Y-m-d H:i:s",strtotime($start . " +30 minute"));
        while(1){
            $tmp_array = array(
                0 => ($key * $count).'h',
                1 => 0
            );
            foreach($result as $data){
                if($data['electric_at'] >= $calc_start && $data['electric_at'] <= $calc_end){
                        //配列を作成
                        $tmp_array[1] = (int)$data['demand_kw'];
                }
                //最大値を保持
                if($max <= (int)$result['demand_kw']){
                    $max = (int)$result['demand_kw'];
                }
            }
            //合計値加算
            $total += $tmp_array[1];
            
            //結果用配列にプッシュ
            array_push($result_array,$tmp_array);
            //1日分のデータ（48個＋グラフ用要素1個）揃ったらループ終了
            if(count($result_array) > 48){
                break;
            }
            //配列のキーを加算
            $count++;
            //計算範囲をシフト
            $calc_start = date("Y-m-d H:i:s",strtotime($calc_start . "+30 minute"));
            $calc_end = date("Y-m-d H:i:s",strtotime($calc_end . "+30 minute"));
        }

        return array(
            'result' => $result_array,
            'max_demand'  => $max
        );
    }

    /**
     * 2週間分のデータをグラフ表示用に変換
     * @access 1週間分のデータ
     * @access 2週間前から～１週間前までのデータ
     * @access 指定された日時
     * @return グラフ表示用配列
     */
    public static function convertDataForWeek($result_one_week_ago,$result_two_week_ago,$targetdate1,$targetdate2){

        //合計値用
        $totalOneWeek = 0;
        $totalTwoWeek = 0;
        //最大デマンド値
        $maxDemandOneWeek = 0;
        $maxDemandTwoWeek = 0;

        //一週間分の日付
        $date1Array = array(
            array("",),
            array(date('Y-m-d',strtotime("-6 days $targetdate1")),array()),
            array(date('Y-m-d',strtotime("-5 days $targetdate1")),array()),
            array(date('Y-m-d',strtotime("-4 days $targetdate1")),array()),
            array(date('Y-m-d',strtotime("-3 days $targetdate1")),array()),
            array(date('Y-m-d',strtotime("-2 days $targetdate1")),array()),
            array(date('Y-m-d',strtotime("-1 days $targetdate1")),array()),
            array(date('Y-m-d',strtotime($targetdate1)),array()),
        );

        $date2Array = array(
            array("",),
            array(date('Y-m-d',strtotime("-6 days $targetdate2")),array()),
            array(date('Y-m-d',strtotime("-5 days $targetdate2")),array()),
            array(date('Y-m-d',strtotime("-4 days $targetdate2")),array()),
            array(date('Y-m-d',strtotime("-3 days $targetdate2")),array()),
            array(date('Y-m-d',strtotime("-2 days $targetdate2")),array()),
            array(date('Y-m-d',strtotime("-1 days $targetdate2")),array()),
            array(date('Y-m-d',strtotime($targetdate2)),array()),
        );

        //各日の最大デマンド値・発生時刻保持用配列
        $demandArray1 = array(
        	array("",),
        	array(date('Y-m-d',strtotime("-6 days $targetdate1")),array('demand_kw' => 0,'electric_at' => "-")),
        	array(date('Y-m-d',strtotime("-5 days $targetdate1")),array('demand_kw' => 0,'electric_at' => "-")),
        	array(date('Y-m-d',strtotime("-4 days $targetdate1")),array('demand_kw' => 0,'electric_at' => "-")),
        	array(date('Y-m-d',strtotime("-3 days $targetdate1")),array('demand_kw' => 0,'electric_at' => "-")),
        	array(date('Y-m-d',strtotime("-2 days $targetdate1")),array('demand_kw' => 0,'electric_at' => "-")),
        	array(date('Y-m-d',strtotime("-1 days $targetdate1")),array('demand_kw' => 0,'electric_at' => "-")),
        	array(date('Y-m-d',strtotime($targetdate1)),array('demand_kw' => 0,'electric_at' => "-")),
        );
        $demandArray2 = array(
        	array("",),
        	array(date('Y-m-d',strtotime("-6 days $targetdate2")),array('demand_kw' => 0,'electric_at' => "-")),
        	array(date('Y-m-d',strtotime("-5 days $targetdate2")),array('demand_kw' => 0,'electric_at' => "-")),
        	array(date('Y-m-d',strtotime("-4 days $targetdate2")),array('demand_kw' => 0,'electric_at' => "-")),
        	array(date('Y-m-d',strtotime("-3 days $targetdate2")),array('demand_kw' => 0,'electric_at' => "-")),
        	array(date('Y-m-d',strtotime("-2 days $targetdate2")),array('demand_kw' => 0,'electric_at' => "-")),
        	array(date('Y-m-d',strtotime("-1 days $targetdate2")),array('demand_kw' => 0,'electric_at' => "-")),
        		array(date('Y-m-d',strtotime($targetdate2)),array('demand_kw' => 0,'electric_at' => "-")),
        );



        //計算処理軽減のため抽出結果を整形
        foreach($result_one_week_ago as $tmpData){
            foreach($date1Array as $key=>$search){
                if($key == 0){continue;}
                if(strpos($tmpData['electric_at'],$search[0]) !== false){
                    array_push($date1Array[$key][1],$tmpData);
                }
            }
        }

        foreach($result_two_week_ago as $tmpData){
            foreach($date2Array as $key=>$search){
                if($key == 0){continue;}
                if(strpos($tmpData['electric_at'],$search[0]) !== false){
                    array_push($date2Array[$key][1],$tmpData);
                }
            }
        }

        //結果格納用配列の初期化
        $result1 = self::initResultArrayForWeek($date1Array);
        $result2 = self::initResultArrayForWeek($date2Array);

        //計算用配列を取得
        $calcArray = self::getCalcArrayForWeekData();

        //平均値算出用の配列

        //日毎の電力計算処理
        foreach($date1Array as $index=>$tmpArray){
            if($index == 0){continue;}
            $targetDate = $tmpArray[0];
            $max_demand = 0;
            $max_demand_at = null;
            foreach($tmpArray[1] as $calcTargetData ){
                foreach($calcArray as $calcData){
                    $startDatetime = strtotime($targetDate.' '.$calcData['start_time']);
                    $endtDatetime = strtotime($targetDate.' '.$calcData['end_time']);
                    $targetTime = strtotime($calcTargetData['electric_at']);
                    if(($targetTime >= $startDatetime)&&($targetTime <= $endtDatetime)){
                    	//日ごとの配列に加算
                       	$result1[$calcData['index']][$index] += $calcTargetData['electric_kw'];
                        //1週間の合計電力量加算
                        $totalOneWeek += $calcTargetData['electric_kw'];
                        //デマンドの最大値を計算
                        if($calcTargetData['demand_kw'] > $max_demand){
                        	$max_demand = $calcTargetData['demand_kw'];
                        	$max_demand_at = $calcTargetData['electric_at'];
                        }
                        //週間の最大デマンド値保持
                        if($maxDemandOneWeek < $max_demand){
                        	$maxDemandOneWeek = $max_demand;
                        }
//                      $countArray[$calcData['index']][$index]++;
                    }
                    //各日の最大デマンド値と発生日時を保持
                    $demandArray1[$index][1]['demand_kw'] = $max_demand;
                    //日付フォーマットを（H:i)に変換
                    if($max_demand_at != null){
                    	$demandArray1[$index][1]['electric_at'] = date('H:i',strtotime($max_demand_at));
                    }else{
                    	//phpの表示バグ対応
                    	$demandArray1[$index][1]['electric_at'] = '-';
                    }
                }
            }
        }
        //平均値算出用の配列
        //日毎の電力計算処理
        foreach($date2Array as $index=>$tmpArray){
            if($index == 0){continue;}
            $targetDate = $tmpArray[0];
            $max_demand = 0;
            $max_demand_at = null;
            foreach($tmpArray[1] as $calcTargetData ){
                foreach($calcArray as $calcData){
                	$startDatetime = strtotime($targetDate.' '.$calcData['start_time']);
                    $endtDatetime = strtotime($targetDate.' '.$calcData['end_time']);
                    $targetTime = strtotime($calcTargetData['electric_at']);
                    if(($targetTime >= $startDatetime)&&($targetTime <= $endtDatetime)){
                    	//日ごとの配列に加算
                        $result2[$calcData['index']][$index] += intVal($calcTargetData['electric_kw']);
                        //1週間の合計電力量加算
                        $totalTwoWeek += intVal($calcTargetData['electric_kw']);
//                      $countArray[$calcData['index']][$index]++;
                        //デマンドの最大値を計算
                        if($calcTargetData['demand_kw'] > $max_demand){
                        	$max_demand = $calcTargetData['demand_kw'];
                        	$max_demand_at = $calcTargetData['electric_at'];
                        }
                        //週間の最大デマンド値保持
                        if($maxDemandTwoWeek < $max_demand){
                        	$maxDemandTwoWeek = $max_demand;
                        }
                    }
                    //各日の最大デマンド値と発生日時を保持
                    $demandArray2[$index][1]['demand_kw'] = $max_demand;
                    //日付フォーマットを（H:i)に変換
                    if($max_demand_at != null){
                    	$demandArray2[$index][1]['electric_at'] = date('H:i',strtotime($max_demand_at));
                    }else{
                    	//phpの表示バグ対応
                    	$demandArray2[$index][1]['electric_at'] = '-';
                    }
                }
            }
        }

        return array(
            'one_week' => $result1,
        	'one_week_demand' => $demandArray1,
            'total_one_week' => $totalOneWeek,
        	'max_demand_one_week' => $maxDemandOneWeek,
            'two_week' => $result2,
        	'two_week_demand' => $demandArray2,
            'total_two_week' => $totalTwoWeek,
        	'max_demand_two_week' => $maxDemandTwoWeek,
        );
    }

    /**
     * 2ヶ月分のデータをグラフ表示用に変換
     * @access 1ヶ月分のデータ
     * @access 2ヶ月から～１ヶ月までのデータ
     * @access 指定された日時
     * @return グラフ表示用配列
     */
    public static function convertDataForMonth($result_one_month_ago,$result_two_month_ago,$month_st,$month_end,$month_ago_st,$month_ago_end,$checkedFlg){
        //合計値用
        $totalOneMonth = 0;
        $totalTwoMonth = 0;
        //最大値保持用
        $max_demand_1 = 0;
        $max_demand_2 = 0;


        $day = date('j',strtotime($month_st));
        $end1 = date('j',strtotime($month_end));
        $end2 = date('j',strtotime($month_ago_end));
        $end = 0;
        if(intVal($end1) > intVal($end2)){
            $end = $end1;
        }else{
            $end = $end2;
        }

        //配列初期化
        //date1Arrayの各要素の配列の0番目の要素が日付、1番目は当月、2番目は先月の電力量用
        //demandArrayの各要素の配列の0番目の要素が日付、1番目は当月、2番目は先月の電力量用
        if($checkedFlg){
            $date1Array = array(
                array("",date('n',strtotime($month_st))."月電力量",date('n',strtotime($month_ago_st))."月電力量"),
            );
            $demandArray = array(
                array("",date('n',strtotime($month_st))."月デマンド値",date('n',strtotime($month_ago_st))."月デマンド値"),
            );
            $demandTriggerArray = array(
            		array("",date('n',strtotime($month_st))."月デマンド値発生日時",date('n',strtotime($month_ago_st))."月デマンド値発生日時"),
            );
            for($i=$day;$i <= $end;$i++){
                array_push($date1Array,array("$i",0,0));
                array_push($demandArray,array("$i",0,0));
                array_push($demandTriggerArray,array("$i",null,null));
            }
        }else{
            $date1Array = array(
                array("",date('n',strtotime($month_st))."月電力量"),
            );
            $demandArray = array(
                array("",date('n',strtotime($month_st))."月デマンド値"),
            );
            $demandTriggerArray = array(
            		array("",date('n',strtotime($month_st))."月デマンド値発生日時"),
            );
            for($i=$day;$i <= $end;$i++){
                array_push($date1Array,array("$i",0));
                array_push($demandArray,array("$i",0));
                array_push($demandTriggerArray,array("$i",null));
            }
        }

        //計算処理軽減のため抽出結果を整形
        $targetDate1 = date('Y-m-d',strtotime($month_st));
        $targetDate2 = date('Y-m-d',strtotime($month_ago_st));

        //１ヶ月目の最大デマンド値
        $month_max_deman_1 = 0;
        //２ヶ月目の最大デマンド値
        $month_max_deman_2 = 0;
        foreach($date1Array as $index=>$date){
            if($index == 0){ continue;}
            //平均値計算用
            $count1 = 0;
            $count2 = 0;
            $demand_count1 = 0;
            $demand_count2 = 0;
            $max_demand_at_1 = null;
            $max_demand_at_2 = null;
            //最大値保持用
            $max_demand_1 = 0;
            $max_demand_2 = 0;

            foreach($result_one_month_ago as $tmpData){
                if(strpos($tmpData['electric_at'],$targetDate1) !== FALSE){
                   //電力計算
                   if(intVal($tmpData['electric_kw']) > 0){
                       $date1Array[$index][1] += intVal($tmpData['electric_kw']);
                       $count1++;
                   }
                   //デマンド計算
                   if(intVal($tmpData['demand_kw']) > 0){
                       $demand_count1++;
                       if(intVal($tmpData['demand_kw']) > $max_demand_1){
                           $max_demand_1 = intVal($tmpData['demand_kw']);
                           $max_demand_at_1 = $tmpData['electric_at'];
                       }
                   }
                   //1ヶ月目の最大デマンド計算
                   if(intVal($tmpData['demand_kw']) > 0){
                       if(intVal($tmpData['demand_kw']) > $month_max_deman_1){
                           $month_max_deman_1 = $tmpData['demand_kw'];
                       }
                    }
                }
            }

            foreach($result_two_month_ago as $tmpData){
               if(strpos($tmpData['electric_at'],$targetDate2) !== FALSE){
                   //電力計算
                   if(intVal($tmpData['electric_kw']) > 0){
                       $date1Array[$index][2] += intVal($tmpData['electric_kw']);
                       $count2++;
                   }
                   //デマンド計算
                   if(intVal($tmpData['demand_kw']) > 0){
                       $demand_count2++;
                       if(intVal($tmpData['demand_kw']) > $max_demand_2){
                           $max_demand_2 = intVal($tmpData['demand_kw']);
                           $max_demand_at_2 = $tmpData['electric_at'];
                       }
                   }
                   //２ヶ月目の最大デマンド計算
                   if(intVal($tmpData['demand_kw']) > 0){
                    if(intVal($tmpData['demand_kw']) > $month_max_deman_2){
                        $month_max_deman_2 = $tmpData['demand_kw'];
                    }
                 }
               }
            }

            if($count1 > 0){
                //電力
                //$date1Array[$index][1] = (int)($date1Array[$index][1] / $count1);
                $date1Array[$index][1] = (int)$date1Array[$index][1];
                $totalOneMonth += (int)$date1Array[$index][1];
            }
            if($demand_count1 > 0){
                //デマンド
                $demandArray[$index][1] = $max_demand_1;
                $demandTriggerArray[$index][1] = $max_demand_at_1;
            }
            if($count2 > 0){
                //電力
                //$date1Array[$index][2] = (int)($date1Array[$index][2] / $count2);
                $date1Array[$index][2] = (int)$date1Array[$index][2];
                $totalTwoMonth += (int)$date1Array[$index][2];
            }
            if($demand_count2 > 0){
                //デマンド
                $demandArray[$index][2] = $max_demand_2;
                $demandTriggerArray[$index][2] = $max_demand_at_2;
            }

            $targetDate1 = date('Y-m-d',strtotime('+1 days'.$targetDate1));
            $targetDate2 = date('Y-m-d',strtotime('+1 days'.$targetDate2));
        }

        return array(
            'result' => $date1Array,
            'total_one_month' => $totalOneMonth,
            'total_two_month' => $totalTwoMonth,
            'result_demand' => $demandArray,
        	'result_demand_at' => $demandTriggerArray,
            'max_demand_one_month' => $month_max_deman_1,
            'max_demand_two_month' => $month_max_deman_2,
        );
    }

    /**
     * 2年分のデータをグラフ表示用に変換
     * @access 1年分のデータ
     * @access 2年前から～１年前までのデータ
     * @return グラフ表示用配列
     */
    public static function convertDataForYear($result_one_years,$result_two_years,$oneYearsdate_st,$twoYearsdate_st,$checkedFlg){

    	//合計値量
        $totalOneYear = 0;
        $totalTwoYear = 0;

        //デマンド最大値保持用
        $demandMax1 = 0;
        $demandMax2 = 0;

        //dateArrayの各要素の配列の0番目の要素が日付、1番目は当年、2番目は前年の電力量用
        if($checkedFlg){
            $dateArray = array(
                array("",date('Y',strtotime($oneYearsdate_st))."年電力量",date('Y',strtotime($twoYearsdate_st))."年電力量"),
            );
            $demandArray = array(
                array("",date('Y',strtotime($oneYearsdate_st))."年デマンド値",date('Y',strtotime($twoYearsdate_st))."年デマンド値"),
            );
            $demandTriggerArray = array(
            		array("",date('n',strtotime($oneYearsdate_st))."デマンド値発生日時",date('n',strtotime($twoYearsdate_st))."年デマンド値発生日時"),
            );
            for($i = 1;$i <= 12;$i++){
                if($i < 10){
                    $i = "0".$i;
                }
                array_push($dateArray,array("$i",0,0));
                array_push($demandArray,array("$i",0,0));
                array_push($demandTriggerArray,array("$i",null,null));
            }
        }else{
            $dateArray = array(
                array("",date('Y',strtotime($oneYearsdate_st))."年電力量"),
            );
            $demandArray = array(
                array("",date('Y',strtotime($oneYearsdate_st))."年デマンド値"),
            );
            $demandTriggerArray = array(
            		array("",date('n',strtotime($oneYearsdate_st))."デマンド値発生日時",),
            );
            for($i = 1;$i <= 12;$i++){
                if($i < 10){
                    $i = "0".$i;
                }
                array_push($dateArray,array("$i",0));
                array_push($demandArray,array("$i",0));
                array_push($demandTriggerArray,array("$i",null));
            }
        }

        //平均値計算用配列
        $calcArray = $dateArray;

        //電力量計算
        foreach($result_one_years as $tmpData){
            $month = date('m',strtotime($tmpData['electric_at']));
            foreach($dateArray as $index => $date){
                if($index == 0){continue;}
                if($month == $date[0]){
                    //電力量計算
                    $dateArray[$index][1] += intVal($tmpData['electric_kw']);
                    $calcArray[$index][1]++;
                    //デマンド
                    if($demandArray[$index][1] < intVal($tmpData['demand_kw'])){
                        $demandArray[$index][1] = intVal($tmpData['demand_kw']);
                        $demandTriggerArray[$index][1] = $tmpData['electric_at'];
                    }
                    if($demandMax1 < $demandArray[$index][1]){
                        $demandMax1 = $demandArray[$index][1];
                    }
                }
            }
        }

        foreach($result_two_years as $tmpData){
            $month = date('m',strtotime($tmpData['electric_at']));
            foreach($dateArray as $index => $date){
                if($index == 0){continue;}
                if($month == $date[0]){
                    //電力量計算
                    $dateArray[$index][2] += intVal($tmpData['electric_kw']);
                    $calcArray[$index][2]++;
                    //デマンド
                    if($demandArray[$index][2] < intVal($tmpData['demand_kw'])){
                        $demandArray[$index][2] = intVal($tmpData['demand_kw']);
                        $demandTriggerArray[$index][2] = $tmpData['electric_at'];
                    }
                    if($demandMax2 < $demandArray[$index][2]){
                        $demandMax2 = $demandArray[$index][2];
                    }
                }
            }
        }

        //平均値計算
        foreach($calcArray as $index=>$arrayData){
            if($index == 0){continue;}
            foreach($arrayData as $key=>$count){
                if($key == 0){continue;}
                if($count == 0){continue;}
                //$dateArray[$index][$key] = (int)($dateArray[$index][$key] / $count);
                $dateArray[$index][$key] = (int)$dateArray[$index][$key];
                if($key == 1){
                    $totalOneYear += (int)$dateArray[$index][1];
                }elseif($key == 2){
                    $totalTwoYear += (int)$dateArray[$index][2];
                }
            }
        }

        return array(
            'result' => $dateArray,
            'result_demand' => $demandArray,
        	'result_demand_at' => $demandTriggerArray,
            'total_one_year' => $totalOneYear,
            'total_two_year' => $totalTwoYear,
            'max_demand_one_year' => $demandMax1,
            'max_demand_two_year' => $demandMax2,
        );
    }

    /**
     * 計算用の配列を取得する（週間データ用）
     */
    private static function getCalcArrayForWeekData(){
        return array(
            array(
                'start_time' => "00:00:00",
                'end_time' => "00:59:59",
                'index' => 1,
            ),
            array(
                'start_time' => "01:00:00",
                'end_time' => "01:59:59",
                'index' => 2,
            ),
            array(
                'start_time' => "02:00:00",
                'end_time' => "02:59:59",
                'index' => 3,
            ),
            array(
                'start_time' => "03:00:00",
                'end_time' => "03:59:59",
                'index' => 4,
            ),
            array(
                'start_time' => "04:00:00",
                'end_time' => "04:59:59",
                'index' => 5,
            ),
            array(
                'start_time' => "05:00:00",
                'end_time' => "05:59:59",
                'index' => 6,
            ),
            array(
                'start_time' => "06:00:00",
                'end_time' => "06:59:59",
                'index' => 7,
            ),
            array(
                'start_time' => "07:00:00",
                'end_time' => "07:59:59",
                'index' => 8,
            ),
            array(
                'start_time' => "08:00:00",
                'end_time' => "08:59:59",
                'index' => 9,
            ),
            array(
                'start_time' => "09:00:00",
                'end_time' => "09:59:59",
                'index' => 10,
            ),
            array(
                'start_time' => "10:00:00",
                'end_time' => "10:59:59",
                'index' => 11,
            ),
            array(
                'start_time' => "11:00:00",
                'end_time' => "11:59:59",
                'index' => 12,
            ),
            array(
                'start_time' => "12:00:00",
                'end_time' => "12:59:59",
                'index' => 13,
            ),
            array(
                'start_time' => "13:00:00",
                'end_time' => "13:59:59",
                'index' => 14,
            ),
            array(
                'start_time' => "14:00:00",
                'end_time' => "14:59:59",
                'index' => 15,
            ),
            array(
                'start_time' => "15:00:00",
                'end_time' => "15:59:59",
                'index' => 16,
            ),
            array(
                'start_time' => "16:00:00",
                'end_time' => "16:59:59",
                'index' => 17,
            ),
            array(
                'start_time' => "17:00:00",
                'end_time' => "17:59:59",
                'index' => 18,
            ),
            array(
                'start_time' => "18:00:00",
                'end_time' => "18:59:59",
                'index' => 19,
            ),
            array(
                'start_time' => "19:00:00",
                'end_time' => "19:59:59",
                'index' => 20,
            ),
            array(
                'start_time' => "20:00:00",
                'end_time' => "20:59:59",
                'index' => 21,
            ),
            array(
                'start_time' => "21:00:00",
                'end_time' => "21:59:59",
                'index' => 22,
            ),
            array(
                'start_time' => "22:00:00",
                'end_time' => "22:59:59",
                'index' => 23,
            ),
            array(
                'start_time' => "23:00:00",
                'end_time' => "23:59:59",
                'index' => 24,
            ),
        );

    }

    /* 週間ページのグラフ表示用配列の初期化 */
    private static function initResultArrayForWeek($date1Array){
        $firstindex = array();
        foreach($date1Array as $date){
            array_push($firstindex,$date[0]);
        }
        return array(
            $firstindex,
            array("0h",0,0,0,0,0,0,0),
            array("1h",0,0,0,0,0,0,0),
            array("2h",0,0,0,0,0,0,0),
            array("3h",0,0,0,0,0,0,0),
            array("4h",0,0,0,0,0,0,0),
            array("5h",0,0,0,0,0,0,0),
            array("6h",0,0,0,0,0,0,0),
            array("7h",0,0,0,0,0,0,0),
            array("8h",0,0,0,0,0,0,0),
            array("9h",0,0,0,0,0,0,0),
            array("10h",0,0,0,0,0,0,0),
            array("11h",0,0,0,0,0,0,0),
            array("12h",0,0,0,0,0,0,0),
            array("13h",0,0,0,0,0,0,0),
            array("14h",0,0,0,0,0,0,0),
            array("15h",0,0,0,0,0,0,0),
            array("16h",0,0,0,0,0,0,0),
            array("17h",0,0,0,0,0,0,0),
            array("18h",0,0,0,0,0,0,0),
            array("19h",0,0,0,0,0,0,0),
            array("20h",0,0,0,0,0,0,0),
            array("21h",0,0,0,0,0,0,0),
            array("22h",0,0,0,0,0,0,0),
            array("23h",0,0,0,0,0,0,0),
        );
    }

    /**
     * 現在時間から未来11時間分の天気予報情報をwebAPIから取得しレスポンス用の配列を作成する
     * @return ArrayObject
     */
    const ONE_HOUR = 3600;
    public static function getWeatherInfo($onedaydate=null,$twodaydate=null,$second_graph_flag=null){
    	/* 計算に必要なパラメータを準備 */
    	//Authのインスタンス化
    	$auth = Auth::instance();
    	$str_id = $auth->get_str_id();
    	$strData = self::selectBasicInfoForStrId($str_id);
    	//緯度経度取得
    	$latitude = $strData['latitude'];
    	$longitude = $strData['longitude'];
    	//現在のタイムスタンプ
    	$nowtimestamp = time();

    	/*openweathermapで天気情報取得（基準となる配列）*/
    	//曜日のプリセット
    	$week_name = array("日", "月", "火", "水", "木", "金", "土");
    	$weatherInfoTableData = array();
    	$response = array();
    	//api実行準備
    	$appid ='1a91ac37fb0b64e5fbd1ad9ccc94b87b';
    	$url = 'https://api.openweathermap.org/data/2.5/forecast?lat='.$latitude.'&lon='.$longitude.'&units=metric&appid='.$appid;
    	//curlの処理を始める合図(openweathermap)
    	$curl = curl_init($url);
    	//リクエストのオプションをセットしていく
    	curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'GET'); // メソッド指定
    	curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false); // 証明書の検証を行わない
    	curl_setopt($curl, CURLOPT_RETURNTRANSFER, true); // レスポンスを文字列で受け取る
    	//レスポンスを変数に入れる
    	$response = curl_exec($curl);
    	//curlの処理を終了
    	curl_close($curl);
    	//表示に必要な要素だけ抽出（リスト先頭から4つ）
        $list =  json_decode($response)->list;
        $owinfotmp = array();
    	$count = 0;
    	foreach($list as $data ){
    		$conarray = (array)$data;
    		$timestamp = $conarray['dt'];
    		$weathericon = $conarray['weather'][0]->icon;
    		//一つ目の天気情報が現在時間から1時間以上開いている場合
    		if($count == 0){
    			if($timestamp - $nowtimestamp > self::ONE_HOUR){
    				$tmpcalc = $timestamp - $nowtimestamp;
    				$eCount = (int)($tmpcalc / self::ONE_HOUR);
    				for($i=$eCount;$i>0;$i--){
    					//必要情報のみ取得（時間と天気情報）
    					$owinfotmp[] = array(
    							'timestamp' =>$timestamp-(self::ONE_HOUR*$i),
    							'date' => date('m/d',$timestamp-(self::ONE_HOUR*$i)),
    							'week' => $week_name[date('w',$timestamp-(self::ONE_HOUR*$i))],
    							'hour' => date('H時',$timestamp-(self::ONE_HOUR*$i)),
    							'icon_info' =>'http://openweathermap.org/img/w/'.$weathericon.'.png',
    					);
    				}
    			}
    			$owinfotmp[] = array(
    					'timestamp' =>$timestamp,
    					'date' => date('m/d',$timestamp),
    					'week' => $week_name[date('w',$timestamp)],
    					'hour' => date('H時',$timestamp),
    					'icon_info' =>'http://openweathermap.org/img/w/'.$weathericon.'.png',
    			);
    			$owinfotmp[] = array(
    					'timestamp' =>$timestamp+self::ONE_HOUR,
    					'date' => date('m/d',$timestamp+self::ONE_HOUR),
    					'week' => $week_name[date('w',$timestamp+self::ONE_HOUR)],
    					'hour' => date('H時',$timestamp+self::ONE_HOUR),
    					'icon_info' =>'http://openweathermap.org/img/w/'.$weathericon.'.png',
    			);
    		}else{
    			//必要情報のみ取得（時間と天気情報）
    			$owinfotmp[] = array(
    					'timestamp' =>$timestamp-self::ONE_HOUR,
    					'date' => date('m/d',$timestamp-self::ONE_HOUR),
    					'week' => $week_name[date('w',$timestamp-self::ONE_HOUR)],
    					'hour' => date('H時',$timestamp-self::ONE_HOUR),
    					'icon_info' =>'http://openweathermap.org/img/w/'.$weathericon.'.png',
    			);
    			$owinfotmp[] = array(
    					'timestamp' =>$timestamp,
    					'date' => date('m/d',$timestamp),
    					'week' => $week_name[date('w',$timestamp)],
    					'hour' => date('H時',$timestamp),
    					'icon_info' =>'http://openweathermap.org/img/w/'.$weathericon.'.png',
    			);
    			$owinfotmp[] = array(
    					'timestamp' =>$timestamp+self::ONE_HOUR,
    					'date' => date('m/d',$timestamp+self::ONE_HOUR),
    					'week' => $week_name[date('w',$timestamp+self::ONE_HOUR)],
    					'hour' => date('H時',$timestamp+self::ONE_HOUR),
    					'icon_info' =>'http://openweathermap.org/img/w/'.$weathericon.'.png',
    			);
    		}
    		//4回繰り返したら（11個の要素をが揃ったら）終了
    		$count++;
    		if($count >= 4 ){break;}
    	}
    	//全ての要素を8個になるまで縮める

    	$owinfo = array();
    	$tempkey = 0;
    	while(count($owinfo) < 8){
    		$owinfo[] = $owinfotmp[$tempkey];
    		$tempkey++;
    	}

    	 //darkskyapiで天気情報取得（基準となる配列に気温と降水量を当てはめていく
    	 $url = 'https://api.darksky.net/forecast/a10e7c1ad14f74f27a7279006bf326a9/'.$latitude.','.$longitude.'?units=si';
    	 // curlの処理を始める合図(openweathermap)
    	 $curl = curl_init($url);
    	 //リクエストのオプションをセットしていく
    	 curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'GET'); // メソッド指定
    	 curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false); // 証明書の検証を行わない
    	 curl_setopt($curl, CURLOPT_RETURNTRANSFER, true); // レスポンスを文字列で受け取る
    	 //レスポンスを変数に入れる
    	 $response = curl_exec($curl);
    	 // curlの処理を終了
    	 curl_close($curl);
    	 //openwethermapで抽出した時間と同じ予報情報を取得し、気温と降水量をマージして天気予報テーブル用のレスポンスを完成させる
    	 $list =  json_decode($response)->hourly->data;
    	 foreach($owinfo as $tmpInfo){
    	 	foreach($list as $data){
    	 		//日時
    	 		$timestamp = $data->time;
    	 		//気温
    	 		$temperature = $data->temperature;
    	 		//降水量
    	 		$rain = $data->precipIntensity;
    	 		if($tmpInfo['timestamp'] == $timestamp){
    	 			$weatherInfoTableData[] = array(
    	 					'timestamp' => $tmpInfo['timestamp'],
    	 					'date' => $tmpInfo['date'],
    	 					'week' => $tmpInfo['week'],
    	 					'hour' => $tmpInfo['hour'],
    	 					'icon_info' => $tmpInfo['icon_info'],
    	 					'temperture' => (int)$temperature,
    	 					'rain' => (int)$rain
    	 			);
    	 			continue 2;
    	 		}
    	 	}
    	 }

        if($second_graph_flag != null && $second_graph_flag == '1'){
            $weatherInfoGraphData = self::getTemperatureGraphData(
                $onedaydate == null ? date('Y-m-d',time()) : $onedaydate, 
                $twodaydate == null ? date('Y-m-d',time()) : $twodaydate
            );
        }else if($onedaydate != null){
            $weatherInfoGraphData = self::getTemperatureGraphData(
                $onedaydate == null ? date('Y-m-d',time()) : $onedaydate
            );
        }else{
            $weatherInfoGraphData = self::getTemperatureGraphData(
                date('Y-m-d',$nowtimestamp)
            );
        }

    	 return array(
    	 		'weatherinfotabledata' => $weatherInfoTableData,
    	 		'weatherinfographdata' => $weatherInfoGraphData,
    	 );
    }

    /**
     * addInformationForOnedayInfo - 日毎詳細表示用のデータに追加情報（気温・湿度）を追加する
     * @access datetime メイン表示で指定している日付(Y-m-d)
     * @access datetime 比較表示で指定している日付(Y-m-d)
     * @access arrayObject 気温湿度情報が抜けた状態の日毎表示用配列データ
     * @return arrayObject 新たに「temperture」「humidity」を追加した表示用データ
     */
    public static function addInformationForOnedayInfo($onedayDate=null,$twodayDate=null,$onedayinfo=array())
    {
        //メイン表示部
        if(!empty($onedayDate)){
            $work_array = $onedayinfo['oneday_date'];
            $wetherinfo = self::getPastWeatherInfo($onedayDate);

            foreach($wetherinfo as $key => $data){
                if ($key == 0) continue;

                $timestamp = strtotime($data['time']);
                
                $hm_current = '~'.date('H:i',$timestamp);
                $hm_prev = '~' .date('H:i',strtotime('-30 minute',$timestamp));

                $work_current = Arr::get($work_array,$hm_current,null);
                if(!is_null($work_current)){
                    $work_array[$hm_current]['temperature'] = $data['temperature'];
                    $work_array[$hm_current]['humidity'] = $data['humidity'];
                }
                $work_prev = Arr::get($work_array,$hm_prev,null);
                if(!is_null($work_current)){
                    $work_array[$hm_prev]['temperature'] = $data['temperature'];
                    $work_array[$hm_prev]['humidity'] = $data['humidity'];
                }
            }
            $tmp_timestamp = strtotime($onedayDate.'00:00:00');
            $tomorrow_date = date('Y-m-d',strtotime('+1 day',$tmp_timestamp));
            $com_wetherinfo = self::getPastWeatherInfo($tomorrow_date);
            $com_data = current($com_wetherinfo);

            $work_array['~23:30']['temperature'] = $work_array['~24:00']['temperature'] = $com_data['temperature'];
            $work_array['~23:30']['humidity'] = $work_array['~24:00']['humidity'] = $com_data['humidity'];
            $onedayinfo['oneday_date'] = $work_array;
        }

        //比較表示部
        if(!empty($twodayDate)){
            $work_array = $onedayinfo['twoday_date'];
            $wetherinfo = self::getPastWeatherInfo($twodayDate);
            
            foreach($wetherinfo as $key => $data){
                if ($key == 0) continue;

                $timestamp = strtotime($data['time']);
                $hm_current = '~'.date('H:i',$timestamp);
                $hm_prev = '~' .date('H:i',strtotime('-30 minute',$timestamp));

                $work_current = Arr::get($work_array,$hm_current,null);
                if(!is_null($work_current)){
                    $work_array[$hm_current]['temperature'] = $data['temperature'];
                    $work_array[$hm_current]['humidity'] = $data['humidity'];
                }
                $work_prev = Arr::get($work_array,$hm_prev,null);
                if(!is_null($work_current)){
                    $work_array[$hm_prev]['temperature'] = $data['temperature'];
                    $work_array[$hm_prev]['humidity'] = $data['humidity'];
                }
            }
            $tmp_timestamp = strtotime($twodayDate.'00:00:00');
            $tomorrow_date = date('Y-m-d',strtotime('+1 day',$tmp_timestamp));
            $com_wetherinfo = self::getPastWeatherInfo($tomorrow_date);
            $com_data = current($com_wetherinfo);
            
            $work_array['~23:30']['temperature'] = $work_array['~24:00']['temperature'] = $com_data['temperature'];
            $work_array['~23:30']['humidity'] = $work_array['~24:00']['humidity'] = $com_data['humidity'];
            $onedayinfo['twoday_date'] = $work_array;
        }

        return $onedayinfo;
    }

    /**
     * addInformationForWeekinfo - 週間詳細表示用のデータに追加情報（気温・湿度）を追加する
     * @access arrayObject 気温湿度情報が抜けた状態の週間表示用配列データ
     * @return arrayObject 新たに「temperture」「humidity」を追加した表示用データ
     */
    public static function addInformationForWeekinfo($oneweekDate,$twoweekDate,$weekinfo)
    {
        $work_array = $weekinfo['oneweek_date'];
        $timestamp = strtotime($oneweekDate);
        $count = 6;
        foreach($work_array as $key=>$data){
            //デマンド発生データなしのため気温湿度取得処理をスキップ
            $data['temperature'] = '-';
            $data['humidity'] = '-';

            //指定日付から過去１週間分
            $target_date = date('Y-m-d',strtotime('-'.$count.' day',$timestamp));

            if(!empty($oneweekDate)){
                //最大デマンドが記録されてる日時でのみ気象情報取得処理
                if($data['demand_kw'] > 0){
                    $search_datetime = date(
                        'Y-m-d H:00',
                        strtotime($target_date.' '.$data['electric_at'])
                    );
                    $wether_array = self::getPastWeatherInfo($target_date);
                    foreach($wether_array as $weather){
                        if($weather['time'] == $search_datetime){
                            $data['temperature'] = $weather['temperature'];
                            $data['humidity'] = $weather['humidity'];
                            break;
                        }
                    }
                }
            }
            $work_array[$key] = $data;
            $count--;
        }
        $weekinfo['oneweek_date'] = $work_array;
        
        $work_array = $weekinfo['twoweek_date'];
        $timestamp = strtotime($twoweekDate);
        $count = 6;
        foreach($work_array as $key=>$data){
            $data['temperature'] = '-';
            $data['humidity'] = '-';

            //指定日付から過去１週間分
            $target_date = date('Y-m-d',strtotime('-'.$count.' day',$timestamp));

            if(!empty($twoweekDate)){
                //最大デマンドが記録されてる日時でのみ気象情報取得処理
                if($data['demand_kw'] > 0){
                    $search_datetime = date(
                        'Y-m-d H:00',
                        strtotime($target_date.' '.$data['electric_at'])
                    );
                    $wether_array = self::getPastWeatherInfo($target_date);
                    foreach($wether_array as $weather){
                        if($weather['time'] == $search_datetime){
                            $data['temperature'] = $weather['temperature'];
                            $data['humidity'] = $weather['humidity'];
                            break;
                        }
                    }
                }
            }
            $work_array[$key] = $data;
            $count--;
        }
        $weekinfo['twoweek_date'] = $work_array;

        return $weekinfo;
    }

    /**
     * addInformationForMonthinfo - 月間詳細表示用のデータに追加情報（気温・湿度）を追加する
     * @access arrayObject 気温湿度情報が抜けた状態の月間表示用配列データ
     * @return arrayObject 新たに「temperture」「humidity」を追加した表示用データ
     */
    public static function addInformationForMonthinfo($onemonthDate,$monthinfo)
    {
        $work_array = $monthinfo['onemonth_date'];
        
        foreach($work_array as $key => $data){
            $data['temperature'] = '-';
            $data['humidity'] = '-';
                
            if(!empty($onemonthDate)){
                $base_target = date('Y-m',strtotime($onemonthDate));
                $target_date = $base_target.'-'.substr($key,0,2);

                if($data[1] > 0){
                    $search_datetime = date(
                        'Y-m-d H:00',
                        strtotime($target_date.' '.$data[2])
                    );
    
                    $wether_array = self::getPastWeatherInfo($target_date);
                    foreach($wether_array as $weather){
                        if($weather['time'] == $search_datetime){
                            $data['temperature'] = $weather['temperature'];
                            $data['humidity'] = $weather['humidity'];
                            break;
                        }
                    }
                }
                $work_array[$key] = $data;
            }
        }
        $monthinfo['onemonth_date'] = $work_array;

        return $monthinfo;
    }

    /**
     * addInformationForYearinfo - 年間詳細表示用のデータに追加情報（気温・湿度）を追加する
     * @access arrayObject 気温湿度情報が抜けた状態の年間表示用配列データ
     * @return arrayObject 新たに「temperture」「humidity」を追加した表示用データ
     */
    public static function addInformationForYearinfo($oneyearDate, $twoyearDate,$yearinfo)
    {
        $work_array = $yearinfo['oneyear_electric'];
        foreach($work_array as $key => $data){
            //デマンド発生データなしのため気温湿度取得処理をスキップ
            $data['temperature'] = '-';
            $data['humidity'] = '-';

            if(!empty($oneyearDate)){
                $year = date('Y',strtotime($oneyearDate));
                $month = substr($key,0,2);
                $day = substr($data[2],0,2);
                $time = substr($data[2],5);
                $target_date = $year.'-'.$month.'-'.$day;
                if($data[1] > 0){
                    $search_datetime = date(
                        'Y-m-d H:00',
                        strtotime($target_date.' '.$time)
                    );
                    $wether_array = self::getPastWeatherInfo($target_date);
                    foreach($wether_array as $weather){
                        if($weather['time'] == $search_datetime){
                            $data['temperature'] = $weather['temperature'];
                            $data['humidity'] = $weather['humidity'];
                            break;
                        }
                    }
                }
            }
            $work_array[$key] = $data;
        }
        $yearinfo['oneyear_electric'] = $work_array;

        $work_array = $yearinfo['twoyear_electric'];
        foreach($work_array as $key => $data){
            //デマンド発生データなしのため気温湿度取得処理をスキップ
            $data['temperature'] = '-';
            $data['humidity'] = '-';

            if(!empty($twoyearDate)){
                $year = date('Y',strtotime($twoyearDate));
                $month = substr($key,0,2);
                $day = substr($data[2],0,2);
                $time = substr($data[2],5);
                $target_date = $year.'-'.$month.'-'.$day;
                if($data[1] > 0){
                    $search_datetime = date(
                        'Y-m-d H:00',
                        strtotime($target_date.' '.$time)
                    );
                    $wether_array = self::getPastWeatherInfo($target_date);
                    foreach($wether_array as $weather){
                        if($weather['time'] == $search_datetime){
                            $data['temperature'] = $weather['temperature'];
                            $data['humidity'] = $weather['humidity'];
                            break;
                        }
                    }
                }
            }
            $work_array[$key] = $data;
        }
        $yearinfo['twoyear_electric'] = $work_array;

        return $yearinfo;
    }



    
    /**
     * 指定日付の0〜24時までの気温・湿度情報を取得
     * @access datetime 'YYYY-mm-dd 00:00:00'
     */
    private static function getPastWeatherInfo($datetime = null)
    {
        //Authのインスタンス化
        $auth = Auth::instance();
        $str_id = $auth->get_str_id();
        $strData = self::selectBasicInfoForStrId($str_id);
        //緯度経度取得
        $latitude = $strData['latitude'];
        $longitude = $strData['longitude'];
        
        //指定日付のタイムスタンプ
        $timestamp = strtotime($datetime);
        $check = date('Y-m-d H:i',$timestamp);
        
        $api = new Model_Api_Weather();

        $response = $api->getWeather($latitude,$longitude,$timestamp);

        $list =  json_decode($response)->hourly->data;

        $result = array();

        //時間・気温・湿度のみを抽出

        for($i=0;$i<24;$i++){
            $tmp_time = date("Y-m-d H:i",strtotime($check . "+$i hour"));
            foreach($list as $key=>$data){
                $time = date('Y-m-d H:i',$data->time);
                $temperature = '-';
                $humidity = '-';
                if($tmp_time == $time){
                    $temperature = $data->temperature;
                    $humidity = $data->humidity * 100;
                    break;
                }
            }
            $result[] = array(
                'time' => $tmp_time,
                'temperature' => $temperature,
                'humidity' => $humidity
            );
        }

        return $result;
    }


    /**
     * グラフ用気温データ作成
     */
    private static function getTemperatureGraphData($target_date_1=null,$target_date_2=null){

        if($target_date_1 != null && $target_date_2 != null){
            //比較日付指定時の気温グラフ作成
            $target1 = date('Y-m-d\T00:00:00',strtotime($target_date_1));
            $target_timestsmp_1 = strtotime($target1);
            
            $target2 = date('Y-m-d\T00:00:00',strtotime($target_date_2));
            $target_timestsmp_2 = strtotime($target2);
            
            $list1 = self::getPastWeatherInfo($target1);
            $list2 = self::getPastWeatherInfo($target2);

            $weatherInfoGraphData = array(array("",date('Y-m-d',strtotime($target1)),date('Y-m-d',strtotime($target2))));
            
    	    foreach($list1 as $key => $data){
                $time = date('H',strtotime($data['time']));
                $temp1 = $data['temperature'] == '-' ? null : $data['temperature'];
                $temp2 = $list2[$key]['temperature'] == '-' ? null : $list2[$key]['temperature'];
                $weatherInfoGraphData[] = array("$time",$temp1,$temp2);
            }
            $tomorrow1 = date('Y-m-d',strtotime('+1 day',$target_timestsmp_1));
            $tomorrow2 = date('Y-m-d',strtotime('+1 day',$target_timestsmp_2));
            $list1 = self::getPastWeatherInfo($tomorrow1);
            $list2 = self::getPastWeatherInfo($tomorrow2);
            $weatherInfoGraphData[] = array("24",$list1[0]['temperature'],$list2[0]['temperature']);
        }else{
            //メイン日付のみ指定時の気温グラフ作成
            if($target_date_1 == null){
                $target1 = date('Y-m-d\T00:00:00',time());
                $target_timestsmp = time();
            }else{
                $target1 = date('Y-m-d\T00:00:00',strtotime($target_date_1));
                $target_timestsmp = strtotime($target1);
            }
            $list = self::getPastWeatherInfo($target1);
    	    $weatherInfoGraphData = array(array("",date('Y-m-d',strtotime($target1))));
    	    foreach($list as $data){
                $time = date('H',strtotime($data['time']));
                $temp = $data['temperature'] == '-' ? null : $data['temperature'];
                $weatherInfoGraphData[] = array("$time",$temp);
            }
            $tomorrow = date('Y-m-d',strtotime('+1 day',$target_timestsmp));
            $list = self::getPastWeatherInfo($tomorrow);
            $weatherInfoGraphData[] = array("24",$list[0]['temperature']);
        }
        return $weatherInfoGraphData;
    }
}