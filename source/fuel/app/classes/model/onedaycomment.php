<?php

/**
 *
 * 作成日：2017/11/03
 * 更新者：2018/01/01
 * 作成者：戸田滉洋
 * 更新者：戸田滉洋
 */

/**
 * The OndayComment Model.
 *
 * １日のコメント情報
 * @package app
 * @extends Model
 *
 *
 */
class Model_OnedayComment extends \orm\Model {

    protected static $_table_name = 'OneDay_comment';
    protected static $_primary_key = array('com_id');
    protected static $_properties = array(
        'com_id',
        'str_id',
        'comment',
        'comment_at',
        'val',
        'created_at',
        'updated_at',
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

    //クエリ実行用メソッド
    private static function executeDbQuery($query) {
        return \DB::query($query)->execute()->as_array();
    }

    /**
     * コメント取得メソッド
     * @access strId (integer) 対象店舗ID
     * @access targetDate (datetime(YYYYmm)) 対象日付
     * @return Comment (string) 指定日付＆店舗のコメント
     */
    public static function getOnedayComment($strId, $targetDate) {
        if ($targetDate) {
            $target = str_replace('-', '', $targetDate);
            $query = "SELECT comment FROM OneDay_comment WHERE str_id=$strId AND comment_at=$target";
            $result = self::executeDbQuery($query);
        } else {
            $result = array();
        }
        $response = "";
        if (!empty($result)) {
            $response = reset($result);
        }
        return $response;
    }

    /**
     *
     * top及び履歴参照時に表示するコメント取得メソッド
     *
     */
    public static function onedeyCommentdata() {
        $auth = Auth::instance();
        $str_id = $auth->get_str_id();
        $comment = array();
        $query = Model_OnedayComment::query();
        $comment['comment'] = $query
                ->where('str_id', '=', [$str_id])
                ->get();

        return $comment;
    }

    /**
     * 履歴参照のお知らせの情報を
     * ページネーションを作成及び表示
     *
     * @return data
     */
    public static function pagenationdata() {
        $lines = 10;
        //配列の初期化
        $data = array();
        //データ件数の取得
        $count = Model_OnedayComment::count();
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
        $str_id = $auth->get_str_id();
        $query = Model_OnedayComment::query();
        $total_items = $query
                ->where('str_id', '=', [$str_id])
                ->count();

        // Paginationインスタンスを生成
        $pagination = Pagination::forge('ondaycomment', [
                    'total_items' => $total_items,
                    'per_page' => 20,
                    'uri_segment' => 'page',
        ]);
        $data['pagination'] = $pagination;

        // 現在のページのユーザーのリストを取得
        $data['ondaycomment'] = $query
                ->order_by('created_at', 'desc')
                ->limit(Pagination::get('per_page'))
                ->offset(Pagination::get('offset'))
                ->get();
        return $data;
    }

    /**
     * コメント追加メソッド
     * @access strId (integer) 対象店舗ID
     * @access targetDate (datetime(YYYYmm)) 対象日付
     * @access comment (string) 追加したいコメント
     *
     * $strId+$targetDateで既にレコードが存在すればupdateをかける
     * なければinsert
     */
    public static function addOnedayComment($strId, $targetDate, $comment) {

        if ($targetDate) {
            $target = str_replace('-', '', $targetDate);
            $comment_sql = "SELECT com_id FROM OneDay_comment WHERE str_id = $strId and comment_at = $target";
            $com_id_data = \DB::query($comment_sql)->execute()->current();

            $com_id = $com_id_data['com_id'];

            //コメント追加・更新用のクエリを作成
            if (!empty($com_id)) {
                //update
                //$commentQuery = "UPDATE OneDay_comment SET comment = '$comment' WHERE str_id=$strId AND comment_at = $target";
                //\DB::query($commentQuery)->execute()->current();
                $query = Model_OnedayComment::find($com_id);
                $query->comment = $comment;
                $query->save();
            } else {
                //insert
                $query = Model_OnedayComment::forge()->set(array(
                    'str_id' => $strId,
                    'comment' => $comment,
                    'comment_at' => $target,
                    'val' => 1
                ));
                $query->save();
            }
        }
        $query = Model_OnedayComment::query();
        $commentSql = "SELECT comment FROM OneDay_comment WHERE str_id = $strId and comment_at = $target";
        $comment = \DB::query($commentSql)->execute()->current();
        $result = $comment['comment'];
        return $result;
    }

}
