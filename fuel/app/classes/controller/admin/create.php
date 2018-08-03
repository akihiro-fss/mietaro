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
 * The Top Controller.
 *
 * 新規ユーザ作成
 * @package app
 * @extends Controller
 */
class Controller_admin_create extends Controller_admin_login {

    public function before() {
        //beforeアクション
        parent::before();
        if (!Auth::check()) {
            //ログインページへ移動
            Response::redirect('admin/login');
        }
        $auth = Auth::instance();
        $group = $auth->get_group();
        if ($group == 1) {
            Response::redirect('top/news');
        }
    }

    //新規ユーザ登録
    public function action_index() {

        //POST送信なら
        if (Input::method() == 'POST') {

            if (Input::post('username') == null or Input::post('email') == null or Input::post('password') == null) {
                Session::set_flash('error', '入力されてない箇所があります');
                Response::redirect('admin/create');
            } else if (false == preg_match('/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/iD', Input::post('email'))) {
                Session::set_flash('error', 'Eメールアドレスを正しく入力してください');
                Response::redirect('admin/create');
            }


            //バリデーションの初期化
            $val = Model_Admin::validate('create');
            //バリデーションOKなら
            if ($val->run()) {
                //POSTデータを受け取る
                $auth_group = Auth::member();
                if ($auth_group == 100) {
                    $username = Input::post('username');
                    $email = Input::post('email');
                    $password = Input::post('password');
                    $group = Input::post('group');
                    $str_id = Input::post('str_id');
                    $ep_id = Input::post('ep_id');
                } else {
                    $username = Input::post('username');
                    $email = Input::post('email');
                    $password = Input::post('password');
                    $group = 1;
                    $auth_search = Auth::instance();
                    $str_id = Input::post('str_id');
                    $ep_id = Input::post('ep_id');
                }
                //重複確認
                $username_count = Model_Admin::count(array('where' => array(array('username' => $username))));
                $email_count = Model_Admin::count(array('where' => array(array('email' => $email))));
                //ユーザー名が重複していたら
                if ($username_count > 0) {
                    Session::set_flash('error', 'ユーザー名が重複しています');
                    Response::redirect('admin/create');
                } else {
                    //Eメールアドレスが重複していたら
                    if ($email_count > 0) {
                        Session::set_flash('error', 'Eメールアドレスが重複しています');
                        Response::redirect('admin/create');
                    }
                    //Authのインスタンス化
                    $auth = Auth::instance();
                    //もしユーザー登録されたら
                    if ($auth->create_user($username, $password, $email, $group, $ep_id, $str_id)) {
                        //登録成功のメッセージ
                        Response::redirect('top/news');
                        //indexページへ移動
                    } else {
                        //データが保存されなかったら
                        Session::set_flash('error', '登録されませんでした');
                    }
                }
            }
            //バリデーションNGなら
            Session::set_flash('error', $val->show_errors());
        }
        //POST送信でなければ
        //テーマのインスタンス化
        $this->theme = \Theme::forge();
        //テーマにテンプレートのセット
        $this->theme->set_template('template');
        //テーマのテンプレートにタイトルをセット
        $this->theme->get_template()->set('title', 'MIETARO');
        //テーマのテンプレートにビューとページデータをセット
        $this->theme->get_template()->set('content', $this->theme->view('admin/create'));
        //テーマのテンプレートにビューとページデータをセット
        $this->theme->get_template()->set('sidebar', $this->theme->view('sidebar'));
        return $this->theme;
    }

    public function post_getStoreList() {
        $epId = Input::post('ep_id');
        $result = Model_BasicInfo::getStoreNameByEpId($epId);
        return json_encode($result);
    }

}
