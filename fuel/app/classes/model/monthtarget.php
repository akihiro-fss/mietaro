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
 * The MonthTarget Model.
 *
 * 月間目標値転送
 * @package app
 * @extends Model
 *
 *
 */
class Model_MonthTarget extends \orm\Model {

    protected static $_table_name = 'MonthTarget';
    protected static $_primary_key = array('str_id');
    protected static $_properties = array(
        'str_id', //店舗ID
        'january', //1月
        'february', //2月
        'march', //3月
        'april', //4月
        'may', //5月
        'june', //6月
        'july', //7月
        'august', //8月
        'september', //9月
        'october', //10月
        'november', //11月
        'december', //12月
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

    public static function monthdata() {
        $data = array();
        $auth = Auth::instance();
        $str_id = $auth->get_str_id();
        $data['month'] = Model_MonthTarget::find($str_id);
        return $data;
    }

    /**
     * sideバーに表示する当月の月間目標値を取得
     * @access 当月の英語の暦
     * @return 表示しているユーザの特定店舗の月間目標値
     */
    public static function getMT($month) {
        $auth = Auth::instance();
        $str_id = $auth->get_str_id();
        $monthresult = Model_MonthTarget::query()
                ->select($month)
                ->where('str_id', $str_id)
                ->get();
        return $monthresult;
    }

    //企業情報新規作成
    public static function createMT($data) {
        $query = Model_MonthTarget::forge()->set(array(
            'str_id' => $data['str_id'], //店舗ID
            'january' => $data['janualry'], //1月
            'february' => $data['february'], //2月
            'march' => $data['march'], //3月
            'april' => $data['april'], //4月
            'may' => $data['may'], //5月
            'june' => $data['june'], //6月
            'july' => $data['july'], //7月
            'august' => $data['august'], //8月
            'september' => $data['september'], //9月
            'october' => $data['october'], //10月
            'november' => $data['november'], //11月
            'december' => $data['decembers'], //12月
        ));

        $result = $query->save();
        return $result;
    }

}
