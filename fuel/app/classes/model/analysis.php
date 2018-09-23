<?php

/**
 *
 * 作成日：2018/08/14
 * 更新日：2018/09/23
 * 作成者：戸田滉洋
 * 更新者：戸田滉洋
 *
 */

/**
 * The Electric Model.
 *
 * 分析用データ取得
 * @package app
 * @extends Model
 *
 *
 */
use Orm\Observer;

class Model_analysis extends \orm\Model
{
    protected static $_table_name = 'Electric';
    protected static $_primary_key = array('electric_id');
    protected static $_properties = array(
        'electric_id',
        'electric_at',
        'str_id',
        'electric_kw',
        'demand_kw',
        'powre_rate',
        'created_at',
    );
    protected static $_observers = array(
        'Orm\Observer_CreatedAt' => array(
            'events' => array('before_insert'),
            'mysql_timestamp' => false,
        ),
    );

    //　分析用グラフのデータ作成
    public static function analysisdata($str_id, $starttime, $endtime)
    {
        $sql = "SELECT electric_at, str_id, electric_kw FROM Electric WHERE str_id = $str_id and electric_at BETWEEN '$starttime' AND '$endtime'";
        $query = \DB::query($sql)->execute();
        $data = Model_analysis::calclation($query);
        return $data;
    }

    //　分析用の使用電力量配列の作成
    private static function calclation($query)
    {
        $data = $query;
        $totaldata = array();
        foreach ($data as $key => $val) {
            $timedata = (int) floor($val['electric_kw'] / 3);
            $totaldata[] = $timedata;
            $totaldata[] = $timedata;
            $totaldata[] = $timedata;

            //  Debug::dump($totaldata);
        }
        return $totaldata;
    }

    // 分析の詳細データで使用するデマンド値の取得
    public static function demand_analysis($str_id, $starttime, $endtime)
    {
        $sql = "SELECT electric_at, str_id, demand_kw FROM Electric WHERE str_id = $str_id and electric_at BETWEEN '$starttime' AND '$endtime'";
        $query = \DB::query($sql)->execute();
        $data = Model_analysis::demandcalc($query);
        // Debug::dump($query);
        return $data;
    }
    // 分析の詳細データで使用する30分の使用電力量を取得
    public static function electric_m($str_id, $starttime, $endtime)
    {
        $sql = "SELECT electric_at, str_id, electric_kw FROM Electric WHERE str_id = $str_id and electric_at BETWEEN '$starttime' AND '$endtime'";
        $query = \DB::query($sql)->execute();
        $data = Model_analysis::electriccalc($query);
        // Debug::dump($query);
        return $data;
    }

    // 分析詳細のデマンド値の配列作成
    private static function demandcalc($query)
    {
        $data = $query;
        $calcdata = array();
        foreach ($data as $key => $val) {
            $calcdata[] = $val['demand_kw'];
            $calcdata[] = null;
            $calcdata[] = null;
        }
        return $calcdata;
    }
    // 分析詳細の30分の使用電力量の配列作成
    private static function electriccalc($query)
    {
        $data = $query;
        $calcdata = array();
        foreach ($data as $key => $val) {
            $calcdata[] = $val['electric_kw'];
            $calcdata[] = null;
            $calcdata[] = null;
        }
        return $calcdata;
    }

    // 分析の詳細データの個別データの配列作成
    public static function total_analysisinfo($today, $date_array, $totaldata)
    {
        $count_date_array = count($date_array);
        for ($i = 0; $i < $count_date_array; ++$i) {
            if ($i == 0) {
                $array_date[''] = $today;
                if (isset($totaldata[$i])) {
                    $array_date[$date_array[$i]] = $totaldata[$i];
                } else {
                    $array_date[$date_array[$i]] = 0;
                }
            } elseif (isset($totaldata[$i])) {
                // Debug::dump($date_array[$i]);
                $array_date[$date_array[$i]] = $totaldata[$i];
            } else {
                $array_date[$date_array[$i]] = 0;
            }
        }
        return $array_date;
    }
    
    // 最大デマンド値の取得
    public static function getdemandMax()
    {
        $auth = Auth::instance();
        $str_id = $auth->get_str_id();
        $today = new DateTime();
        $month = $today->modify('-1 month')->format('Y-m-d');
        //Debug::dump($month);
        $sql = "SELECT demand_kw From Electric WHERE str_id = $str_id and electric_at = $month ORDER BY electric_at DESC LIMIT 1";
        $query = \DB::query($sql)->execute()->current();
        $data =$query;
        return $data;
    }

    // 分析用詳細表の合計の使用電力量を取得
    public static function getTotalElectric($str_id, $starttime, $endtime)
    {
        $sql = "SELECT electric_at, str_id, electric_kw FROM Electric WHERE str_id = $str_id and electric_at BETWEEN '$starttime' AND '$endtime'";
        $query = \DB::query($sql)->execute();
        $total_electric = Model_analysis::calTotalElectric($query);
        return $total_electric;
    }

    // 分析用詳細表の合計使用電力量をお計算
    private static function calTotalElectric($query)
    {
        $data = $query;
        $totaldata = 0;
        foreach ($data as $key => $val) {
            $totaldata = $totaldata + $val['electric_kw'];
        }
        return $totaldata;
    }

    // 検証用で使用する月ごとのco2排出量を計算
    public static function getYEfactor($year_electric, $emission_factor)
    {
        // $oneyear_electricのco2排出量を計測
        foreach ($year_electric as $key => $val) {
            $year_emission[$key] = floor($val[0] * $emission_factor);
        }
        return $year_emission;
    }

    // 使用電力量の比率を計算
    public static function getERaito($oneyear_electric, $twoyear_electric)
    {
        foreach ($oneyear_electric as $key => $val) {
            if ($val[0] == 0 || $twoyear_electric[$key][0] == 0) {
                $electric_raito[$key] = 0;
            } else {
                $electric_raito[$key] = floor($val[0]/$twoyear_electric[$key][0]*100);
            }
        }
        return $electric_raito;
    }

    // 最大デマンド値の比率を計算
    public static function getDRaito($oneyear_electric, $twoyear_electric)
    {
        foreach ($oneyear_electric as $key => $val) {
            if ($val[1] == 0 || $twoyear_electric[$key][1] == 0) {
                $demand_raito[$key] = 0;
            } else {
                $demand_raito[$key] = floor($val[1]/$twoyear_electric[$key][1]*100);
            }
        }
        return $demand_raito;
    }

    // 使用電力の削減量計算
    public static function calcER($oneyear_electric, $twoyear_electric)
    {
        foreach ($oneyear_electric as $key => $val) {
            $electric_R[$key] = $twoyear_electric[$key][0] - $val[0];
        }
        return $electric_R;
    }

    // デマンド値の削減量計算
    public static function calcDR($oneyear_electric, $twoyear_electric)
    {
        foreach ($oneyear_electric as $key => $val) {
            $demand_R[$key] = $twoyear_electric[$key][1] - $val[1];
        }
        return $demand_R;
    }

    // CO2の削減量計算
    public static function calcCOR($oneyear_emission, $twoyear_emission)
    {
        foreach ($oneyear_emission as $key => $val) {
            $emission_R[$key] = $twoyear_emission[$key] - $val;
        }
        return $emission_R;
    }

    // 使用電力量の合計を計算
    public static function calcETotal($year_electric)
    {
        $year_ETotal = 0;
        foreach ($year_electric as $key => $val) {
            $year_ETotal = $year_ETotal + $val[0];
        }
        return $year_ETotal;
    }

    // 年間の最大デマンド値を計算
    public static function maxYDemand($year_electric)
    {
        $year_MaxD[0] = 0;
        $year_MaxD[1] = "";
        foreach ($year_electric as $key => $val) {
            if ($year_MaxD[0] <= $val[1]) {
                $year_MaxD[0] = $val[1];
                $year_MaxD[1] = $val[2];
            }
        }
        return $year_MaxD;
    }

    // CO2排出量の合計を計算
    public static function calcCTotal($year_emission)
    {
        $year_CTotal = 0;
        foreach ($year_emission as $key => $val) {
            $year_CTotal = $year_CTotal + $val;
        }
        return $year_CTotal;
    }
}
