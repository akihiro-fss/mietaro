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
 * The admin Views.
 *
 * @package app
 * @extends lostpass
 */
class Controller_admin_lostpass extends Controller_Template
{
  //ログイン
  public function action_index(){
    //テーマのインスタンス化
    $theme=\Theme::forge();
    //テーマにテンプレートのセット
    $theme->set_template('template');
    //テーマのテンプレートにタイトルをセット
    $theme->get_template()->set('title','MIETARO');
    //テーマのテンプレートにビューとページデータをセット
    $theme->get_template()->set('header',$theme->view('admin/lostpass'));
    return $theme;
  }
}
