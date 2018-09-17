<?php

/**
 * 作成日：2018/08/14
 * 更新日：2018/08/25
 * 作成者：戸田滉洋
 * 更新者：戸田滉洋
 */

/**
 * The analysis Controrller.
 *
 * 分析用データ取得
 *
 * @extends Model
 */
class Controller_Electric_Analysis extends Controller
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

        $auth = Auth::instance();
        $str_id = $auth->get_str_id();

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
        // 開始時間から終了時間までのデータの取得
        $totaldata = Model_analysis::analysisdata($str_id, $starttime, $endtime);
        
        // 連想配列で日付及び分析用のデータ配列作成
        $count_date_array = count($date_array);
        for ($i = 0; $i < $count_date_array; ++$i) {
            if ($i == 0) {
                $array_date[''] = $today;
                if (isset($totaldata[$i])) {
                    $array_date[$date_array[$i]] = $totaldata[$i];
                } else {
                    $array_date[$date_array[$i]] = 0;
                }
            } elseif (isset($totaldata[$i])) {
                // Debug::dump($date_array[$i]);
                $array_date[$date_array[$i]] = $totaldata[$i];
            } else {
                $array_date[$date_array[$i]] = 0;
            }
        }

        //viewに送るデータを連想配列で作成
        $data = array();
        $data['starttime'] = $starttime;
        $data['endtime'] = $endtime;
        $data['date_array'] = $array_date;

        //テーマのインスタンス化
        $theme = \Theme::forge();
        //テーマにテンプレートのセット
        $theme->set_template('template');
        //テーマのテンプレートにタイトルをセット
        $theme->get_template()->set('title', 'MIETARO');
        //テーマのテンプレートにビューとページデータをセット
        $theme->get_template()->set('content', $theme->view('electric/analysis', $data));
        //テーマのテンプレートにビューとページデータをセット
        $theme->get_template()->set('sidebar', $theme->view('sidebar'));

        return $theme;
    }
}
