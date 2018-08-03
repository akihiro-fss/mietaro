<?php

/**
 *
 * 作成日：2017/12/5
 * 更新日：2017/12/5
 * 作成者：戸田滉洋
 * 更新者：戸田滉洋
 * 
 */

/**
 * The PastPerformance Model.
 *
 * 導入前実績
 * @package app
 * @extends Model
 * 
 * 
 */
class Model_PastPerformance extends \orm\Model {

    protected static $_table_name = 'PastPerformance';
    protected static $_primary_key = array('str_id');
    protected static $_properties = array(
        'str_id', //店舗ID
        'p_year', //導入前実績で入力した年
        'january_kwh', //1月使用電力量
        'january_kw', //1月最大デマンド値
        'february_kwh', //2月使用電力量
        'february_kw', //2月最大デマンド値
        'march_kwh', //3月使用電力量
        'march_kw', //3月最大デマンド値
        'april_kwh', //4月使用電力量
        'april_kw', //4月最大デマンド値
        'may_kwh', //5月使用電力量
        'may_kw', //5月最大デマンド値
        'june_kwh', //6月使用電力量
        'june_kw', //6月最大デマンド値
        'july_kwh', //7月使用電力量
        'july_kw', //7月最大デマンド値
        'august_kwh', //8月使用電力量
        'august_kw', //8月最大デマンド値
        'september_kwh', //9月使用電力量
        'september_kw', //9月最大デマンド値
        'october_kwh', //10月使用電力量
        'october_kw', //10月最大デマンド値
        'november_kwh', //11月使用電力量
        'november_kw', //11月最大デマンド値
        'december_kwh', //12月使用電力量
        'december_kw', //12月最大デマンド値
        'val', //削除フラグ
        'created_at', //作成日
        'updated_at', //更新日
    );
    protected static $_observers = array(
        'Orm\Observer_CreatedAt' => array(
            'events' => array('before_insert'),
            'mysql_timestamp' => false,
        ),
        'Orm\Observer_UpdatedAt' => array(
            'events' => array('before_save'),
            'mysql_timestamp' => false,
        ),
    );

    public static function pastdata() {
        $auth = Auth::instance();
        $str_id = $auth->get_str_id();
        $data['month'] = Model_PastPerformance::find($str_id);
        return $data;
    }

    //導入前実績入力
    public static function createPP($data,$str_id) {
        $query = Model_PastPerformance::forge()->set(array(
            'str_id' => $str_id,
            'p_year' => $data['p_year'], //導入前実績で入力した年
            'january_kwh' => $data['january_kwh'], //1月使用電力量
            'february_kwh' => $data['february_kwh'], //2月使用電力量
            'march_kwh' => $data['march_kwh'], //3月使用電力量
            'april_kwh' => $data['april_kwh'], //4月使用電力量
            'may_kwh' => $data['may_kwh'], //5月使用電力量
            'june_kwh' => $data['june_kwh'], //6月使用電力量
            'july_kwh' => $data['july_kwh'], //7月使用電力量
            'august_kwh' => $data['august_kwh'], //8月使用電力量
            'september_kwh' => $data['september_kwh'], //9月使用電力量
            'october_kwh' => $data['october_kwh'], //10月使用電力量
            'november_kwh' => $data['november_kwh'], //11月使用電力量
            'december_kwh' => $data['december_kwh'], //12月使用電力量
            'january_kw' => $data['january_kw'], //1月最大デマンド値
            'february_kw' => $data['february_kw'], //2月最大デマンド値
            'march_kw' => $data['march_kw'], //3月最大デマンド値
            'april_kw' => $data['april_kw'], //4月最大デマンド値
            'may_kw' => $data['may_kw'], //5月最大デマンド値
            'june_kw' => $data['june_kw'], //6月最大デマンド値
            'july_kw' => $data['july_kw'], //7月最大デマンド値
            'august_kw' => $data['august_kw'], //8月最大デマンド値
            'september_kw' => $data['september_kw'], //9月最大デマンド値
            'october_kw' => $data['october_kw'], //10月最大デマンド値
            'november_kw' => $data['november_kw'], //11月最大デマンド値
            'december_kw' => $data['december_kw'], //12月最大デマンド値
            'val' => 1
        ));

        $result = $query->save();
        return $result;
    }

}
