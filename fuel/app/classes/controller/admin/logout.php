<?php
/**
 *
 * 作成日：2017/07/16
 * 更新日：2017/12/23
 * 作成者：戸田滉洋
 * 更新者：戸田滉洋
 *
 */

/**
 * The Top admin.
 *
 * ログアウト
 * @package app
 * @extends Controller
 */
class Controller_admin_Logout extends Controller {

    public function action_index() {
        //ログイン用のオブジェクト生成
        $auth = Auth::instance();
        $auth->logout();
        Session::destroy();
        //ログインページへ移動
        Session::set_flash('error', 'ログアウトしました。');
        Response::redirect('admin/login');
    }

}
