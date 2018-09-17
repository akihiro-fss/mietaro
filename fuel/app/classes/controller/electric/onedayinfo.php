<?php

/**
 *
 * 作成日：2017/12/30
 * 更新日：2017/12/30
 * 作成者：戸田滉洋
 * 更新者：丸山　隼
 *
 */

/**
 * The Electric Controller.
 *
 * @package app
 * @extends Controller
 */
class Controller_Electric_onedayinfo extends Controller {

    public function before() {
        //未ログインの場合、ログインページにリダイレクト
        if (!Auth::check()) {
            Response::redirect('admin/login');
        }
    }

    public function action_index() {

        //日付フォームの値を取得
        $param = \Input::post();
        $onedayDate = \Arr::get($param,'param_date_1',null);
        $twodayDate = \Arr::get($param,'param_date_2',null);

        //店舗ID取得
        $auth = Auth::instance();
        $strId = $auth->get_str_id();

        //使用電力詳細情報を取得
        $result = \Model_ElectricInfo::getOnedayData($strId,$onedayDate, $twodayDate);

        //一日分のデータを取得
        //テーマのインスタンス化
        $theme = \Theme::forge();
        //テーマにテンプレートのセット
        $theme->set_template('template');
        //テーマのテンプレートにタイトルをセット
        $theme->get_template()->set('title', 'MIETARO');
        //テーマのテンプレートにビューとページデータをセット
        $theme->get_template()->set('content', $theme->view('electric/onedayinfo')->set('electricData',$result));
        //テーマのテンプレートにビューとページデータをセット
        $theme->get_template()->set('sidebar', $theme->view('sidebar'));

        return $theme;
    }
}
