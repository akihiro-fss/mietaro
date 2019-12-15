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

class Controller_Electric_month extends Controller
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
     $monthdata = Model_Electric::monthdaydata();
     //テーマのインスタンス化
     $theme=\Theme::forge();
     //テーマにテンプレートのセット
     $theme->set_template('template');
     //テーマのテンプレートにタイトルをセット
     $theme->get_template()->set('title','MIETARO');
     //テーマのテンプレートにビューとページデータをセット
     $theme->get_template()->set('content', $theme->view('electric/month', $monthdata)->set('monthData',$monthdata));
     $theme->get_template()->set('sidebar',$theme->view('sidebar'));
     return $theme;
  }
}
