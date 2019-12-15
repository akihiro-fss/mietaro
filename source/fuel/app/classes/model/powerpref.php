<?php

/**
 *
 * 作成日：2017/07/16
 * 更新日：2017/11/11
 * 作成者：戸田滉洋
 * 更新者：戸田滉洋
 *
 */

/**
 * The Top Model.
 * 
 * 電力会社の情報
 * @package app
 * @extends model
 */

class Model_PowerPref extends \orm\Model {

    protected static $_table_name = 'PowerPref';
    protected static $_primary_key = array('power_com_id');
    protected static $_properties = array(
        'power_com_id', //電力会社ID
        'power_com_na', //電力会社名
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

    public static function powerprefdata() {
        $data = array();
        $query = Model_PowerPref::query();
        $data['data'] = $query
                ->related('PowerPref')
                ->get();
        return $data;
    }

    public static function powerpreflist() {
        $query = Model_PowerPref::find('all');
        foreach ($query as $row):
            $data[$row->power_com_id] = $row->power_com_na;
        endforeach;
        return $data;
    }

}
