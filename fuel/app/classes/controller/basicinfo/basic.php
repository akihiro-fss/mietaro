<?php

/**
 *
 * 作成日：2017/07/17
 * 更新日：2017/12/21
 * 作成者：戸田滉洋
 * 更新者：戸田滉洋
 *
 */

/**
 * The BasicInfo Controller.
 *
 * 基本情報を表示させる
 * @package app
 * @extends Controller
 */
class Controller_BasicInfo_basic extends Controller {

    public function before() {
        //未ログインの場合、ログインページにリダイレクト
        if (!Auth::check()) {
            Response::redirect('admin/login');
        }
    }

    public function action_index() {
        //POST送信なら
        if (Input::method() == 'POST') {

            //バリデーション
            if (
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
                    Input::post('str_weather_region') == null or
                    Input::post('power_com_id') == null or
                    Input::post('str_ct_1') == null or
                    Input::post('str_ct_2') == null or
                    Input::post('str_vt_1') == null or
                    Input::post('str_vt_2') == null or
                    Input::post('contract_de') == null or
                    Input::post('emission_factor') == null or
                    Input::post('conversion_factor') == null
            ) {
                Session::set_flash('error', '入力されてない箇所があります');
                Response::redirect('basicinfo/basic');
            } else if (false == preg_match('/^[0-9]{3}-[0-9]{4}$/', Input::post('str_pos_code'))) {
                Session::set_flash('error', '郵便番号を正しく入力してください');
                Response::redirect('basicinfo/basic');
            } else if (false == preg_match('/\d{2,5}[-(]\d{1,4}[-)]\d{4}/', Input::post('str_phone_num'))) {
                Session::set_flash('error', '電話番号を正しく入力してください');
                Response::redirect('basicinfo/basic');
            } else if (false == preg_match('/\d{2,5}[-(]\d{1,4}[-)]\d{4}/', Input::post('str_phone_num'))) {
                Session::set_flash('error', 'FAX番号を正しく入力してください');
                Response::redirect('basicinfo/basic');
            } else if (false == preg_match('/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/iD', Input::post('str_email_addres'))) {
                Session::set_flash('error', 'Eメールアドレスを正しく入力してください');
                Response::redirect('basicinfo/basic');
            } else if (false == preg_match('/\d/', Input::post('str_ct_1'))) {
                Session::set_flash('error', 'CT比1次側は数字を入力してください');
                Response::redirect('basicinfo/basic');
            } else if (false == preg_match('/\d/', Input::post('str_ct_2'))) {
                Session::set_flash('error', 'CT比2次側は数字を入力してください');
                Response::redirect('basicinfo/basic');
            } else if (false == preg_match('/\d/', Input::post('str_vt_1'))) {
                Session::set_flash('error', 'VT比1次側は数字を入力してください');
                Response::redirect('basicinfo/basic');
            } else if (false == preg_match('/\d/', Input::post('str_vt_2'))) {
                Session::set_flash('error', 'VT比2次側は数字を入力してください');
                Response::redirect('basicinfo/basic');
            } else if (false == preg_match('/\d/', Input::post('contract_de'))) {
                Session::set_flash('error', '契約電力は数字を入力してください');
                Response::redircet('basicinfo/basic');
            } else if (false == preg_match('/\d/', Input::post('emission_factor'))) {
                Session::set_flash('error', 'CO2排出係数は数字を入力してください');
                Response::redircet('basicinfo/basic');
            } else if (false == preg_match('/\d/', Input::post('conversion_factor'))) {
                Session::set_flash('error', '原油換算係数は数字で入力してください');
                Response::redirect('basicinfo/basic');
            }

            $str_id = Session::get('str_id');
            Session::delete('str_id');
            $data = (object) array();
            $data->str_na = Input::post('str_na');
            $data->pref_id = Input::post('pref_id');
            $data->str_pos_code = Input::post('str_pos_code');
            $data->str_street_addres = Input::post('str_street_addres');
            $data->str_phone_num = Input::post('str_phone_num');
            $data->str_fax_num = Input::post('str_fax_num');
            $data->str_info = Input::post('str_info');
            $data->latitude = Input::post('latitude');
            $data->longitude = Input::post('longitude');
            $data->str_email_addres = Input::post('str_email_addres');
            $data->str_weather_region = Input::post('str_weather_region');
            $data->str_memo = Input::post('str_memo');
            $data->power_com_id = Input::post('power_com_id');
            $data->str_ct_1 = Input::post('str_ct_1');
            $data->str_ct_2 = Input::post('str_ct_2');
            $data->str_vt_1 = Input::post('str_vt_1');
            $data->str_vt_2 = Input::post('str_vt_2');
            $data->contract_de = Input::post('contract_de');
            $data->emission_factor = Input::post('emission_factor');
            $data->conversion_factor = Input::post('conversion_factor');

            $updatestore = Model_BasicInfo::strupdate($str_id, $data);
            //更新が出来たかどうか確認するモジュールが必要である
            if ($updatestore) {
                //登録成功のメッセージ
                Session::set_flash('success', '更新しました');
                //indexページへ移動
            } else {
                //データが更新されなかったら
                Session::set_flash('error', '更新されませんでした');
            }
        }
        //テーマのインスタンス化
        $theme = \Theme::forge();
        //テーマにテンプレートのセット
        $theme->set_template('template');
        //テーマのテンプレートにタイトルをセット
        $theme->get_template()->set('title', 'MIETARO');
        //テーマのテンプレートにビューとページデータをセット
        $theme->get_template()->set('content', $theme->view('basicinfo/basic', Model_BasicInfo::strdata()));
        //テーマのテンプレートにビューとページデータをセット
        $theme->get_template()->set('sidebar', $theme->view('sidebar'));
        return $theme;
    }

}
