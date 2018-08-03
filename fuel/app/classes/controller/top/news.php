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
 * The Top news.
 *
 * @package app
 * @extends Controller
 */
class Controller_top_news extends Controller {

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
            //バリデーション
            if (Input::post('news') == null) {
                Session::set_flash('error', '入力されてない箇所があります');
                Response::redirect('top/news');
            }

            $news = Input::post('news');
            $createNews = Model_Top::updateNews($news);
            if ($createNews) {
                //登録成功のメッセージ
                Session::set_flash('success', '投稿しました');
            } else {
                //登録できなかった場合
                Session::set_flash('error', '投稿に失敗しました');
            }
        }
        //テーマのインスタンス化
        $theme = \Theme::forge();
        //テーマにテンプレートのセット
        $theme->set_template('template');
        //テーマのテンプレートにタイトルをセット
        $theme->get_template()->set('title', 'MIETARO');
        //テーマのテンプレートにビューとページデータをセット
        $news = Model_Top::news();
        $comment = Model_OnedayComment::onedeyCommentdata();
        $data = array();
        $data['news'] = $news;
        $data['comment'] = $comment;
        $theme->get_template()->set('content', $theme->view('top/news', $data));
        //テーマのテンプレートにビューとページデータをセット
        $theme->get_template()->set('sidebar', $theme->view('sidebar'));
        return $theme;
    }

    public function post_getNews() {

        //現在の日付を取得
        $nowDate = date('Y-m-d');

        //現在日付で登録されているデータを取得
        $result = Model_Top::getNewsByCreatedAt($nowDate);

        return json_encode($result);
    }
}
