<?php

/**
 *
 * 作成日：2017/07/16
 * 最終更新日：2017/12/23
 * 作成者：戸田滉洋
 * 最終更新者：戸田滉洋
 *
 */

/**
 * The Top BasicInfo.
 *
 * ログイン中のユーザの企業情報
 * @package app
 * @extends Controller
 */
class Controller_BasicInfo_EnterPrice extends Controller {

    public function before() {
        //未ログインの場合、ログインページにリダイレクト
        if (!Auth::check()) {
            Response::redirect('admin/login');
        }
        if (!Auth::member(100)) {
            Response::redirect('top/news');
        }
    }

    public function action_index() {

        //POST送信なら
        if (Input::method() == 'POST') {

            if (
                    Input::post('ep_na') == null or
                    Input::post('ep_pref_id') == null or
                    Input::post('ep_pos_code') == null or
                    Input::post('ep_street_addres') == null or
                    Input::post('ep_phone_num') == null or
                    Input::post('ep_email') == null
            ) {
                Session::set_flash('error', '入力されてない箇所があります');
                Response::redirect('basicinfo/enterprice');
            } else if (false == preg_match('/^[0-9]{3}-[0-9]{4}$/', Input::post('ep_pos_code'))) {
                Session::set_flash('error', '郵便番号を正しく入力してください');
                Response::redirect('basicinfo/enterprice');
            } else if (false == preg_match('/\d{2,5}[-(]\d{1,4}[-)]\d{4}/', Input::post('ep_phone_num'))) {
                Session::set_flash('error', '電話番号を正しく入力してください');
                Response::redirect('basicinfo/enterprice');
            } else if (false == preg_match('/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/iD', Input::post('ep_email'))) {
                Session::set_flash('error', 'Eメールアドレスを正しく入力してください');
                Response::redirect('basicinfo/enterprice');
            }

            $ep_id = Session::get('ep_id');
            Session::delete('ep_id');
            $data = array();
            //バリデーションOKなら
            $data['ep_na'] = Input::post('ep_na');
            $data['ep_pref_id'] = Input::post('ep_pref_id');
            $data['ep_pos_code'] = Input::post('ep_pos_code');
            $data['ep_street_addres'] = Input::post('ep_street_addres');
            $data['ep_phone_num'] = Input::post('ep_phone_num');
            $data['ep_email'] = Input::post('ep_email');

            $updateEP = Model_EnterPrice::updateEP($data, $ep_id);

            if ($updateEP) {
                //登録成功のメッセージ
                Session::set_flash('success', '更新しました');
                //indexページへ移動
            } else {
                //データが保存されなかったら
                Session::set_flash('error', '登録されませんでした');
            }
        }
        //テーマのインスタンス化
        $theme = \Theme::forge();
        //テーマにテンプレートのセット
        $theme->set_template('template');
        //テーマのテンプレートにタイトルをセット
        $theme->get_template()->set('title', 'MIETARO');
        //テーマのテンプレートにビューとページデータをセット
        $theme->get_template()->set('content', $theme->view('basicinfo/enterprice', Model_EnterPrice::epdata()));
        //テーマのテンプレートにビューとページデータをセット
        $theme->get_template()->set('sidebar', $theme->view('sidebar'));
        return $theme;
    }

}
