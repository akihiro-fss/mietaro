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
 * ログイン中のユーザ情報
 * @package app
 * @extends Controller
 */
class Controller_BasicInfo_createstore extends Controller_admin_login {

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

            if (Input::post('ep_id') == null or
                    Input::post('str_na') == null or
                    Input::post('pref_id') == null or
                    Input::post('str_pos_code') == null or
                    Input::post('str_street_addres') == null or
                    Input::post('str_phone_num') == null or
                    Input::post('str_fax_num') == null or
                    Input::post('str_info') == null or
                    Input::post('latitude') == null or
                    Input::post('longitude') == null or
                    Input::post('str_email_addres') == null or
                    Input::post('str_memo') == null or
                    Input::post('str_ct_1') == null or
                    Input::post('str_ct_2') == null or
                    Input::post('str_vt_1') == null or
                    Input::post('str_vt_2') == null or
                    Input::post('power_com_id') == null or
                    Input::post('demand_alarm') == null or
                    Input::post('contract_de') == null or
                    Input::post('emission_factor') == null or
                    Input::post('conversion_factor') == null
            ) {
                Session::set_flash('error', '入力されてない箇所があります');
                Response::redirect('basicinfo/createstore');
            } else if (false == preg_match('/^[0-9]{3}-[0-9]{4}$/', Input::post('str_pos_code'))) {
                Session::set_flash('error', '郵便番号を正しく入力してください');
                Response::redirect('basicinfo/createstore');
            } else if (false == preg_match('/\d{2,5}[-(]\d{1,4}[-)]\d{4}/', Input::post('str_phone_num'))) {
                Session::set_flash('error', '電話番号を正しく入力してください');
                Response::redirect('basicinfo/createstore');
            } else if (false == preg_match('/\d{2,5}[-(]\d{1,4}[-)]\d{4}/', Input::post('str_phone_num'))) {
                Session::set_flash('error', 'FAX番号を正しく入力してください');
                Response::redirect('basicinfo/createstore');
            } else if (false == preg_match('/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/iD', Input::post('str_email_addres'))) {
                Session::set_flash('error', 'Eメールアドレスを正しく入力してください');
                Response::redirect('basicinfo/createstore');
            } else if (false == preg_match('/\d/', Input::post('str_ct_1'))) {
                Session::set_flash('error', 'CT比1次側は数字を入力してください');
                Response::redirect('basicinfo/createstore');
            } else if (false == preg_match('/\d/', Input::post('str_ct_2'))) {
                Session::set_flash('error', 'CT比2次側は数字を入力してください');
                Response::redirect('basicinfo/createstore');
            } else if (false == preg_match('/\d/', Input::post('str_vt_1'))) {
                Session::set_flash('error', 'VT比1次側は数字を入力してください');
                Response::redirect('basicinfo/createstore');
            } else if (false == preg_match('/\d/', Input::post('str_vt_2'))) {
                Session::set_flash('error', 'VT比2次側は数字を入力してください');
                Response::redirect('basicinfo/createstore');
            } else if (false == preg_match('/\d/', Input::post('demand_alarm'))) {
                Session::set_flash('error', 'デマンド警報値は数字を入力してください');
                Response::redirect('basicinfo/createstore');
            } else if (false == preg_match('/\d/', Input::post('contract_de'))) {
                Session::set_flash('error', '契約電力は数字を入力してください');
                Response::redircet('basicinfo/createstore');
            } else if (false == preg_match('/\d/', Input::post('emission_factor'))) {
                Session::set_flash('error', 'CO2排出係数は数字を入力してください');
                Response::redircet('basicinfo/createstore');
            } else if (false == preg_match('/\d/', Input::post('conversion_factor'))) {
                Session::set_flash('error', '原油換算係数は数字で入力してください');
                Response::redirect('basicinfo/createstore');
            }

            $data = array();
            //バリデーションOKなら
            if (Auth::member(100)) {
                $data['ep_id'] = Input::post('ep_id');
            } else {
                $data['ep_id'] = Auth::get_ep_id();
            }

            $data['str_na'] = Input::post('str_na');
            $data['pref_id'] = Input::post('pref_id');
            $data['str_pos_code'] = Input::post('str_pos_code');
            $data['str_street_addres'] = Input::post('str_street_addres');
            $data['str_phone_num'] = Input::post('str_phone_num');
            $data['str_fax_num'] = Input::post('str_fax_num');
            $data['str_info'] = Input::post('str_info');
            $data['latitude'] = Input::post('latitude');
            $data['longitude'] = Input::post('longitude');
            $data['str_email_addres'] = Input::post('str_email_addres');
            $data['str_weather_region'] = Input::post('str_weather_region');
            $data['str_memo'] = Input::post('str_memo');
            $data['str_ct_1'] = Input::post('str_ct_1');
            $data['str_ct_2'] = Input::post('str_ct_2');
            $data['str_vt_1'] = Input::post('str_vt_1');
            $data['str_vt_2'] = Input::post('str_vt_2');
            $data['power_com_id'] = Input::post('power_com_id');
            $data['demand_alarm'] = Input::post('demand_alarm');
            $data['contract_de'] = Input::post('contract_de');
            $data['emission_factor'] = Input::post('emission_factor');
            $data['conversion_factor'] = Input::post('conversion_factor');

            $createstore = Model_BasicInfo::createstore($data);

            if ($createstore) {
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
        $this->theme->get_template()->set('content', $this->theme->view('basicinfo/createstore'));
        //テーマのテンプレートにビューとページデータをセット
        $this->theme->get_template()->set('sidebar', $this->theme->view('sidebar', Controller_Sidebar::data()));
        return $this->theme;
    }

}
