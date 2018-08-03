<?php

/**
 *
 * 作成日：2017/07/16
 * 更新日：2017/12/30
 * 作成者：戸田滉洋
 * 更新者：戸田滉洋
 *
 */

/**
 * The Top admin.
 *
 * @package app
 * @extends Controller
 */
class Controller_admin_user extends Controller_admin_login {

    public function before() {
        //beforeアクション
        parent::before();
        if (!Auth::check()) {
            //ログインページへ移動
            Response::redirect('admin/login');
        }
    }

    public function action_index() {
        //POST送信なら
        if (Input::method() == 'POST') {
            Response::redirect('admin/useredit');
        }
        //テーマのインスタンス化
        $theme = \Theme::forge();
        //テーマにテンプレートのセット
        $theme->set_template('template');
        //テーマのテンプレートにタイトルをセット
        $theme->get_template()->set('title', 'MIETARO');
        //テーマのテンプレートにビューとページデータをセット
        $theme->get_template()->set('content', $theme->view('admin/user', Model_user::userdata()));
        //テーマのテンプレートにビューとページデータをセット
        $theme->get_template()->set('sidebar', $theme->view('sidebar', Controller_Sidebar::data()));
        return $theme;
    }

}
