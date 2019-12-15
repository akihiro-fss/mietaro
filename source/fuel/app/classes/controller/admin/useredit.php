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
 * ログイン中のユーザ情報
 * @package app
 * @extends Controller
 */
class Controller_admin_userEdit extends Controller_admin_login {

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

            if (false == preg_match('/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/iD', Input::post('email'))) {
                Session::set_flash('error', 'Eメールアドレスを正しく入力してください');
                Response::redirect('admin/useredit');
            }

            $user_id = Auth::get_user_id();
            $id = $user_id['1'];
            $data = new stdClass;
            $data->email = Input::post('email');
            $data->str_id = Input::post('str_id');
            if (is_null($data->str_id)) {
                $data->str_id = Auth::get_str_id();
            }

            $updateuser = Model_User::userupdate($id, $data);
            //更新が出来たかどうか確認するモジュールが必要である
            if ($updateuser) {
                //登録成功のメッセージ
                Session::set_flash('success', '更新しました');
                Response::redirect('admin/user');
                //indexページへ移動
            } else {
                //データが更新されなかったら
                Session::set_flash('error', '更新されませんでした');
            }
        }

        $user_id = Auth::get_user_id();
        $id = $user_id['1'];

        //テーマのインスタンス化
        $theme = \Theme::forge();
        //テーマにテンプレートのセット
        $theme->set_template('template');
        //テーマのテンプレートにタイトルをセット
        $theme->get_template()->set('title', 'MIETARO');
        //テーマのテンプレートにビューとページデータをセット
        $theme->get_template()->set('content', $theme->view('admin/useredit', Model_user::userdata())->set('user_id',$id));
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
