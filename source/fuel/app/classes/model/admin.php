<?php
/**
 *
 * 作成日：2017/07/19
 * 更新日：2017/7/19
 * 作成者：戸田滉洋
 * 更新者：戸田滉洋
 * 
 */

/**
 * The Admin Model.
 *
 * userデータの転送
 * @package app
 * @extends Model
 * 
 */
class Model_Admin extends \Orm\Model {

    //テーブル名の指定(usersテーブルを指定）
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
        $val->add_field('email', 'メールアドレス', 'required|valid_email');
        $val->add_field('password', 'パスワード', 'required|max_length[100]');
        return $val;
    }

    //ページデータの取得
    public static function pagedata($lines = 10) {
        //配列の初期化
        $data = array();
        //データ件数の取得
        $count = Model_User::count();
        //Paginationの環境設定
        $config = array(
            //'pagination_url' => 'test/admin/select',
            'uri_segment' => 'page',
            //'uri_segment' => 3,
            'num_links' => 4,
            'per_page' => $lines,
            'total_items' => $count,
            'template' => array(
                'wrapper_start' => '<div class="pagination"><ul>',
                'wrapper_end' => '</ul></div>',
                'previous_start' => '<li class="previous">',
                'previous_end' => '</li>',
                'previous_inactive_start' => '<li class="active"><a href="#">',
                'previous_inactive_end' => '</a></li>',
                'next_inactive_start' => '<li class="active"><a href="#">',
                'next_inactive_end' => '</a></li>',
                'next_start' => '<li class="next">',
                'next_end' => '</li></ul>',
                'active_start' => '<li class="active"><a href="#">',
                'active_end' => '</a></li>',
        ));
        //Paginationのセット
        Pagination::set_config($config);
        //ページデータの取得
        // カテゴリ1の総数を取得する。
        $auth = Auth::instance();
        $authgroup = $auth->get_groups();
        $groups = $authgroup['0'];
        $group = $groups['1'];

        if ($group == 100) {
            $query = Model_User::query();
            $total_items = $query->count();
        } else {
            $str_id = $auth->get_str_id();
            $query = Model_User::query();
            $total_items = $query
                    ->where('str_id', '=', [$str_id])
                    ->count();
        }

        // Paginationインスタンスを生成
        $pagination = Pagination::forge('users', [
                    'total_items' => $total_items,
                    'per_page' => 20,
                    'uri_segment' => 'page',
        ]);
        $data['pagination'] = $pagination;

        // 現在のページのユーザーのリストを取得
        $data['users'] = $query
                ->order_by('created_at', 'desc')
                ->limit(Pagination::get('per_page'))
                ->offset(Pagination::get('offset'))
                ->get();
        return $data;
    }

    //Configデータの取得
    public static function config_groups() {
        //config/simpleauthのgroups配列を取得
        $config = Config::get('simpleauth.groups');
        //取得配列の再構成
        foreach ($config as $key => $row):
            $groups[$key] = $row['name'];
        endforeach;
        //配列$groupsを返す
        return $groups;
    }

}
