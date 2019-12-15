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

class Controller_admin_select extends Controller_Template {

    //beforeアクション
    public function before() {
        parent::before();
        if (!Auth::check() and Request::active()->action != 'login') {
            //ログインページへ移動
            Response::redirect('admin/login');
        }
        
        $auth = Auth::instance();
        $group = $auth->get_group();
        if ($group == 1){
            Response::redirect('top/news');
        }
    }

    //トップページの表示
    public function action_index() {
        //テーマのインスタンス化
        $theme = \Theme::forge();
        //テーマにテンプレートのセット
        $theme->set_template('template');
        //テーマのテンプレートにタイトルをセット
        $theme->get_template()->set('title', 'MIETARO');
        //テーマのテンプレートにビューとページデータをセット
        $theme->get_template()->set('content', $theme->view('admin/select', Model_Admin::pagedata()));
        //テーマのテンプレートにビューとページデータをセット
        $theme->get_template()->set('sidebar', $theme->view('sidebar'));
        return $theme;
    }

}
