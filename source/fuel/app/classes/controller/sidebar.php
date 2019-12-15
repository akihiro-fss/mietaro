<?php

/**
 *
 * 作成日：2017/07/17
 * 更新日：2017/08/25
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

        //月間目標値
        $monthdata = date('F');
        $month = strtolower($monthdata);
        $monthresult = Model_MonthTarget::getMT($month);
        foreach ($monthresult as $row) {
            $monthdate = $row->$month;
        }
        $monthcount = date('t');
        $monthone = 0;
        if (isset($monthdate)) {
            $monthone = floor($monthdate / $monthcount);
        } else {
            $monthdate = '-';
        }
        $electricMonth = Model_Electric::getSideBerData();
        $addMonth = Controller_Sidebar::addSideBarData($electricMonth);
        // 前月の最大デマンド値取得
        $demand_max_kw = Model_analysis::getdemandMax();
        if (isset($demand_max_kw)) {
            $demand_max_kw = $demand_max_kw;
        } else {
            $demand_max_kw = 0;
        }
        //デマンド値取得
        $demand_key = Model_BasicInfo::getDemandKey();
        if (isset($demand_key['demand_alarm'])) {
            $demandKey = $demand_key['demand_alarm'];
        } else {
            $demandKey = '-';
        }
        //契約電力取得
        $contract_de = Model_BasicInfo::getContractDe();
        if (isset($contract_de['contract_de'])) {
            $contractDe = $contract_de['contract_de'];
        } else {
            $contractDe = '-';
        }
        //CO2排出係数
        $eFactor = Model_BasicInfo::getEfactor();
        if (isset($eFactor['emission_factor'])) {
            $ef = $eFactor['emission_factor'];
        } else {
            $ef = '-';
        }
        $data = array();
        $data['month'] = $monthdate;
        $data['MToneday'] = $monthone;
        $data['electricMonth'] = $addMonth;
        $data['demandMax'] = $demand_max_kw;
        $data['demandKey'] = $demandKey;
        $data['contractDe'] = $contractDe;
        $data['ef'] = $ef;
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
        foreach ($electricMonth as $row) {
            $var = $var + $row['electric_kw'];
        }
        return $var;
    }

}
