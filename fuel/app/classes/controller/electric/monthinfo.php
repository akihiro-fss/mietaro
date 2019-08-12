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
class Controller_Electric_monthinfo extends Controller {

    public function before() {
        //未ログインの場合、ログインページにリダイレクト
        if (!Auth::check()) {
            Response::redirect('admin/login');
        }
    }

    public function action_index() {
        //日付フォームの値を取得
        $param = \Input::post();
        $onemonthDate = \Arr::get($param,'param_date_1');

        if(empty($onemonthDate)){
            $onemonthDate = date('Y-m-d');
        }

        //店舗ID取得
        $auth = Auth::instance();
        $strId = $auth->get_str_id();

        //使用電力詳細情報を取得
        $monthinfo = \Model_ElectricInfo::getMonthData($strId,$onemonthDate);

        //各時間帯に気温・湿度情報を追加する
        $monthinfo = Model_Electric::addInformationForMonthInfo($onemonthDate,$monthinfo);

        //一日分のデータを取得
        //テーマのインスタンス化
        $theme = \Theme::forge();
        //テーマにテンプレートのセット
        $theme->set_template('template');
        //テーマのテンプレートにタイトルをセット
        $theme->get_template()->set('title', 'MIETARO');
        //テーマのテンプレートにビューとページデータをセット
        $theme->get_template()->set('content', $theme->view('electric/monthinfo')->set('electricData',$monthinfo));
        //テーマのテンプレートにビューとページデータをセット
        $theme->get_template()->set('sidebar', $theme->view('sidebar'));
        return $theme;
    }
}
