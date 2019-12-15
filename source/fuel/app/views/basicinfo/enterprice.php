<?php
/**
 *
 * 作成日：2017/07/15
 * 更新日：2017/12/30
 * 作成者：戸田滉洋
 * 更新者：戸田滉洋
 *
 */
/**
 * The Top BasicInfo.
 *
 * @package app
 * @extends Views
 */
?>
<h3 style="text-align:left">各種設定</h3>
<?php $pref = Model_Prefecture::preflist(); ?>

<ul class="nav nav-tabs">
    <li class="nav-item"><a href="basic">店舗情報</a></li>
    <li class="nav-item"><a href="alarmValue">警報値・目標値設置</a></li>
    <li class="nav-item"><a href="PastPerformance">導入前実績</a></li>
    <li class="nav-item"><a href="EnterPrice">企業情報</a></li>
</ul>
<h2 style="text-align:left">企業情報</h2>
<div class="row">
    <?php echo Form::open(array('name' => 'basicInfo', 'method' => 'post', 'class' => 'form-horizontal')); ?>
    <?php echo '<div class="alert-error">' . Session::get_flash('error') . '</div>' ?>
    <?php echo '<div class="alert-success">' . Session::get_flash('success') . '</div>' ?>
    <table class="table table-bordered table-striped">
        <tr>
            <th>
                <label class="control-label" for="ep_na">企業名</label>
            </th>
            <td>
                <?php Session::set('ep_id', $data->ep_id); ?>
                <?php echo Form::Input('ep_na', $data->ep_na, array('type' => 'text', 'size' => 40)); ?>
            </td>
        </tr>
        <tr>
            <th>
                <label class="control-label" for="ep_pref_id">都道府県</label>
            </th>
            <td>
                <?php echo Form::select('ep_pref_id', $data->ep_pref_id, $pref); ?>
            </td>
        </tr>
        <tr>
            <th>
                <label class="control-label" for="ep_pos_code">郵便番号</label>
            </th>
            <td>
                <?php echo Form::input('ep_pos_code', $data->ep_pos_code, array('type' => 'text', 'size' => 40)); ?>
            </td>
        </tr>
        <tr>
            <th>
                <label class="control-label" for="ep_street_addres">住所</label>
            </th>
            <td>
                <?php echo Form::input('ep_street_addres', $data->ep_street_addres, array('type' => 'text', 'size' => 40)); ?>
            </td>
        </tr>
        <tr>
            <th>
                <label class="control-label" for="ep_phone_num">電話番号</label>
            </th>
            <td>
                <?php echo Form::input('ep_phone_num', $data->ep_phone_num, array('type' => 'text', 'size' => 40)); ?>
            </td>
        </tr>
        <tr>
            <th>
                <label class="control-label" for="ep_email">Eメールアドレス</label>
            </th>
            <td>
                <?php echo Form::input('ep_email', $data->ep_email, array('type' => 'text', 'size' => 40)); ?>
            </td>
        </tr>
        <tr>
            <th>
                <label class="control-label" for="created_at">作成日</label>
            </th>
            <td>
                <?php echo date('Y/m/d H:i:s', $data->created_at); ?>

            </td>
        </tr>
        <tr>
            <th>
                <label class="control-label" for="updated_at">更新日</label>
            </th>
            <td>
                <?php echo date('Y/m/d H:i:s', $data->updated_at); ?>

            </td>
        </tr>
    </table>
    <div class="form-actions">
        <?php echo Form::submit('submit', '設定', array('class' => 'btn btn-primary span3')); ?>
        <?php echo Form::close(); ?>
    </div>
</div>
