<?php
/**
 *
 * 作成日：2019/06/17
 * 更新日：2019/06/17
 * 作成者：戸田滉洋
 * 更新者：戸田滉洋
 * 
 */

/**
 * Model_Api_Weather
 *
 * 天気予報に関するAPIライブラリ
 * 
 */
class Model_Api_Weather {

    //エンドポイント
    protected $url_darksky = "https://api.darksky.net/forecast/a10e7c1ad14f74f27a7279006bf326a9/";

    //appid
    protected $appid_darksky ='a10e7c1ad14f74f27a7279006bf326a9';

    //緯度
    protected $lat_darksky = null;

    //経度
    protected $lng_darksky = null;
    
    //指定日付
    protected $time_darksky = null;
    
    //取得オプション
    protected $option_darksky = "?units=si&exclude=currently&lang=ja";

    /**
     * 指定日付の１時間毎の気象情報を取得
     * darksky使用
     */
    public function getWeather($lat=null,$lng=null,$time=null){
        
        if(is_null($lat) || is_null($lng) || is_null($time)){
            return false;
        }
        
        //api実行準備
        $url = $this->url_darksky.$lat.','.$lng.','.$time.$this->option_darksky;

    	//curlの処理を始める合図(darkskyapi)
    	$curl = curl_init($url);
    	//リクエストのオプションをセットしていく
    	curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'GET'); // メソッド指定
    	curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false); // 証明書の検証を行わない
    	curl_setopt($curl, CURLOPT_RETURNTRANSFER, true); // レスポンスを文字列で受け取る
        //レスポンスを変数に入れる
        $response = curl_exec($curl);
    	//curlの処理を終了
        curl_close($curl);
        
        return $response;
    }
}
