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
 * The Top admin.
 *
 * ログイン
 * @package app
 * @extends Controller
 */

class Controller_admin_login extends Controller_Template{
 //beforeアクション
 //public function before(){
 //parent::before();
 //if(!Auth::check()){
 //ログインページへ移動
 //Response::redirect('admin/login');
 //}
 //}
 //ログイン
 public function action_index(){ 
   //すでにログイン済であればログイン後のページへリダイレクト
   Auth::check() and Response::redirect('top/news');
   //POST送信なら
   if(Input::method() == 'POST')
   {
     //Authのインスタンス化
     $auth=Auth::instance();
     //資格情報の取得
     if($auth->login(Input::post('username'),Input::post('password')))
     {
       //認証OKならトップページへ
       Response::redirect('top/news');
     }else
     {
       //認証が失敗したときの処理
       Session::set_flash('error', 'ユーザー名かパスワードが違います。');
     }
   }
   //テーマのインスタンス化
   $theme=\Theme::forge();
   //テーマにテンプレートのセット
   $theme->set_template('template');
   //テーマのテンプレートにタイトルをセット
   $theme->get_template()->set('title','MIETARO');
   //テーマのテンプレートにビューとページデータをセット
   $theme->get_template()->set('content',$theme->view('admin/login'));
   return $theme;
 }
 //ログアウト
 public function action_logout(){
   Auth::logout();
   Session::destroy();
   //ログインページへ移動
   Session::set_flash('error', 'ログアウトしました。');
   Response::redirect('admin/login');
 }
}
