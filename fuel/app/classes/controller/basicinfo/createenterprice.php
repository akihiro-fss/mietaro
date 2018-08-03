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
 * The Top BasicInfo.
 *
 * 新規企業作成
 * @package app
 * @extends Controller
 */

class Controller_BasicInfo_createEnterPrice extends Controller_admin_login {

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

    //新規ユーザー登録
    public function action_index() {

        //POST送信なら
        if (Input::method() == 'POST') {

            if (
                    Input::post('ep_na') == null or
                    Input::post('ep_pref_id') == null or
                    Input::post('ep_pos_code') == null or
                    Input::post('ep_street_addres') == null or
                    Input::post('ep_phone_num') == null or
                    Input::post('ep_email_addres') == null
            ) {
                Session::set_flash('error', '入力されてない箇所があります');
                Response::redirect('basicinfo/createenterprice');
            } else if (false == preg_match('/^[0-9]{3}-[0-9]{4}$/', Input::post('ep_pos_code'))) {
                Session::set_flash('error', '郵便番号を正しく入力してください');
                Response::redirect('basicinfo/createenterprice');
            } else if (false == preg_match('/\d{2,5}[-(]\d{1,4}[-)]\d{4}/', Input::post('ep_phone_num'))) {
                Session::set_flash('error', '電話番号を正しく入力してください');
                Response::redirect('basicinfo/createenterprice');
            } else if (false == preg_match('/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/iD', Input::post('ep_email_addres'))) {
                Session::set_flash('error', 'Eメールアドレスを正しく入力してください');
                Response::redirect('basicinfo/createenterprice');
            }

            $data = array();
            //バリデーションOKなら
            $data['ep_na'] = Input::post('ep_na');
            $data['ep_pref_id'] = Input::post('ep_pref_id');
            $data['ep_pos_code'] = Input::post('ep_pos_code');
            $data['ep_street_addres'] = Input::post('ep_street_addres');
            $data['ep_phone_num'] = Input::post('ep_phone_num');
            $data['ep_fax_num'] = Input::post('ep_fax_num');
            $data['ep_email_addres'] = Input::post('ep_email_addres');


            $createEP = Model_EnterPrice::createEP($data);

            if ($createEP) {
                //登録成功のメッセージ
                Session::set_flash('success', '登録しました');
                //indexページへ移動
            } else {
                //データが保存されなかったら
                Session::set_flash('error', '登録されませんでした');
            }
        }

        //POST送信でなければ
        //テーマのインスタンス化
        $this->theme = \Theme::forge();
        //テーマにテンプレートのセット
        $this->theme->set_template('template');
        //テーマのテンプレートにタイトルをセット
        $this->theme->get_template()->set('title', 'MIETARO');
        //テーマのテンプレートにビューとページデータをセット
        $this->theme->get_template()->set('content', $this->theme->view('basicinfo/createenterprice'));
        //テーマのテンプレートにビューとページデータをセット
        $theme->get_template()->set('sidebar', $theme->view('sidebar'));
        return $this->theme;
    }

}
