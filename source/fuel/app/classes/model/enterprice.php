<?php

/**
 *
 * 作成日：2017/07/17
 * 更新日：2017/11/12
 * 作成者：戸田滉洋
 * 更新者：戸田滉洋
 * 
 */

/**
 * The EnterPrice Model.
 *
 * 企業情報転送
 * @package app
 * @extends Model
 * 
 * 
 */
class Model_EnterPrice extends \orm\Model {

    protected static $_table_name = 'EnterPrice';
    protected static $_primary_key = array('ep_id');
    protected static $_properties = array(
        'ep_id', //企業ID
        'ep_na', //企業名
        'ep_pref_id', //企業都道府県
        'ep_pos_code', //企業郵便番号
        'ep_street_addres', //企業住所
        'ep_phone_num', //企業電話番号
        'ep_email', //企業メールアドレス
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

    public static function epdata() {
        $data = array();
        $auth = Auth::instance();
        $ep_id = $auth->get_ep_id();
        $data['data'] = Model_EnterPrice::find($ep_id);
        return $data;
    }

    public static function eplist() {
        $query = Model_EnterPrice::find('all');
        foreach ($query as $row):
            $data[$row->ep_id] = $row->ep_na;
        endforeach;
        return $data;
    }

    //企業情報新規作成
    public static function createEP($data) {
        $query = Model_EnterPrice::forge()->set(array(
            'ep_na' => $data['ep_na'], //企業名
            'ep_pref_id' => $data['ep_pref_id'], //企業都道府県
            'ep_pos_code' => $data['ep_pos_code'], //企業郵便番号
            'ep_street_addres' => $data['ep_street_addres'], //企業住所
            'ep_phone_num' => $data['ep_phone_num'], //企業電話番号
            'ep_email' => $data['ep_email_addres'] //企業メールアドレス
        ));

        $result = $query->save();
        return $result;
    }

    //企業情報更新
    public static function updateEP($data, $ep_id) {
        $query = Model_EnterPrice::find($ep_id);
        $query->ep_na = $data['ep_na']; //企業名
        $query->ep_pref_id = $data['ep_pref_id']; //企業都道府県
        $query->ep_pos_code = $data['ep_pos_code']; //企業郵便番号
        $query->ep_street_addres = $data['ep_street_addres']; //企業住所
        $query->ep_phone_num = $data['ep_phone_num']; //企業電話番号
        $query->ep_email = $data['ep_email']; //企業メールアドレス
        $query->save();
        return $query;
    }

}
