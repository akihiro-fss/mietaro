<?php
/**
 *
 * 作成日：2017/1/12
 * 更新日：2017/1/23
 * 作成者：丸山　隼
 * 更新者：丸山　隼
 *
 */

/**
 * The Electric Model.
 *
 * 詳細表示用のモデルクラス
 *
 * @package app
 * @extends Model
 *
 *
 */
use Orm\Observer;
class Model_ElectricInfo extends \orm\Model {

    //DBへのセレクトクエリメソッド
    private static function selectElectricData($strId,$date_start,$date_end){
        $sql = "SELECT electric_at, str_id, electric_kw,demand_kw FROM Electric WHERE str_id = $strId AND electric_at >= '$date_start' AND electric_at <= '$date_end'";
        return \DB::query($sql)->execute()->as_array();
    }

    /**
     *  店舗の基本情報取得
     */
    private static function selectBasicInfoForStrId($str_id){
    	$sql = "SELECT * FROM BasicInfo WHERE str_id = $str_id";
    	return \DB::query($sql)->execute()->current();
    }

    //一日使用電力詳細表示用データ取得
    public static function getOnedayData($strId,$onedayDate = "",$twodayDate = ""){

        //計算結果格納用配列
        $result = array(
            'oneday_date' => array(),
            'twoday_date' => array(),
            'oneday_total' => 0,
            'twoday_total' => 0,
            'param_date_1' => '',
            'param_date_2' => '',
        	'total_emission_1' => 0,
        	'total_emission_2' => 0,
        	'total_price_1' => 0,
        	'total_price_2' => 0,
        );

        //店舗情報取得
        $strData = self::selectBasicInfoForStrId($strId);
        //CO2排出係数
        $emisionFactor = (float)$strData['emission_factor'];

        //原油換算係数
        $conversionFactor = (float)$strData['conversion_factor'];

        //メイン
        if($onedayDate == ""){
            $onedayDate = date('Y-m-d');
        }
        //取得した日時の時間を整形
        $onedayStart = date('Y-m-d 00:00:00', strtotime($onedayDate));
        $onedayEnd = date('Y-m-d 23:59:59', strtotime($onedayDate));

            //時間計算用配列
            $targetTimeArray = array(
                'start' => array(),
                'end' => array(),
            );
            for($i=1;$i<=48;$i++){
                $minutesStart = "+". 30 * ($i-1) . " minutes";
                $minutesEnd = "+". 30 * $i . " minutes";
                $targetTimeArray['start'][] = date('Y-m-d H:i:s', strtotime("$minutesStart -1 seconds",strtotime($onedayStart)));//よりも大きい
                $targetTimeArray['end'][] = date('Y-m-d H:i:s', strtotime("$minutesEnd -1 seconds",strtotime($onedayStart)));//以下
            }

            //対象の日付の電力情報を抽出
            $onedayElectricData = self::selectElectricData($strId, $onedayStart,$onedayEnd);

            //抽出した電力情報を30分毎に分割した合計値を配列に格納
            for($i=0;$i<=47;$i++){
                $result['oneday_date'] = array_merge($result['oneday_date'],array($targetTimeArray['end'][$i]=>array(0,0)));
                foreach($onedayElectricData as $index => $data){
                    if(strtotime($targetTimeArray['start'][$i]) < strtotime($data['electric_at']) && strtotime($targetTimeArray['end'][$i]) >= strtotime($data['electric_at'])){
                        $result['oneday_date'][$targetTimeArray['end'][$i]] = array($data['electric_kw'],$data['demand_kw']);
                        $result['oneday_total'] += $data['electric_kw'];
                    }
                }

            }
            //テーブル表示用に配列のキーを書き換え
            $conArray = array();
            $minutesNum = 30;
            $key = 1;
            foreach($result['oneday_date'] as $data){
            	$minutes = 0;
            	$minutes = $minutesNum * $key;
            	$minutes = date('H:i',strtotime("00:00"."+ $minutes minutes"));
            	if($minutes == "00:00"){
            		$minutes = "24:00";
            	}
                $conArray = array_merge($conArray,array("~".$minutes=>array($data[0],$data[1])));
                $key++;
            }

            $result['oneday_date'] = $conArray;

        //比較用
        if($twodayDate == ""){
            $twodayDate = date('Y-m-d',strtotime('-1 days'));
        }

        $twodayStart = date('Y-m-d 00:00:00', strtotime($twodayDate));
        $twodayEnd = date('Y-m-d 23:59:59', strtotime($twodayDate));

            //時間計算用配列
            $targetTimeArray = array(
                'start' => array(),
                'end' => array(),
            );
            for($i=1;$i<=48;$i++){
                $minutesStart = "+". 30 * ($i-1) . " minutes";
                $minutesEnd = "+". 30 * $i . " minutes";
                $targetTimeArray['start'][] = date('Y-m-d H:i:s', strtotime("$minutesStart -1 seconds",strtotime($twodayStart)));//よりも大きい
                $targetTimeArray['end'][] = date('Y-m-d H:i:s', strtotime("$minutesEnd -1 seconds",strtotime($twodayStart)));//以下
            }

            //対象の日付の電力情報を抽出
            $twodayElectricData = self::selectElectricData($strId, $twodayStart,$twodayEnd);

            //抽出した電力情報を30分毎に分割した合計値を配列に格納
            for($i=0;$i<=47;$i++){
                $result['twoday_date'] = array_merge($result['twoday_date'],array($targetTimeArray['end'][$i]=>array(0,0)));
                foreach($twodayElectricData as $index => $data){
                    if(strtotime($targetTimeArray['start'][$i]) < strtotime($data['electric_at']) && strtotime($targetTimeArray['end'][$i]) >= strtotime($data['electric_at'])){
                        $result['twoday_date'][$targetTimeArray['end'][$i]] = array($data['electric_kw'],$data['demand_kw']);
                        $result['twoday_total'] += $data['electric_kw'];
                    }
                }
            }

            //テーブル表示用に配列のキーを書き換え
            $conArray = array();
            $minutesNum = 30;
            $key = 1;
            foreach($result['twoday_date'] as $data){
            	$minutes = 0;
            	$minutes = $minutesNum * $key;
            	$minutes = date('H:i',strtotime("00:00"."+ $minutes minutes"));
            	if($minutes == "00:00"){
            		$minutes = "24:00";
            	}
            	$conArray = array_merge($conArray,array("~".$minutes=>array($data[0],$data[1])));
            	$key++;
            }
            $result['twoday_date'] = $conArray;

            $result['param_date_1'] = date('Y-m-d',strtotime($onedayStart));
            $result['param_date_2'] = date('Y-m-d',strtotime($twodayStart));
            $result['total_emission_1'] = floor($result['oneday_total'] * $emisionFactor);
            $result['total_emission_2'] = floor($result['twoday_total']  * $emisionFactor);
            $result['total_price_1'] = floor($result['oneday_total'] * $conversionFactor);
            $result['total_price_2'] = floor($result['twoday_total'] * $conversionFactor);
            $result['conversion_factor'] = $conversionFactor;

        return $result;
    }


    //週間使用電力詳細表示用データ取得
    public static function getWeekData($strId,$oneweekDate = "",$twoweekDate = ""){

        //計算結果格納用配列
        $result = array(
            'oneweek_date' => array(),
            'twoweek_date' => array(),
            'oneweek_total' => 0,
            'twoweek_total' => 0,
            'param_date_1' => '',
            'param_date_2' => '',
        	'total_emission_1' => 0,
        	'total_emission_2' => 0,
        	'total_price_1' => 0,
        	'total_price_2' => 0,
        );

        //　店舗情報取得
        $strData = self::selectBasicInfoForStrId($strId);
        //CO2排出係数
        $emisionFactor = (float)$strData['emission_factor'];
        //　原油換算係数
        $conversionFactor = (float)$strData['conversion_factor'];

        $result1 = array();
        $result2 = array();
        //メイン
        if($oneweekDate == ""){
            $oneweekDate = date('Y-m-d');
        }
        $oneweekStart = date('Y-m-d 00:00:00', strtotime("-1 week",strtotime($oneweekDate)));
        $oneweekEnd = date('Y-m-d 23:59:59', strtotime($oneweekDate));

        $result1 = self::selectElectricData($strId, $oneweekStart,$oneweekEnd);
        //比較
        if($twoweekDate == ""){
            $twoweekDate = date('Y-m-d',strtotime('-1 week',strtotime($oneweekDate)));
        }
        $twoweekStart = date('Y-m-d 00:00:00', strtotime("-1 week",strtotime($twoweekDate)));
        $twoweekEnd = date('Y-m-d 23:59:59', strtotime($twoweekDate));

        $result2= self::selectElectricData($strId, $twoweekStart,$twoweekEnd);
        //週間使用電力取得
        $convertResult = \Model_Electric::convertDataForWeek($result1,$result2,$oneweekDate,$twoweekDate);

        $oneweekDateArray = $convertResult['one_week'][0];
        $twoweekDateArray = $convertResult['two_week'][0];
        $demandArray1 = $convertResult['one_week_demand'];
        $demandArray2 = $convertResult['two_week_demand'];

        $tmpDate = date('Y-m-', strtotime($oneweekDate));
        $week = array('日','月','火','水','木','金','土');

        foreach($oneweekDateArray as $index=>$date){
                if($index==0){continue;}
                $calcDateArray=explode('-',$date);
                $tmpDateTime = new DateTime($tmpDate.$calcDateArray[2]);
                $w = (int)$tmpDateTime->format('w');

                $result['oneweek_date'] = array_merge($result['oneweek_date'],array($calcDateArray[2].'日('."$week[$w]".')'=>array('total' => 0,'demand_kw' => 0,'electric_at' => "-")));

                foreach($convertResult['one_week'] as $key=>$dataArray){
                      if($key==0){continue;}
                      //電力の小計を加算
                      $result['oneweek_date'][$calcDateArray[2].'日('."$week[$w]".')']['total'] += (int)$dataArray[$index];
                      //デマンド値セット
                      $result['oneweek_date'][$calcDateArray[2].'日('."$week[$w]".')']['demand_kw'] = $demandArray1[$index][1]['demand_kw'];
                      //デマンド発生日時セット
                      $result['oneweek_date'][$calcDateArray[2].'日('."$week[$w]".')']['electric_at'] = $demandArray1[$index][1]['electric_at'];
                      //すべての合計値加算
                      $result['oneweek_total'] += (int)$dataArray[$index];
                }
        }

        $tmpDate = date('Y-m-', strtotime($twoweekDate));
        foreach($twoweekDateArray as $index=>$date){
                if($index==0){continue;}
                $calcDateArray=explode('-',$date);
                $tmpDateTime = new DateTime($tmpDate.$calcDateArray[2]);
                $w = (int)$tmpDateTime->format('w');

                $result['twoweek_date'] = array_merge($result['twoweek_date'],array($calcDateArray[2].'日('."$week[$w]".')'=>array('total' => 0,'demand_kw' => 0,'electric_at' => "-")));
                foreach($convertResult['two_week'] as $key=>$dataArray){
                    if($key==0){continue;}
                    //電力の小計を加算
                    $result['twoweek_date'][$calcDateArray[2].'日('."$week[$w]".')']['total'] += (int)$dataArray[$index];
                    //デマンド値セット
                    $result['twoweek_date'][$calcDateArray[2].'日('."$week[$w]".')']['demand_kw'] = $demandArray2[$index][1]['demand_kw'];
                    //デマンド発生日時セット
                    $result['twoweek_date'][$calcDateArray[2].'日('."$week[$w]".')']['electric_at'] = $demandArray2[$index][1]['electric_at'];
                    //すべての合計値加算
                    $result['twoweek_total'] += (int)$dataArray[$index];
                }
        }
        $result['param_date_1'] = $oneweekDate;
        $result['param_date_2'] = $twoweekDate;
        $result['total_emission_1'] = floor($result['oneweek_total'] * $emisionFactor);
        $result['total_emission_2'] = floor($result['twoweek_total']  * $emisionFactor);
        $result['total_price_1'] = floor($result['oneweek_total'] * $conversionFactor);
        $result['total_price_2'] = floor($result['twoweek_total'] * $conversionFactor);

        return $result;
    }


    //週間使用電力詳細表示用データ取得
    public static function getMonthData($strId,$onemonthDate = ""){

        //計算結果格納用配列
        $result = array(
            'onemonth_date' => array(),
            'onemonth_total' => 0,
        	'total_emission' => 0,
        	'total_price' => 0,
        );

        //　店舗情報取得
        $strData = self::selectBasicInfoForStrId($strId);
        //CO2排出係数
        $emisionFactor = (float)$strData['emission_factor'];
        //　原油換算係数
        $conversionFactor = (float)$strData['conversion_factor'];

        $result1 = array();

        //メイン
        if($onemonthDate == ""){
            $onemonthDate = date('Y-m-d');
        }
        $onemonthStart = date('Y-m-1 00:00:00', strtotime($onemonthDate));
        $onemonthEnd = date('Y-m-d 23:59:59', strtotime("-1 days ",strtotime(date('Y-m-1 00:00:00', strtotime("+1 MONTH ",strtotime($onemonthStart))))));

        $result1 = self::selectElectricData($strId, $onemonthStart,$onemonthEnd);

        //日毎に電力情報を取得
        $convertResult = Model_Electric::convertDataForMonth($result1,array(),$onemonthStart,$onemonthEnd,"","",0);

        $tmpDate = date('Y-m-', strtotime($onemonthDate));
        $week = array('日','月','火','水','木','金','土');
        foreach($convertResult['result'] as $index=>$arrayData){
        	if($index == 0){continue;}
        	//同日のデマンド値取得
        	$demandKw = $convertResult['result_demand'][$index][1];
        	//発生日時取得
        	$demandAt = $convertResult['result_demand_at'][$index][1];
        	if($demandAt == null){
        		$demandAt = "-";
        	}else{
        		$demandAt = date('H:i',strtotime($demandAt));
        	}
        	//$demandAt = $convertResult['result_demand_triger'][$index][1];
            //曜日を計算
            $tmpDateTime = new DateTime($tmpDate.$arrayData[0]);
            $w = (int)$tmpDateTime->format('w');

            //レスポンス用配列作成
            $result['onemonth_date'] = array_merge(
            		$result['onemonth_date'],array($arrayData[0]."($week[$w])"=>array((int)$arrayData[1],$demandKw,$demandAt))
            );
        }

        $result['onemonth_total'] = $convertResult['total_one_month'];
        $result['param_date_1'] = $onemonthDate;
        $result['total_emission'] = floor($result['onemonth_total'] * $emisionFactor);
        $result['total_price'] = floor($result['onemonth_total'] * $conversionFactor);

        return $result;
    }


    //週間使用電力詳細表示用データ取得
    public static function getYearData($strId,$oneyearDate = "",$twoyearDate = ""){

        //計算結果格納用配列
        $result = array(
        	'oneyear_electric' => array(),
        	'twoyear_electric' => array(),
        	'oneyear_demand' => array(),
        	'twoyear_demand' => array(),
        	'oneyear_total' => 0,
        	'twoyear_total' => 0,
        	'total_emission_1' => 0,
        	'total_emission_2' => 0,
        	'total_price_1' => 0,
        	'total_price_2' => 0,
        );

        //　店舗情報取得
        $strData = self::selectBasicInfoForStrId($strId);
        //CO2排出係数
        $emisionFactor = (float)$strData['emission_factor'];
        //　原油換算係数
        $conversionFactor = (float)$strData['conversion_factor'];

        //メイン
        if($oneyearDate == ""){
            $oneyearDate = date('Y-m-d');
        }
        $oneyearStart = date('Y-01-01 00:00:00', strtotime($oneyearDate));
        $oneyearEnd = date('Y-12-31 23:59:59', strtotime($oneyearDate));

        $result1 = self::selectElectricData($strId, $oneyearStart,$oneyearEnd);
        //比較
        $result2 = array();
        $checkedFlg = 0;
        $twoyearStart = "";
        $twoyearEnd = "";
        if($twoyearDate == ""){
            $twoyearDate = date('Y-m-d',strtotime('-1 years'));
        }
        $twoyearStart = date('Y-01-01 00:00:00', strtotime($twoyearDate));
        $twoyearEnd = date('Y-12-31 23:59:59', strtotime($twoyearDate));

        $result2 = self::selectElectricData($strId, $twoyearStart,$twoyearEnd);
        //月毎に電力情報を取得
        $convertResult = Model_Electric::convertDataForYear($result1,$result2,$oneyearStart,$oneyearEnd,$twoyearStart,$twoyearEnd,1);
        //電力量・デマンド値・デマンド発生日時
        foreach($convertResult['result'] as $index=>$arrayData){
        	if($index == 0){continue;}
        	//同日のデマンド値取得
        	$demandKw = $convertResult['result_demand'][$index][1];
        	//デマンド発生日時取得
        	$demandAt = $convertResult['result_demand_at'][$index][1];
        	if($demandAt == null){
        		$demandAt = '-';
        	}else{
        		$demandAt = date('d日 H:i',strtotime($demandAt));
        	}
        	//レスポンス用配列作成
        	$result['oneyear_electric'] = array_merge(
        			$result['oneyear_electric'],array($arrayData[0]."月"=>array((int)$arrayData[1],$demandKw,$demandAt,))
        	);
        }
        foreach($convertResult['result'] as $index=>$arrayData){
        	if($index == 0){continue;}
        	//同日のデマンド値取得
        	$demandKw = $convertResult['result_demand'][$index][2];
        	//デマンド発生日時取得
        	$demandAt = $convertResult['result_demand_at'][$index][2];
        	if($demandAt == null){
        		$demandAt = '-';
        	}else{
        		$demandAt = date('d日 H:i',strtotime($demandAt));
        	}
        	//レスポンス用配列作成
        	$result['twoyear_electric'] = array_merge(
        		$result['twoyear_electric'],array($arrayData[0]."月"=>array((int)$arrayData[2],$demandKw,$demandAt,))
        	);
        }

        $result['oneyear_total'] = $convertResult['total_one_year'];
        $result['twoyear_total'] = $convertResult['total_two_year'];

        $result['param_date_1'] = $oneyearDate;
        $result['param_date_2'] = $twoyearDate;
        $result['total_emission_1'] = floor($result['oneyear_total'] * $emisionFactor);
        $result['total_emission_2'] = floor($result['twoyear_total']  * $emisionFactor);
        $result['total_price_1'] = floor($result['oneyear_total'] * $conversionFactor);
        $result['total_price_2'] = floor($result['twoyear_total'] * $conversionFactor);

        return $result;
    }
}
