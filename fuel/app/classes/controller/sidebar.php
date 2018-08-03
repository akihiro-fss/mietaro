<?php

/**
 *
 * 作成日：2017/07/17
 * 更新日：2017/12/23
 * 作成者：戸田滉洋
 * 更新者：戸田滉洋
 *
 */

/**
 * The Sidebar Controller.
 *
 * サイドバー情報
 * @package app
 * @extends Controller
 */
class Controller_Sidebar extends Controller_admin_login {

    /**
     * サイドバーのデータ取得
     *
     * @return $data
     */
    public static function data() {


        $monthdata = date('F');
        $month = strtolower($monthdata);
        $monthresult = Model_MonthTarget::getMT($month);
        foreach ($monthresult as $row) {
            $monthdate = $row->$month;
        }
        $monthcount = date('t');
        $monthone = 0;
        if(isset($monthdate)){
            $monthone = floor($monthdate / $monthcount);
        }else{
            $monthdate = '-';
        }
        $electricMonth = Model_Electric::getSideBerData();
        $addMonth = Controller_Sidebar::addSideBarData($electricMonth);
        $data = array();
        $data['month'] = $monthdate;
        $data['MToneday'] = $monthone;
        $data['electricMonth'] = $addMonth;

        return $data;
    }

    /**
     * サイドバーの表示する月間使用電力量の計算
     *
     * @param type $electricMonth
     * @return $var
     */
    private static function addSideBarData($electricMonth) {

        $var = 0;
       foreach($electricMonth as $row){
           $var = $var + $row['electric_kw'];
       }
        return $var;
    }

}
