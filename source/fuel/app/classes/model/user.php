<?php
/**
 *
 * 作成日：2017/08/03
 * 更新日：2017/12/30
 * 作成者：戸田滉洋
 * 更新者：丸山　隼
 *
 */

/**
 * The User Model.
 *
 * ユーザ一覧及びユーザ情報
 * @package app
 * @extends Model
 */

class Model_User extends \Orm\Model {

    //テーブル名の指定(モデル名の複数形なら省略可）
    protected static $_table_name = 'users';
    //プロパティのセット
    protected static $_properties = array(
        'id',
        'username',
        'password',
        'group',
        'email',
        'last_login',
        'login_hash',
        'profile_fields',
        'ep_id',
        'str_id',
        'created_at',
        'updated_at'
    );
    protected static $_observers = array(
        'Orm\Observer_CreatedAt' => array(
            'events' => array('before_insert'),
            'mysql_timestamp' => false,
        ),
        'Orm\Observer_UpdatedAt' => array(
            'events' => array('before_save'),
            'mysql_timestamp' => false,
        ),
    );

    //バリデーションの設定
    public static function validate($factory) {
        //バリデーションのインスタンス化
        $val = Validation::forge($factory);
        //バリデーションフィールドの追加
        $val->add_field('username', 'ユーザー名', 'required|max_length[255]');
        $val->add_field('password', 'パスワード', 'required|max_length[100]');
        return $val;
    }

    //個人データの取得
    public static function userdata() {
        //Authのインスタンス化
        $auth = Auth::instance();
        $email = $auth->get_email();
        //$str_id = $auth->get_str_id();
        $data['data'] = Model_User::find('first', array('where' => array('email' => $email)));
        return $data;
    }

    public static function usersdata($id) {

        $group = Auth::get_group();
        if ($group != 100) {
            if ($group != 50) {
                Response::redirect('top/news');
            }
            $usersdata = array();
            $usersdata = Model_User::find($id);
            $ep_id = $auth->get_ep_id();
            if ($ep_id != $usersdata->ep_id) {
                Response::redirect('top/news');
            }
        }
        //data配列の初期化
        $data = array();
        $data['data'] = Model_User::find('first', array('where' => array('id' => $id)));
        $data['user_id'] = $id;
        return $data;
    }

    public static function theme($template, $content) {
        //テーマのインスタンス化
        $theme = \Theme::forge();
        //テーマにテンプレートのセット
        $theme->set_template($template);
        //テーマのテンプレートにタイトルをセット
        $theme->get_template()->set('title', 'MIETARO');
        //モデルColassrotからデータを取得
        //テーマのテンプレートにビューとページデータをセット
        $theme->get_template()->set('content', $theme->view($content, Model_User::userdata()));
        return $theme;
    }

    //user情報を更新
    public static function userupdate($id, $data) {
        $query = Model_User::find($id);
        $query->email = $data->email;
        $query->str_id = $data->str_id;
        $query->save();
        return $query;
    }

    public static function getUsersEditInfo($userId){
        //ユーザ情報取得
        $query ="SELECT ep_id,str_id FROM users WHERE id = $userId";
        $usersData = \DB::query($query)->execute()->as_array();
        $usersData = reset($usersData);

        //ユーザの元々の登録情報保持
        $epId = $usersData['ep_id'];
        $selectedStrId = $usersData['str_id'];

        //企業IDに紐づく店舗IDを取得
        $query ="SELECT str_id,str_na FROM BasicInfo WHERE ep_id = $epId";
        $basicInfoData = \DB::query($query)->execute()->as_array();

        //セレクトボックスに表示する情報を作成
        $result = array();
        foreach($basicInfoData as $data){
            if($data['str_id'] == $selectedStrId){
                $result[] = array(
                    'str_id' => $data['str_id'],
                    'str_na' => $data['str_na'],
                    'seleced' => true,
                );
            }else{
                $result[] = array(
                    'str_id' => $data['str_id'],
                    'str_na' => $data['str_na'],
                    'seleced' => false,
                );
            }

        }
        return $result;
    }

}
