<?php

/**
 *
 * 作成日：2017/12/30
 * 更新日：2017/12/30
 * 作成者：丸山　隼
 * 更新者：丸山　隼
 *
 */

/**
 * The Electric Controller.
 *
 *　分析用のブランクファイル
 * @package app
 * @extends Controller
 */
class Controller_Electric_weekinfo extends Controller {

    public function before() {
        //未ログインの場合、ログインページにリダイレクト
        if (!Auth::check()) {
            Response::redirect('admin/login');
        }
    }

    public function action_index() {
        //日付フォームの値を取得
        $param = \Input::post();
        $oneweekDate = \Arr::get($param,'param_date_1');
        $twoweekDate = \Arr::get($param,'param_date_2');

        if(empty($oneweekDate)){
            $oneweekDate = date('Y-m-d');
        }
        if(empty($twoweekDate)){
            $twoweekDate = date('Y-m-d',strtotime('-1 week'));
        }

        //店舗ID取得
        $auth = Auth::instance();
        $strId = $auth->get_str_id();

        //使用電力詳細情報を取得
        $weekinfo = \Model_ElectricInfo::getWeekData($strId,$oneweekDate, $twoweekDate);

        //各時間帯に気温・湿度情報を追加する
        $weekinfo = Model_Electric::addInformationForWeekInfo($oneweekDate,$twoweekDate,$weekinfo);

        //一日分のデータを取得
        //テーマのインスタンス化
        $theme = \Theme::forge();
        //テーマにテンプレートのセット
        $theme->set_template('template');
        //テーマのテンプレートにタイトルをセット
        $theme->get_template()->set('title', 'MIETARO');
        //テーマのテンプレートにビューとページデータをセット
        $theme->get_template()->set('content', $theme->view('electric/weekinfo')->set('electricData',$weekinfo));
        //テーマのテンプレートにビューとページデータをセット
        $theme->get_template()->set('sidebar', $theme->view('sidebar'));
        return $theme;
    }
}
