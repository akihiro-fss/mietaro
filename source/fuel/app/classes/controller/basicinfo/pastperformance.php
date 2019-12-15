<?php

/**
 *
 * 作成日：2017/12/9
 * 更新日：2017/12/9
 * 作成者：戸田滉洋
 * 更新者：戸田滉洋
 *
 */

/**
 * The BasicInfo Controller.
 *
 * 導入前実績
 * @package app
 * @extends Controller
 */
class Controller_BasicInfo_PastPerformance extends Controller {

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
                    Input::post('p_year') == null or
                    Input::post('january_kwh') == null or
                    Input::post('january_kw') == null or
                    Input::post('february_kwh') == null or
                    Input::post('february_kw') == null or
                    Input::post('march_kwh') == null or
                    Input::post('march_kw') == null or
                    Input::post('april_kwh') == null or
                    Input::post('april_kw') == null or
                    Input::post('may_kwh') == null or
                    Input::post('may_kw') == null or
                    Input::post('june_kwh') == null or
                    Input::post('june_kw') == null or
                    Input::post('july_kwh') == null or
                    Input::post('july_kw') == null or
                    Input::post('august_kwh') == null or
                    Input::post('august_kw') == null or
                    Input::post('september_kwh') == null or
                    Input::post('september_kw') == null or
                    Input::post('october_kwh') == null or
                    Input::post('october_kw') == null or
                    Input::post('november_kwh') == null or
                    Input::post('november_kw') == null or
                    Input::post('december_kwh') == null or
                    Input::post('december_kw') == null
            ) {
                Session::set_flash('error', '入力されてない箇所があります');
                Response::redirect('basicinfo/pastperformance');
            } else if (false == preg_match('/\d/', Input::post('p_year')) or
                    false == preg_match('/\d/', Input::post('january_kwh')) or
                    false == preg_match('/\d/', Input::post('january_kw')) or
                    false == preg_match('/\d/', Input::post('february_kwh')) or
                    false == preg_match('/\d/', Input::post('february_kw')) or
                    false == preg_match('/\d/', Input::post('march_kwh')) or
                    false == preg_match('/\d/', Input::post('march_kw')) or
                    false == preg_match('/\d/', Input::post('april_kwh')) or
                    false == preg_match('/\d/', Input::post('april_kw')) or
                    false == preg_match('/\d/', Input::post('may_kwh')) or
                    false == preg_match('/\d/', Input::post('may_kw')) or
                    false == preg_match('/\d/', Input::post('june_kwh')) or
                    false == preg_match('/\d/', Input::post('june_kw')) or
                    false == preg_match('/\d/', Input::post('july_kwh')) or
                    false == preg_match('/\d/', Input::post('july_kw')) or
                    false == preg_match('/\d/', Input::post('august_kwh')) or
                    false == preg_match('/\d/', Input::post('august_kw')) or
                    false == preg_match('/\d/', Input::post('september_kwh')) or
                    false == preg_match('/\d/', Input::post('september_kw')) or
                    false == preg_match('/\d/', Input::post('october_kwh')) or
                    false == preg_match('/\d/', Input::post('october_kw')) or
                    false == preg_match('/\d/', Input::post('november_kwh')) or
                    false == preg_match('/\d/', Input::post('november_kwh')) or
                    false == preg_match('/\d/', Input::post('december_kwh')) or
                    false == preg_match('/\d/', Input::post('december_kw'))
            ) {
                Session::set_flash('error', '数字を入力してください');
                Response::redirect('basicinfo/pastperformance');
            }

            $str_id = Session::get('str_id');
            Session::delete('str_id');
            $data = array();
            $data['p_year'] = Input::post('p_year');
            $data['january_kwh'] = Input::post('january_kwh');
            $data['january_kw'] = Input::post('january_kw');
            $data['february_kwh'] = Input::post('february_kwh');
            $data['february_kw'] = Input::post('february_kw');
            $data['march_kwh'] = Input::post('march_kwh');
            $data['march_kw'] = Input::post('march_kw');
            $data['april_kwh'] = Input::post('april_kwh');
            $data['april_kw'] = Input::post('april_kw');
            $data['may_kwh'] = Input::post('may_kwh');
            $data['may_kw'] = Input::post('may_kw');
            $data['june_kwh'] = Input::post('june_kwh');
            $data['june_kw'] = Input::post('june_kw');
            $data['july_kwh'] = Input::post('july_kwh');
            $data['july_kw'] = Input::post('july_kw');
            $data['august_kwh'] = Input::post('august_kwh');
            $data['august_kw'] = Input::post('august_kw');
            $data['september_kwh'] = Input::post('september_kwh');
            $data['september_kw'] = Input::post('september_kw');
            $data['october_kwh'] = Input::post('october_kwh');
            $data['october_kw'] = Input::post('october_kw');
            $data['november_kwh'] = Input::post('november_kwh');
            $data['november_kw'] = Input::post('november_kw');
            $data['december_kwh'] = Input::post('december_kwh');
            $data['december_kw'] = Input::post('december_kw');

            $createPP = Model_PastPerformance::createPP($data, $str_id);
            //更新が出来たかどうか確認するモジュールが必要である
            if ($createPP) {
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
        $theme->get_template()->set('content', $theme->view('basicinfo/pastperformance', Model_PastPerformance::pastdata()));
        //テーマのテンプレートにビューとページデータをセット
        $theme->get_template()->set('sidebar', $theme->view('sidebar'));
        return $theme;
    }

}
