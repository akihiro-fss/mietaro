<?php

/**
 *
 * 作成日：2017/07/17
 * 更新日：2017/12/30
 * 作成者：戸田滉洋
 * 更新者：丸山　隼
 *
 */

/**
 * The BasicInfo Controller.
 *
 * 警報値と目標値を表示させる
 * @package app
 * @extends Controller
 *
 */
class Controller_BasicInfo_alarmValue extends Controller {

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
                    is_null(Input::post('demand_alarm')) or
                    is_null(Input::post('january')) or
                    is_null(Input::post('february')) or
                    is_null(Input::post('march')) or
                    is_null(Input::post('April')) or
                    is_null(Input::post('may')) or
                    is_null(Input::post('june')) or
                    is_null(Input::post('july')) or
                    is_null(Input::post('august')) or
                    is_null(Input::post('september')) or
                    is_null(Input::post('october')) or
                    is_null(Input::post('december'))
            ) {
                //処理記載必要あり
            } else if (
                    is_null(Input::post('demand_alarm'))
            ) {
                if (false == preg_match('/\d/', Input::post('demand_alarm'))) {
                    Session::set_flash('error', 'デマンド警報値は数字を入力してください');
                    Response::redirect('basicinfo/alarmvalue');
                } else {
                    $demand = Input::post('demand_alarm');
                    $update_demand_alarm = Model_BasicInfo::update_demand_alarm($demand);
                    if ($update_demand_alarm) {
                        //登録成功のメッセージ
                        Response::redirect('top/news');
                        //indexページへ移動
                    } else {
                        //データが保存されなかったら
                        Session::set_flash('error', '登録されませんでした');
                    }
                }
            } else if (
                    is_null(Input::post('january')) or
                    is_null(Input::post('february')) or
                    is_null(Input::post('march')) or
                    is_null(Input::post('April')) or
                    is_null(Input::post('may')) or
                    is_null(Input::post('june')) or
                    is_null(Input::post('july')) or
                    is_null(Input::post('august')) or
                    is_null(Input::post('september')) or
                    is_null(Input::post('october')) or
                    is_null(Input::post('december'))
            ) {
                //処理記載必要あり
            } else {
                Session::set_flash('error', '入力されてない箇所があります');
                Response::redirect('basicinfo/alarmvalue');
            }
            $auth = Auth::instance();
            $str_id = $auth->get_str_id();
            $datanull = Model_MonthTarget::monthdata($str_id);
            if ($datanull = 0) {

            } else {

            }
        }
        //テーマのインスタンス化
        $theme = \Theme::forge();
        //テーマにテンプレートのセット
        $theme->set_template('template');
        //テーマのテンプレートにタイトルをセット
        $theme->get_template()->set('title', 'MIETARO');
        //テーマのテンプレートにビューとページデータをセット
        //店舗情報セット
        $data = array();
        $data['demand'] = Model_BasicInfo::strdata();
        //店舗の月間目標値セット
        $data['month'] = Model_MonthTarget::monthdata();
        $theme->get_template()->set('content', $theme->view('basicinfo/alarmvalue', $data));
        //テーマのテンプレートにビューとページデータをセット
        $theme->get_template()->set('sidebar', $theme->view('sidebar'));
        return $theme;
    }

    public function action_update() {
        //POST送信なら
        if (Input::method() == 'POST') {
            //バリデーション
            $validResult = self::validation();
            if(!$validResult['result']){
                //バリデーションエラー
                Session::set_flash('error', $validResult['message']);
                Response::redirect('basicinfo/alarmvalue');
            }else{
                //更新処理
                $auth = Auth::instance();
                $str_id = $auth->get_str_id();
                $param = Input::post();

                $demandAlarm = $param['demand_alarm'];

                $sql = "UPDATE BasicInfo SET demand_alarm = $demandAlarm WHERE str_id = $str_id";
                \DB::query($sql)->execute();

                $january = $param['january'];
                $february = $param['february'];
                $march = $param['march'];
                $april = $param['april'];
                $may = $param['may'];
                $june = $param['june'];
                $july = $param['july'];
                $august = $param['august'];
                $september = $param['september'];
                $october = $param['october'];
                $november = $param['november'];
                $december = $param['december'];

                $sql = "UPDATE MonthTarget
                          SET january = $january,
                          february = $february,
                          march = $march,
                          april = $april,
                          may = $may,
                          june = $june,
                          july = $july,
                          august = $august,
                          september = $september,
                          october = $october,
                          november = $november,
                          december = $december
                        WHERE str_id = $str_id AND val = 1";
                \DB::query($sql)->execute();
            }

            //更新成功
            Session::set_flash('success', '更新しました');
            Response::redirect('basicinfo/alarmvalue');
        }else{
            //不正アクセスの場合トップページにリダイレクト
            Session::set_flash('error', '不正なページ遷移を検出しました');
            Response::redirect('basicinfo/alarmvalue');
        }
    }


    //バリデーションメソッド
    private static function validation(){
        $validation = array (
            'demandAlarm' => Input::post('demand_alarm'),
            'january' => Input::post('january'),
            'february' => Input::post('february'),
            'march' => Input::post('march'),
            'april' => Input::post('april'),
            'may' => Input::post('may'),
            'june' => Input::post('june'),
            'july' => Input::post('july'),
            'august' => Input::post('august'),
            'september' => Input::post('september'),
            'october' => Input::post('october'),
            'november' => Input::post('november'),
            'december' => Input::post('december'),
        );

        foreach($validation as $key=>$data){
            if($data < 0){
                return array(
                  'result' => false,
                  'message' => '入力値は０以上を入力してください'
                );
            }
            if(is_null($data)){
                return array(
                    'result' => false,
                    'message' => '入力していない値があります',
                );
            }
        }

        return array('result'=>true);
    }

}
