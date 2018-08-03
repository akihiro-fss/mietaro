<?php

/**
 *
 * 作成日：2017/07/16
 * 最終更新日：2017/12/30
 * 作成者：戸田滉洋
 * 最終更新者：戸田滉洋
 *
 */

/**
 * The Top admin.
 *
 * @package app
 * @extends Controller
 */
class Controller_admin_usersEdit extends Controller_admin_login {

    public function before() {
        //beforeアクション
        parent::before();
        if (!Auth::check()) {
            //ログインページへ移動
            Response::redirect('admin/login');
        }
        if (Auth::member(1)) {
            Response::redirect('top/news');
        }
    }

    public function action_index() {

        //POST送信なら
        if (Input::method() == 'POST') {

            if (false == preg_match('/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/iD', Input::post('email'))) {
                Session::set_flash('error', 'Eメールアドレスを正しく入力してください');
                Response::redirect('admin/usersedit');
            }
            $id = Session::get('users_id');
            Session::delete('users_id');
            $data = (object) array();
            $data->email = Input::post('email');
            $data->str_id = Input::post('str_id');


            $updateusers = Model_User::userupdate($id, $data);
            //更新が出来たかどうか確認するモジュールが必要である
            if ($updateusers) {
                //登録成功のメッセージ
                Session::set_flash('success', '更新しました');
                Response::redirect('admin/select');
                //indexページへ移動
            } else {
                //データが更新されなかったら
                Session::set_flash('error', '更新されませんでした');
            }
        }
        $id = session::get('users_id');

        //テーマのインスタンス化
        $theme = \Theme::forge();
        //テーマにテンプレートのセット
        $theme->set_template('template');
        //テーマのテンプレートにタイトルをセット
        $theme->get_template()->set('title', 'MIETARO');
        //テーマのテンプレートにビューとページデータをセット
        $theme->get_template()->set('content', $theme->view('admin/usersedit', Model_user::usersdata($id)));
        //テーマのテンプレートにビューとページデータをセット
        $theme->get_template()->set('sidebar', $theme->view('sidebar', Controller_Sidebar::data()));
        return $theme;
    }

    public function post_getUsersEditInfo() {
        $userId = Input::post('user_id');
        $result = Model_User::getUsersEditInfo($userId);
        return json_encode($result);
    }

}
