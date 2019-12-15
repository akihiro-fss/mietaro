<?php

/**
 *
 * 作成日：2018/08/08
 * 更新日：2018/09/23
 * 作成者：戸田滉洋
 * 更新者：戸田滉洋
 *
 */
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Controller_Electric_yearcompaire extends Controller
{
    public function before()
    {
        //beforeアクション
        parent::before();
        if (!Auth::check()) {
            //ログインページへ移動
            Response::redirect('admin/login');
        }
    }

    public function action_index()
    {

        //日付フォームの値を取得
        $param = \Input::post();
        $oneyearDate = \Arr::get($param, 'param_date_1', null);
        // Debug::dump($oneyearDate);
        $twoyearDate = \Arr::get($param, 'param_date_2', null);
        // Debug::dump($twoyearDate);
        if (empty($oneyearDate)) {
            $oneyearDatetime = new DateTime();
            $oneyearDateformat = $oneyearDatetime;
            $oneyearDate = $oneyearDateformat->format('Y-m-d');
            // Debug::dump($oneyearDate->format('Y-m-d H:i:s'));
        }
        if (empty($twoyearDate)) {
            $twoyearDatetime = new Datetime();
            $twoyearDateformat = $twoyearDatetime->modify('-1 year');
            $towyearDate = $twoyearDateformat->format('Y-m-d');
            // Debug::dump($twoyearDate->format('Y-m-d H:i:s'));
        }
        
        //店舗ID取得
        $auth = Auth::instance();
        $strId = $auth->get_str_id();

        //使用電力詳細情報を取得
        $data_array = \Model_ElectricInfo::getYearData($strId, $oneyearDate, $twoyearDate);
        
        // CO2排出係数の取得
        $efactor = Model_basicinfo::getEfactor();
        $emission_factor = $efactor['emission_factor'];

        // 原油換算係数の取得
        $cfactor = Model_basicinfo::getCfactor();
        $conversion_factor = $cfactor['conversion_factor'];
        $oneyear_electric = $data_array['oneyear_electric'];
        $twoyear_electric = $data_array['twoyear_electric'];

        // 月毎のCO2排出量を取得
        $oneyear_emission = Model_analysis::getYEfactor($oneyear_electric, $emission_factor);
        $twoyear_emission = Model_analysis::getYEfactor($twoyear_electric, $emission_factor);

        // 月毎の比率を取得
        $electric_raito = Model_analysis::getERaito($oneyear_electric, $twoyear_electric);
        $demand_raito = Model_analysis::getDRaito($oneyear_electric, $twoyear_electric);
        
        // 月毎の削減量を取得
        $electric_R = Model_analysis::calcER($oneyear_electric, $twoyear_electric);
        $demand_R = Model_analysis::calcDR($oneyear_electric, $twoyear_electric);
        $emission_R = Model_analysis::calcCOR($oneyear_emission, $twoyear_emission);
        
        // 使用電力量の合計
        $oneyear_electric_total = Model_analysis::calcETotal($oneyear_electric);
        $twoyear_electric_total = Model_analysis::calcETotal($twoyear_electric);
        
        // 最大デマンド値の取得
        $max_oneyear_demand = Model_analysis::maxYDemand($oneyear_electric);
        $max_twoyear_demand = Model_analysis::maxYDemand($twoyear_electric);

        // co2排出量の合計を取得
        $emission_oneyear_total = Model_analysis::calcCTotal($oneyear_emission);
        $emission_twoyear_total = Model_analysis::calcCTotal($twoyear_emission);

        // total使用電力の削減量を計算
        $total_R = $twoyear_electric_total-$oneyear_electric_total;
        
        // totalCO2削減量の計算
        $total_ER = $emission_twoyear_total-$emission_oneyear_total;

        // total使用電力量の比率
        if ($oneyear_electric_total == 0 || $twoyear_electric_total == 0) {
            $total_raito = 0;
        } else {
            $total_raito = floor($oneyear_electric_total/$twoyear_electric_total*100);
        }

        // 連想配列のデータに値追加
        $data_array = array_merge($data_array, array('oneyear_emission' => $oneyear_emission));
        $data_array = array_merge($data_array, array('twoyear_emission' => $twoyear_emission));
        $data_array = array_merge($data_array, array('electric_raito' => $electric_raito));
        $data_array = array_merge($data_array, array('demand_raito' => $demand_raito));
        $data_array = array_merge($data_array, array('electric_R' => $electric_R));
        $data_array = array_merge($data_array, array('demand_R' => $demand_R));
        $data_array = array_merge($data_array, array('emission_R' => $emission_R));
        $data_array = array_merge($data_array, array('oneyear_electric_total' => $oneyear_electric_total));
        $data_array = array_merge($data_array, array('twoyear_electric_total' => $twoyear_electric_total));
        $data_array = array_merge($data_array, array('max_oneyear_demand' => $max_oneyear_demand));
        $data_array = array_merge($data_array, array('max_twoyear_demand' => $max_twoyear_demand));
        $data_array = array_merge($data_array, array('emission_oneyear_total' => $emission_oneyear_total));
        $data_array = array_merge($data_array, array('emission_twoyear_total' => $emission_twoyear_total));
        $data_array = array_merge($data_array, array('total_R' => $total_R));
        $data_array = array_merge($data_array, array('total_ER' => $total_ER));
        $data_array = array_merge($data_array, array('total_raito' => $total_raito));

        // 使用電力の比較表
        //テーマのインスタンス化
        $theme = \Theme::forge();
        //テーマにテンプレートのセット
        $theme->set_template('template');
        //テーマのテンプレートにタイトルをセット
        $theme->get_template()->set('title', 'MIETARO');
        //テーマのテンプレートにビューとページデータをセット
        $theme->get_template()->set('content', $theme->view('electric/yearcompaire')->set('yearcompaire', $data_array));
        //テーマのテンプレートにビューとページデータをセット
        $theme->get_template()->set('sidebar', $theme->view('sidebar'));
        return $theme;
    }
}
