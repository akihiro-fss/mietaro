<?php

/**
 *
 * 作成日：2017/07/17
 * 更新日：2018/01/01
 * 作成者：戸田滉洋
 * 更新者：戸田滉洋
 *
 */

/**
 * The Top Model.
 *
 * お知らせを表示するモデル
 * @package app
 * @extends Model
 *
 *
 */
class Model_Top extends \orm\Model {

    protected static $_table_name = 'News';
    protected static $_properties = array(
        'id',
        'news',
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

    /**
     *
     * topのお知らせに作成日の最新の10データを表示
     * @return data
     */
    public static function news() {
        $data = array();
        $query = Model_top::query();
        $data['data'] = $query
                ->order_by('created_at', 'desc')
                ->limit(10)
                ->get();
        return $data;
    }

    /**
     *
     * お知らせをDBに挿入
     *
     * @param type $news
     * @return $result
     */
    public static function createNews($news) {
        $query = Model_Top::forge();
        $query->news = $news;
        $query->val = 1;
        $result = $query->save();
        return $result;
    }

    /*
     * 指定の内容を現在日時で登録または更新する
     * 同日にすでに登録済みなら更新、でなければ新規登録
     *
     * @return integer
     */
    public static function updateNews($news){
        $result = 0;

        //最新１０件取得
        $dataArray = Model_top::query()
        ->order_by('created_at', 'desc')
        ->limit(10)
        ->get();

        $updateFlg = 0;
        $newsId = '';

        //現在の日付で登録されたデータを検索
        foreach($dataArray as $key=>$data){
            if(date('Y-m-d',$data->created_at) == date('Y-m-d')){
                //update対象データ有り
                $updateFlg = 1;
                $newsId = $data->id;
                break;
            }
        }
        if($updateFlg){
            //update処理
            $query = Model_Top::find($newsId);
            $query->news = $news;
            $result = $query->save();
        }else{
            //insert処理
            $result = self::createNews($news);
        }
        return $result;
    }

    /*
     * 指定された日付に登録されているnewsの内容を取得する
     *
     * @return String
     */
    public static function getNewsByCreatedAt($targetDate){
        $result = '';

        //最新１０件取得
        $dataArray = Model_top::query()
        ->order_by('created_at', 'desc')
        ->limit(10)
        ->get();

        $updateFlg = 0;
        $newsId = '';

        //現在の日付で登録されたデータを検索
        foreach($dataArray as $key=>$data){
            if(date('Y-m-d',$data->created_at) == $targetDate){
                $result = $data->news;
                break;
            }
        }
        return $result;
    }

    /**
     * 履歴参照で表示するコメントを
     * ページネーションで作成し表示
     *
     * @return data
     */
    public static function pagenation() {
        $lines = 10;
        //配列の初期化
        $data = array();
        //データ件数の取得
        $count = Model_Top::count();
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
        //$auth = Auth::instance();
        //$str_id = $auth->get_str_id();
        $query = Model_Top::query();
        $total_items = $query
                //->where('str_id', '=', [$str_id])
                ->count();
        // Paginationインスタンスを生成
        $pagination = Pagination::forge('top', [
                    'total_items' => $total_items,
                    'per_page' => 20,
                    'uri_segment' => 'page',
        ]);
        $data['pagination'] = $pagination;

        // 現在のページのユーザーのリストを取得
        $data['top'] = $query
                ->order_by('created_at', 'desc')
                ->limit(Pagination::get('per_page'))
                ->offset(Pagination::get('offset'))
                ->get();
        return $data;
    }

}
