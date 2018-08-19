<?php

/**
 *
 * 作成日：2017/07/17
 * 更新日：2018/08/19
 * 作成者：戸田滉洋
 * 更新者：戸田滉洋
 *
 */

/**
 * The BasicInfo Controller.
 *
 * @package app
 * @extends Controller
 */
class Controller_Electric_oneDay extends Controller {

    public function before() {
        //未ログインの場合、ログインページにリダイレクト
        if (!Auth::check()) {
            Response::redirect('admin/login');
        }
    }

    public function action_index() {
        //一日分のデータを取得
        $oneday = Model_Electric::onedaydata();
        //テーマのインスタンス化
        $theme = \Theme::forge();
        //テーマにテンプレートのセット
        $theme->set_template('template');
        //テーマのテンプレートにタイトルをセット
        $theme->get_template()->set('title', 'MIETARO');
        //テーマのテンプレートにビューとページデータをセット
        $theme->get_template()->set('content', $theme->view('electric/oneday', $oneday)->set('onedayData',$oneday));
        //テーマのテンプレートにビューとページデータをセット
        $theme->get_template()->set('sidebar', $theme->view('sidebar'));
        return $theme;
    }

    public function post_getOnedayComment() {
        $strId = Input::post('str_id');
        $targetDate = Input::post('target_date');

        $result = Model_OnedayComment::getOnedayComment($strId, $targetDate);

        return json_encode($result);
    }

    public function post_addOnedayComment() {
        $strId = Input::post('str_id');
        $targetDate = Input::post('target_date');
        $comment = Input::post('comment');

        $result = Model_OnedayComment::addOnedayComment($strId, $targetDate,$comment);

        return $result;
    }

}
