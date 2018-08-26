<?php

/**
 *
 * 作成日：2018/08/14
 * 更新日：
 * 作成者：戸田滉洋
 * 更新者：
 *
 */

/**
 * The analysis Controrller.
 *
 * 分析用データ取得
 * @package app
 * @extends Model
 *
 *
 */
class Controller_Electric_Analysis extends Controller {

    public function before() {
        //beforeアクション
        parent::before();
        if (!Auth::check()) {
            //ログインページへ移動
            Response::redirect('admin/login');
        }
    }

    public function action_index() {

        //日付の取得

        if (!isset($today)){
          $today = date("Y-m-d H:00:00");
          $starttime = date("Y-m-d H:00:00",strtotime($today . "-4 hour"));
          $endtime = date("Y-m-d H:00:00",strtotime($today . "+4 hour"));
        }
        $auth = Auth::instance();
        $str_id = $auth->get_str_id();

        Debug::dump($today);
        Debug::dump($starttime);
        Debug::dump($endtime);

        // $diff = (strtotime($endtime) - strtotime($starttime)) / ( 60 * 60 * 24);
        // for($i = 0; $i <= $diff; $i++) {
        //   $analysis_array[] = date('Y-m-d', strtotime($starttime . '+' . $i . 'days'));
        // }
        $interval = new DateInterval('PT10M'); ;
        $start = new Datetime($starttime);
        $end = new Datetime($endtime);
        $period = new DatePeriod($start, $interval, $end);
        Debug::dump($period);
        $date_array = [];
        foreach ($period as $datetime) {
          //echo $datetime->format('Y-m-d H:m:s');
          //echo "\n";
          //Debug::dump($datetime->format('Y-m-d H:m:00'));
          $date_array[] = $datetime->format('Y-m-d H:i:00');
        }
        Debug::dump($date_array);
        $totaldata = Model_analysis::analysisdata($str_id,$starttime,$endtime);
        $arrayDate = [];
        $count_date_array = count($date_array);
        for ($i = 0; $i < $count_date_array; $i++){
          if ($i == 0){
            $array_date[""] = $today;
            if (isset($totaldata[$i])){
              $array_date[$date_array[$i]] = $totaldata[$i];
            }else{
              $array_date[$date_array[$i]] = 0;
            }

          }else if (isset($totaldata[$i])){
            $array_date[$date_array[$i]] = $totaldata[$i];
          }else{
            $array_date[$date_array[$i]] = 0;
          }
        }
        Debug::dump($array_date);
        //Debug::dump($totaldata);

        //Debug::dump($analysis_array);

        $array_count = count($date_array);
        $array_total = [];
        Debug::dump($array_total);

        $data = array();
        #$data['totaldata'] = $totaldata;
        $data['date_array'] = $array_date;
        //$data['analysis_array'] = $analysis_array;
         //Model_Electric::

        //テーマのインスタンス化
        $theme = \Theme::forge();
        //テーマにテンプレートのセット
        $theme->set_template('template');
        //テーマのテンプレートにタイトルをセット
        $theme->get_template()->set('title', 'MIETARO');
        //テーマのテンプレートにビューとページデータをセット
        $theme->get_template()->set('content', $theme->view('electric/analysis',$data));
        //テーマのテンプレートにビューとページデータをセット
        $theme->get_template()->set('sidebar', $theme->view('sidebar'));
        return $theme;
    }

}
