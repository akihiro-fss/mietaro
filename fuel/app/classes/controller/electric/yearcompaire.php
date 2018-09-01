<?php

/**
 *
 * 作成日：2018/08/08
 * 更新日：
 * 作成者：戸田滉洋
 * 更新者：
 *
 */
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Controller_Electric_yearcompaire extends Controller {

    public function before() {
        //beforeアクション
        parent::before();
        if (!Auth::check()) {
            //ログインページへ移動
            Response::redirect('admin/login');
        }
    }

    public function action_index() {

        //日付フォームの値を取得
        $param = \Input::post();
        $oneyearDate = \Arr::get($param, 'param_date_1', null);
        // Debug::dump($oneyearDate);
        $twoyearDate = \Arr::get($param, 'param_date_2', null);
        // Debug::dump($twoyearDate);
        if (empty($oneyearDate)){
            $oneyearDate = new DateTime();
            // Debug::dump($oneyearDate->format('Y-m-d H:i:s'));
        }
        if (empty($twoyearDate)){
            $twoyearDate = new Datetime();
            // Debug::dump($twoyearDate->format('Y-m-d H:i:s'));
        }
        //店舗ID取得
        $auth = Auth::instance();
        $strId = $auth->get_str_id();

        // 使用電力の比較表
        //テーマのインスタンス化
        $theme = \Theme::forge();
        //テーマにテンプレートのセット
        $theme->set_template('template');
        //テーマのテンプレートにタイトルをセット
        $theme->get_template()->set('title', 'MIETARO');
        //テーマのテンプレートにビューとページデータをセット
        $theme->get_template()->set('content', $theme->view('electric/yearcompaire'));
        //テーマのテンプレートにビューとページデータをセット
        $theme->get_template()->set('sidebar', $theme->view('sidebar'));
        return $theme;
    }

}
