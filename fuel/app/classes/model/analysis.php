<?php
/**
 *
 * 作成日：2018/08/14
 * 更新日：
 * 作成者：戸田滉洋
 * 更新者：
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
class Model_analysis extends \orm\Model {

    protected static $_table_name = 'Electric';
    protected static $_primary_key = array('electric_id');
    protected static $_properties = array(
        'electric_id',
        'electric_at',
        'str_id',
        'electric_kw',
        'demand_kw',
        'created_at',
    );
    protected static $_observers = array(
        'Orm\Observer_CreatedAt' => array(
            'events' => array('before_insert'),
            'mysql_timestamp' => false,
        ),
    );

    public static function analysisdata($str_id,$starttime,$endtime){
      $sql = "SELECT electric_at, str_id, electric_kw FROM Electric WHERE str_id = $str_id and electric_at BETWEEN '$starttime' AND '$endtime'";
      $query = \DB::query($sql)->execute();

      $data =  Model_analysis::calclation($query);
      return $data;
    }
    private static function calclation($query){
      $data = $query;
      $totaldata = array();
      foreach ($data as $key => $val) {
        //  Debug::dump($val['electric_at']);
        //  Debug::dump($val['electric_kw']);


          //$m = DateTime::createFromFormat('Y-m-d H:10:00', $val['electric_at']);
          //Debug::dump($m);
          $timedata = (int) floor($val['electric_kw'] / 3);
          $totaldata[] = $timedata;
          $totaldata[] = $timedata;
          $totaldata[] = $timedata;

        //  Debug::dump($totaldata);
      }
      return $totaldata;
    }
}
