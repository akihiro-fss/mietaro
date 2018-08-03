<?php

/**
 *
 * 作成日：2018/01/01
 * 更新日：2018/01/01
 * 作成者：戸田滉洋
 * 更新者：戸田滉洋
 *
 */

/**
 * The Top comment.
 *
 * @package app
 * @extends Controller
 */
class Controller_top_comment extends Controller_Template {

    //beforeアクション
    public function before() {
        parent::before();
        if (!Auth::check() and Request::active()->action != 'login') {
            //ログインページへ移動
            Response::redirect('admin/login');
        }

        $auth = Auth::instance();
        $group = $auth->get_group();
        if ($group == 1) {
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
        $theme->get_template()->set('content', $theme->view('top/comment', Model_OnedayComment::pagenationdata()));
        //テーマのテンプレートにビューとページデータをセット
        $theme->get_template()->set('sidebar', $theme->view('sidebar'));
        return $theme;
    }

}
