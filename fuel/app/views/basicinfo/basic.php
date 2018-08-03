<?php
/**
 *
 * 作成日：2017/07/16
 * 更新日：2017/12.30
 * 作成者：戸田滉洋
 * 更新者：戸田滉洋
 *
 */
/**
 * The Top BasicInfo.
 *
 * 店舗情報表示及び更新画面
 * @package app
 * @extends Views
 */
?>

<?php $ep = Model_EnterPrice::eplist(); ?>
<?php $power_pref = Model_PowerPref::powerpreflist(); ?>
<?php $pref = Model_Prefecture::preflist(); ?>
<?php $strlist = Model_BasicInfo::strlist(); ?>
<h3 style="text-align:left">各種設定</h3>
<ul class="nav nav-tabs">
    <li class="nav-item"><a href="basic">店舗情報</a></li>
    <li class="nav-item"><a href="alarmValue">警報値・目標値設置</a></li>
    <li class="nav-item"><a href="PastPerformance">導入前実績</a></li>
    <li class="nav-item"><a href="EnterPrice">企業情報</a></li>
</ul>
<h2 style="text-align:left">店舗情報</h2>
<div class="row">
    <?php echo Form::open(array('name' => 'basicInfo', 'method' => 'post', 'class' => 'form-horizontal')); ?>
    <?php echo '<div class="alert-error">' . Session::get_flash('error') . '</div>' ?>
    <?php echo '<div class="alert-success">' . Session::get_flash('success') . '</div>' ?>
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
                        <?php Session::set('str_id', $row->str_id); ?>
                        <?php echo Form::input('str_na', $row->str_na, array('type' => 'text', 'size' => 40)); ?>
                    </div>
                </td>
            </tr>
            <tr>
                <th>
                    <label class="control-label" for="pref_id">都道府県</label>
                </th>
                <td>
                    <?php echo Form::select('pref_id', $row->pref_id, $pref); ?>
                </td>
            </tr>
            <tr>
                <th>
                    <label class="control-label" for="str_pos_code">郵便番号</label>
                </th>
                <td>
                    <?php echo Form::input('str_pos_code', $row->str_pos_code, array('type' => 'text', 'size' => 40)); ?>
                </td>
            </tr>
            <tr>
                <th>
                    <label class="control-label" for="str_street_addres">住所</label>
                </th>
                <td>
                    <?php echo Form::input('str_street_addres', $row->str_street_addres, array('type' => 'text', 'size' => 40)); ?>
                </td>
            </tr>
            <tr>
                <th>
                    <label class="control-label" for="str_phone_num">電話番号</label>
                </th>
                <td>
                    <?php echo Form::input('str_phone_num', $row->str_phone_num, array('type' => 'text', 'size' => 40)); ?>
                </td>
            </tr>
            <tr>
                <th>
                    <label class="control-label" for="str_fax_num">FAX</label>
                </th>
                <td>
                    <?php echo Form::input('str_fax_num', $row->str_fax_num, array('type' => 'text', 'size' => 40)); ?>
                </td>
            </tr>
            <tr>
                <th>
                    <label class="control-label" for="str_info">店舗情報</label>
                </th>
                <td>
                    <?php echo Form::textarea('str_info', $row->str_info, array('type' => 'text', 'size' => 40)); ?>
                </td>
            </tr>
            <tr>
                <th>
                    <label class="control-label" for="latitude">緯度</label>
                </th>
                <td>
                    <?php echo Form::input('latitude', $row->latitude, array('type' => 'text', 'size' => 40)); ?>
                </td>
            </tr>
            <tr>
                <th>
                    <label class="control-label" for="longitude">経度</label>
                </th>
                <td>
                    <?php echo Form::input('longitude', $row->longitude, array('type' => 'text', 'size' => 40)); ?>
                </td>
            </tr>
            <tr>
                <th>
                    <label class="control-label" for="str_email_addres">緊急メールアドレス</label>
                </th>
                <td>
                    <?php echo Form::input('str_email_addres', $row->str_email_addres, array('type' => 'text', 'size' => 40)); ?>
                </td>
            </tr>
            <tr>
                <th>
                    <label class="control-label" for="str_weather_region">気象庁地域区分</label>
                </th>
                <td>
                    <?php echo Form::input('str_weather_region', $row->str_weather_region, array('type' => 'text', 'size' => 40)); ?>
                </td>
            </tr>
            <tr>
                <th>
                    <label class="control-label" for="str_memo">メモ</label>
                </th>
                <td>
                    <?php echo Form::textarea('str_memo', $row->str_memo, array('type' => 'text', 'size' => 40)); ?>
                </td>
            </tr>
            <tr>
                <th>
                    <label class="control-label" for="str_ct">CT比</label>
                </th>
                <td>
                    <?php echo "1次側: " . $row->str_ct_1; ?>
                    </br>
                    <?php echo "2次側: " . $row->str_ct_2; ?>
                    <?php $str_ct = $row->str_ct_1 / $row->str_ct_2; ?>
                </td>
            </tr>
            <tr>
                <th>
                    <label class="control-label" for="str_vt">VT比</label>
                </th>
                <td>
                    <?php echo "1次側: " . $row->str_vt_1; ?>
                    </br>
                    <?php echo "2次側: " . $row->str_vt_2; ?>
                    <?php $str_vt = $row->str_vt_1 / $row->str_vt_2; ?>
                </td>
            </tr>
            <tr>
                <th>
                    <label class="control-label" for="conv_plus_plus">plus(変換パルスレート)</label>
                </th>
                <td>
                    <?php $conv_plus_plus = $str_ct * $str_vt; ?>
                    <?php echo 50000; ?>
                    <?php $conv_plus_kwh = $conv_plus_plus / 50000; ?>
                </td>
            </tr>
            <tr>
                <th>
                    <label class="control-label" for="conv_plus_kwh">kwh(変換パルスレート)</label>
                </th>
                <td>
                    <?php echo $conv_plus_kwh; ?>
                </td>
            </tr>
            <tr>
                <th>
                    <label class="control-label" for="power_com_na">電力会社</label>
                </th>
                <td>
                    <?php echo Form::select('power_com_id', $row->power_com_id, $power_pref); ?>
                </td>
            </tr>
            <tr>
                <th>
                    <label class="control-label" for="created_at">作成日</label>
                </th>
                <td>
                    <?php echo date('Y/m/d H:i:s', $row->created_at); ?>

                </td>
            </tr>
            <tr>
                <th>
                    <label class="control-label" for="updated_at">更新日</label>
                </th>
                <td>
                    <?php echo date('Y/m/d H:i:s', $row->updated_at); ?>

                </td>
            </tr>
        </table>
    <?php endforeach; ?>
    <div class="form-actions">
        <?php echo Form::submit('submit', '設定', array('class' => 'btn btn-primary span3')); ?>
        <?php echo Form::close(); ?>
    </div>

</div>
