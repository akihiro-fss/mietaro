<?php

/**
 *
 * 作成日：2017/07/17
 * 更新日：2018/08/17
 * 作成者：戸田滉洋
 * 更新者：戸田滉洋
 *
 */

/**
 * The BasicInfo Model.
 *
 * 各種設定機能で使用するデータの転送
 * @package app
 * @extends Model
 *
 */
class Model_BasicInfo extends \orm\Model {

    protected static $_table_name = 'BasicInfo';
    protected static $_primary_key = array('str_id');
    protected static $_properties = array(
        'str_id', //店舗ID
        'str_na', //店舗名
        'ep_id', //企業ID
        'pref_id', //都道府県
        'str_pos_code', //郵便番号
        'str_street_addres', //住所
        'str_phone_num', //電話番号
        'str_fax_num', //FAX
        'str_info', //事業所情報
        'latitude', //緯度
        'longitude', //軽度
        'str_email_addres', //緊急連絡先メールアドレス
        'str_weather_region', //気象庁地域区分
        'str_memo', //メモ
        'str_ct_1', //CT比一次側
        'str_ct_2', //CT比二次側
        'str_vt_1', //VT比一次側
        'str_vt_2', //VT比二次側
        'power_com_id', //電力会社ID
        'demand_alarm', //デマンド警報値
        'contract_de', //契約電力
        'emission_factor', //CO2排出係数
        'conversion_factor', //原油換算係数
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
    protected static $_has_many = array(
        'basicinfo' => array(
            //'model_to' => 'Model_powerCom',
            'key_from' => 'power_com_id',
            'key_to' => 'power_com_id',
            'cascade_save' => false,
            'cascade_delete' => false
        )
    );

    public static function createstore($data) {

        $query = Model_BasicInfo::forge()->set(array(
            'str_na' => $data['str_na'], //店舗名
            'ep_id' => $data['ep_id'], //企業ID
            'pref_id' => $data['pref_id'], //都道府県
            'str_pos_code' => $data['str_pos_code'], //郵便番号
            'str_street_addres' => $data['str_street_addres'], //住所
            'str_phone_num' => $data['str_phone_num'], //電話番号
            'str_fax_num' => $data['str_fax_num'], //FAX
            'str_info' => $data['str_info'], //事業所情報
            'latitude' => $data['latitude'], //緯度
            'longitude' => $data['longitude'], //軽度
            'str_email_addres' => $data['str_email_addres'], //緊急連絡先メールアドレス
            'str_weather_region' => $data['str_weather_region'], //気象庁地域区分
            'str_memo' => $data['str_memo'], //メモ
            'str_ct_1' => $data['str_ct_1'], //CT比一次側
            'str_ct_2' => $data['str_ct_2'], //CT比二次側
            'str_vt_1' => $data['str_vt_1'], //VT比一次側
            'str_vt_2' => $data['str_vt_2'], //VT比二次側
            'power_com_id' => $data['power_com_id'], //電力会社ID
            'demand_alarm' => $data['demand_alarm'], //デマンド警報値
            'contract_de' => $data['contract_de'], //契約電力
            'emission_factor' => $data['emission_factor'], //CO2排出係数
            'conversion_factor' => $data['conversion_factor'], //原油換算係数
        ));

        $result = $query->save();
        return $result;
    }

    public static function strdata() {
        $data = array();
        //Authのインスタンス化
        $auth = Auth::instance();
        $str_id = $auth->get_str_id();
        $query = Model_BasicInfo::query();
        $data['data'] = $query
                ->related('basicinfo')
                //->related('basicinfo.powercom')
                ->where('str_id', '=', [$str_id])
                ->get();
        return $data;
    }

    public static function strupdate($str_id, $data) {

        $query = Model_BasicInfo::find($str_id);
        $query->str_na = $data->str_na;
        $query->pref_id = $data->pref_id;
        $query->str_pos_code = $data->str_pos_code;
        $query->str_street_addres = $data->str_street_addres;
        $query->str_phone_num = $data->str_phone_num;
        $query->str_fax_num = $data->str_fax_num;
        $query->str_info = $data->str_info;
        $query->latitude = $data->latitude;
        $query->longitude = $data->longitude;
        $query->str_email_addres = $data->str_email_addres;
        $query->str_weather_region = $data->str_weather_region;
        $query->str_memo = $data->str_memo;
        $query->power_com_id = $data->power_com_id;
        $query->str_ct_1 = $data->str_ct_1;
        $query->str_ct_2 = $data->str_ct_2;
        $query->str_vt_1 = $data->str_vt_1;
        $query->str_vt_2 = $data->str_vt_2;
        $query->contract_de = $data->contract_de;
        $query->emission_factor = $data->emission_factor;
        $query->conversion_factor = $data->conversion_factor;
        $query->save();
        return $query;
    }

    public static function strlist() {
        $data = array();
        $query = Model_BasicInfo::find('all');
        foreach ($query as $row):
            $data[$row->str_id] = $row->str_na;
        endforeach;
        return $data;
    }

    public static function getStoreNameByEpId($epId) {
        $query = "SELECT str_id,str_na FROM BasicInfo WHERE ep_id = $epId";
        $data = \DB::query($query)->execute()->as_array();
        return $data;
    }

    public static function epstrlist() {
        //Authのインスタンス化
        $auth = Auth::instance();
        $ep_id = $auth->get_ep_id();
        $data = array();
        $query = Model_BasicInfo::query();
        $data['data'] = $query
                ->related('basicinfo')
                //->related('basicinfo.powercom')
                ->where('ep_id', '=', [$ep_id])
                ->get();
        return $data;
    }

    public static function getStrDataByStrId($strId) {
        $query = "SELECT * FROM BasicInfo WHERE str_id = $strId";
        $data = DB::query($query)->execute()->current();
        return $data;
    }

    /**
     * サイドバーをデマンド値を表示
     * @return data
     */
    public static function getDemandKey() {
        $auth = Auth::instance();
        $str_id = $auth->get_str_id();
        $query = "SELECT demand_alarm FROM BasicInfo WHERE str_id = $str_id";
        $data = DB::query($query)->execute()->current();
        return $data;
    }

    /**
     * サイドバーを契約電力を表示
     * @return data
     */
    public static function getContractDe() {
        $auth = Auth::instance();
        $str_id = $auth->get_str_id();
        $query = "SELECT contract_de FROM BasicInfo  WHERE str_id = $str_id";
        $data = DB::query($query)->execute()->current();
        return $data;
    }
    
    /**
     * CO2排出係数取得
     * @return data
     */
    public static function getEfactor() {
        $auth = Auth::instance();
        $str_id = $auth->get_str_id();
        $query = "SELECT emission_factor FROM BasicInfo  WHERE str_id = $str_id";
        $data = DB::query($query)->execute()->current();
        return $data;
    }

}
