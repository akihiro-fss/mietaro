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
 * The Top BasicInfo.
 *
 * sidebarに表示されている店舗のリンク先
 * 店舗データ表示画面
 * @package app
 * @extends Views
 */
?>

<?php $ep = Model_EnterPrice::eplist(); ?>
<?php $power_pref = Model_PowerPref::powerpreflist(); ?>
<?php $pref = Model_Prefecture::preflist(); ?>
<?php $strlist = Model_BasicInfo::strlist(); ?>
<h3 style="text-align:left">店舗情報</h3>
<div class="row">
    <?php foreach ($data as $row): ?>
        <table class="table table-bordered table-striped">
            <tr>
                <th>
                    <label class="control-label" for="ep_na">会社名</label>
                </th>
                <td>
                    <?php echo $ep[$row->ep_id]; ?>

                </td>
            </tr>
            <tr>
                <th>
                    <label class="control-label" for="str_na">店舗名</label>
                </th>
                <td>
                    <div class="col-xs-3">
                        <?php echo $row->str_na; ?>
                    </div>
                </td>
            </tr>
            <tr>
                <th>
                    <label class="control-label" for="pref_id">都道府県</label>
                </th>
                <td>
                    <?php echo $pref[$row->pref_id]; ?> 
                </td>
            </tr>
            <tr>
                <th>
                    <label class="control-label" for="str_pos_code">郵便番号</label>
                </th>
                <td>
                    <?php echo $row->str_pos_code; ?>
                </td>
            </tr>
            <tr>
                <th>
                    <label class="control-label" for="str_street_addres">住所</label>
                </th>
                <td>
                    <?php echo $row->str_street_addres; ?>
                </td>
            </tr>
            <tr>
                <th>
                    <label class="control-label" for="str_phone_num">電話番号</label>
                </th>
                <td>
                    <?php echo $row->str_phone_num; ?>
                </td>
            </tr>
            <tr>
                <th>
                    <label class="control-label" for="str_fax_num">FAX</label>
                </th>
                <td>
                    <?php echo $row->str_fax_num; ?>
                </td>
            </tr>
            <tr>
                <th>
                    <label class="control-label" for="str_info">店舗情報</label>
                </th>
                <td>
                    <?php echo nl2br($row->str_info); ?>
                </td>
            </tr>
            <tr>
                <th>
                    <label class="control-label" for="str_weather_region">気象庁地域区分</label>
                </th>
                <td>
                    <?php echo $row->str_weather_region; ?>
                </td>
            </tr>
            <tr>
                <th>
                    <label class="control-label" for="str_memo">メモ</label>
                </th>
                <td>
                    <?php echo nl2br($row->str_memo); ?>
                </td>
            </tr>
        </table>
    <?php endforeach; ?>
</div>