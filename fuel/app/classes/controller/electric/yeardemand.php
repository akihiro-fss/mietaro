<?php

/**
 *
 * 作成日：2018/08/19
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

class Controller_Electric_yearDemand extends Controller
{
  public function before()
  {
    //未ログインの場合、ログインページにリダイレクト
    if(!Auth::check()){
      Response::redirect('admin/login');
    }
  }
  public function action_index()
  {
      $yeardata = Model_Electric::yeardata();
      //テーマのインスタンス化
      $theme = \Theme::forge();
      //テーマにテンプレートのセット
      $theme->set_template('template');
      //テーマのテンプレートにタイトルをセット
      $theme->get_template()->set('title', 'MIETARO');
      //テーマのテンプレートにビューとページデータをセット
      $theme->get_template()->set('content', $theme->view('electric/yeardemand', $yeardata)->set('yearData',$yeardata));
      $theme->get_template()->set('sidebar', $theme->view('sidebar'));
      return $theme;
  }
}
