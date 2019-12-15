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
 * The Top Views.
 *
 * 新規の店舗データ作成画面
 * @package app
 * @extends Views
 */
?>
<?php $ep_na = Model_EnterPrice::eplist(); ?> 
<?php $pref = Model_Prefecture::preflist(); ?>
<?php $power_pref = Model_PowerPref::powerpreflist(); ?>
<h2 style="text-align:center">新規店舗登録</h2>
<?php echo Form::open(array('name' => 'createstore', 'method' => 'post', 'class' => 'form-horizontal')); ?>
<?php echo '<div class="alert-error">' . Session::get_flash('error') . '</div>' ?>
<table class="table table-bordered table-striped">
    <?php if (Auth::member(100)): ?>
        <tr>
            <th>
                <label class="control-label" for="ep_na">企業名</label>
            </th>
            <td>
                <?php echo Form::select('ep_id', null, array("選択してください", $ep_na)); ?>
            </td>
        </tr>
    <?php else: ?>
        <tr>
            <th>
                <label class="control-label" for="ep_na">企業名</label>
            </th>
            <td>
                <?php $ep_id = Auth::get_ep_id(); ?>
                <?php echo $ep_na[$ep_id] ?>
            </td>
        </tr>
    <?php endif; ?>
    <tr>
        <th>
            <label class="control-label" for="str_na">店舗名</label>
        </th>
        <td>
            <div class="col-xs-3">
                <?php echo Form::Input('str_na', null, array('type' => 'text', 'size' => 40)); ?>
            </div>
        </td>
    </tr>
    <tr>
        <th>
            <label class="control-label" for="pref_id">都道府県</label>
        </th>
        <td>
            <?php echo Form::select('pref_id', "選択してください", array("選択してください", $pref)); ?> 
        </td>
    </tr>
    <tr>
        <th>
            <label class="control-label" for="str_pos_code">郵便番号</label>
        </th>
        <td>
            <?php echo Form::input('str_pos_code', null, array('type' => 'text', 'size' => 40)); ?>
        </td>
    </tr>
    <tr>
        <th>
            <label class="control-label" for="str_street_addres">住所</label>
        </th>
        <td>
            <?php echo Form::input('str_street_addres', null, array('type' => 'text', 'size' => 40)); ?>
        </td>
    </tr>
    <tr>
        <th>
            <label class="control-label" for="str_phone_num">電話番号</label>
        </th>
        <td>
            <?php echo Form::input('str_phone_num', null, array('type' => 'text', 'size' => 40)); ?>
        </td>
    </tr>
    <tr>
        <th>
            <label class="control-label" for="str_fax_num">FAX</label>
        </th>
        <td>
            <?php echo Form::input('str_fax_num', null, array('type' => 'text', 'size' => 40)); ?>
        </td>
    </tr>
    <tr>
        <th>
            <label class="control-label" for="str_info">店舗情報</label>
        </th>
        <td>
            <?php echo Form::textarea('str_info', null, array('type' => 'text', 'size' => 40)); ?>
        </td>
    </tr>
    <tr>
        <th>
            <label class="control-label" for="latitude">緯度</label>
        </th>
        <td>
            <?php echo Form::input('latitude', null, array('type' => 'text', 'size' => 40)); ?>
        </td>
    </tr>
    <tr>
        <th>
            <label class="control-label" for="longitude">経度</label>
        </th>
        <td>
            <?php echo Form::input('longitude', null, array('type' => 'text', 'size' => 40)); ?>
        </td>
    </tr>
    <tr>
        <th>
            <label class="control-label" for="str_email_addres">緊急メールアドレス</label>
        </th>
        <td>
            <?php echo Form::input('str_email_addres', null, array('type' => 'text', 'size' => 40)); ?>
        </td>
    </tr>
    <tr>
        <th>
            <label class="control-label" for="str_weather_region">気象庁地域区分</label>
        </th>
        <td>
            <?php echo Form::input('str_weather_region', null, array('type' => 'text', 'size' => 40)); ?>
        </td>
    </tr>
    <tr>
        <th>
            <label class="control-label" for="str_memo">メモ</label>
        </th>
        <td>
            <?php echo Form::textarea('str_memo', null, array('type' => 'text', 'size' => 40)); ?>
        </td>
    </tr>
    <tr>
        <th>
            <label class="control-label" for="str_ct_1">CT比一次側</label>
        </th>
        <td>
            <?php echo Form::input('str_ct_1', null, array('type' => 'text', 'size' => 40)); ?>
        </td>
    </tr>
    <tr>
        <th>
            <label class="control-label" for="str_ct_2">CT比二次側</label>
        </th>
        <td>
            <?php echo Form::input('str_ct_2', null, array('type' => 'text', 'size' => 40)); ?>
        </td>
    </tr>
    <tr>
        <th>
            <label class="control-label" for="str_vt_1">VT比一次側</label>
        </th>
        <td>
            <?php echo Form::input('str_vt_1', null, array('type' => 'text', 'size' => 40)); ?>
        </td>
    </tr>
    <tr>
        <th>
            <label class="control-label" for="str_vt_2">VT比二次側</label>
        </th>
        <td>
            <?php echo Form::input('str_vt_2', null, array('type' => 'text', 'size' => 40)); ?>
        </td>
    </tr>
    <tr>
        <th>
            <label class="control-label" for="power_com_na">電力会社</label>
        </th>
        <td>
            <?php echo Form::select('power_com_id', "選択してください", array("選択してください", $power_pref)); ?>
        </td>
    </tr>
    <tr>
        <th>
            <label class="control-label" for="contract_de">契約電力</label>
        </th>
        <td>
            <?php echo Form::input('contract_de', null, array('type' => 'text', 'size' => 40)); ?>
        </td>
    </tr>
    <tr>
        <th>
            <label class="control-label" for="demand_alarm">デマンド警報値</label>
        </th>
        <td>
            <?php echo Form::input('demand_alarm', null, array('type' => 'text', 'size' => 40)); ?>
        </td>
    </tr>
    <tr>
        <th>
            <label class="control-label" for="emission_factor">CO2排出係数</label>
        </th>
        <td>
            <?php echo Form::input('emission_factor', null, array('type' => 'text', 'size' => 40)); ?>
        </td>
    </tr>
    <tr>
        <th>
            <label class="control-label" for="conversion_factor">原油換算係数</label>
        </th>
        <td>
            <?php echo Form::input('conversion_factor', null, array('type' => 'text', 'size' => 40)); ?>
        </td>
    </tr>
</table>
<div class="form-actions" style="text-align:center">
    <?php echo Form::submit('submit', '新規登録', array('class' => 'btn btn-primary span3')); ?>
</div>
<?php echo Form::close(); ?>
