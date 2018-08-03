<?php

/**
 *
 * 作成日：2017/07/16
 * 最終更新日：2017/11/12
 * 作成者：戸田滉洋
 * 最終更新者：戸田滉洋
 *
 */

/**
 * The Prefecture Model.
 *
 * 都道府県の情報
 * @package app
 * @extends Moldel
 */

class Model_Prefecture extends \orm\Model {

    protected static $_table_name = 'prefecture';
    protected static $_primary_key = array('pref_id');
    protected static $_properties = array(
        'pref_id', //都道府県ID
        'pref_na', //都道府県名
    );

    public static function preffdata() {
        $data = array();
        $query = Model_Prefecture::query();
        $data['data'] = $query
                ->related('prefecture')
                ->get();
        return $data;
    }

    public static function preflist() {
        $query = Model_Prefecture::find('all');
        foreach ($query as $row):
            $data[$row->pref_id] = $row->pref_na;
        endforeach;
        return $data;
    }

}
