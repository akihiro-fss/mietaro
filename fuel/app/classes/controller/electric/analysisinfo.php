<?php

/**
 *
 * 作成日：2018/09/16
 * 更新日：
 * 作成者：戸田滉洋
 * 更新者：
 *
 */

/**
 * The Electric Controller.
 *
 * @package app
 * @extends Controller
 */
class Controller_Electric_analysisinfo extends Controller
{
    public function before()
    {
        //未ログインの場合、ログインページにリダイレクト
        if (!Auth::check()) {
            Response::redirect('admin/login');
        }
    }

    public function action_index()
    {

        //日付フォームの値を取得
        // $param = \Input::post();
        // $starttime = \Arr::get($param, 'starttime', null);
        // $endtime = \Arr::get($param, 'endtime', null);
        //日付の取得
        if (Input::method() == 'POST') {
            $starttime = Input::post('starttime');
            // Debug::dump($starttime);
            $tday_format = new Datetime($starttime);
            $today = $tday_format->format('Y-m-d H:i:s');
            $endtime = Input::post('endtime');
        }
        // 日付が送られて来なかった場合
        if (empty($starttime)) {
            $today = date('Y-m-d H:00:00');
            $starttime = date('Y-m-d H:00:00', strtotime($today.'-4 hour'));
        }
        if (empty($endtime)) {
            $today = date('Y-m-d H:00:00');
            $endtime = date('Y-m-d H:00:00', strtotime($today.'+4 hour'));
        }

        //店舗ID取得
        $auth = Auth::instance();
        $strId = $auth->get_str_id();

        // 開始時間から終了時間までの時間を10分間隔で取得
        $interval = new DateInterval('PT10M');
        $start = new Datetime($starttime);
        $end = new Datetime($endtime);
        $period = new DatePeriod($start, $interval, $end);

        // 日付のデータ配列を作成
        // 日付が00:00の時は、m-d H:iを表示
        // 日付が00:00でなければ、H:iで表示
        $date_array = [];
        foreach ($period as $datetime) {
            if ($datetime->format('Y-m-d H:i:00') == $datetime->format('Y-m-d H:00:00')) {
                $date_array[] = $datetime->format('m-d H:i');
            } else {
                $date_array[] = $datetime->format('H:i');
            }
        }

        //使用電力詳細情報を取得
        $electric = Model_analysis::analysisdata($strId, $starttime, $endtime);

        // デマンド値の詳細情報を取得
        $demand = Model_analysis::demand_analysis($strId, $starttime, $endtime);

        // 使用電力量30分単位のデータを取得
        $electric_m = Model_analysis::electric_m($strId, $starttime, $endtime);

        // 表示用の配列を作成
        $electric_array = Model_analysis::total_analysisinfo($today, $date_array, $electric);
        $demand_array = Model_analysis::total_analysisinfo($today, $date_array, $demand);
        $electirc_m_array = Model_analysis::total_analysisinfo($today, $date_array, $electric_m);

        // $totaldata = Model_analysis::all_analysisinfo($today, $date_array, $electric_array, $demand_array);
        // Debug::dump($electric_array);
        // Debug::dump($demand_array);
        //viewに送るデータを連想配列で作成
        $data = array();
        $data['starttime'] = $starttime;
        $data['endtime'] = $endtime;
        $data['electric'] = $electric_array;
        $data['electric_data'] = $electirc_m_array;
        $data['demand'] = $demand_array;
        $data['date_array'] = $date_array;
        //一日分のデータを取得
        //テーマのインスタンス化
        $theme = \Theme::forge();
        //テーマにテンプレートのセット
        $theme->set_template('template');
        //テーマのテンプレートにタイトルをセット
        $theme->get_template()->set('title', 'MIETARO');
        //テーマのテンプレートにビューとページデータをセット
        // $theme->get_template()->set('content', $theme->view('electric/analysisinfo')->set('electricData', $result));
        $theme->get_template()->set('content', $theme->view('electric/analysisinfo', $data));
        //テーマのテンプレートにビューとページデータをセット
        $theme->get_template()->set('sidebar', $theme->view('sidebar'));

        return $theme;
    }
}
