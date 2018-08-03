<?php
/**
 *
 * 作成日：2017/11/10
 * 更新日：2017/12/23
 * 作成者：戸田滉洋
 * 更新者：戸田滉洋
 *
 */
/**
 * The Top BasicInfo.
 *
 * 新規の企業データ作成画面
 * @package app
 * @extends Views
 */
?>
<?php $pref = Model_Prefecture::preflist(); ?>
<h2 style="text-align:center">新規店舗登録</h2>
<?php echo Form::open(array('name' => 'createEnterPrice', 'method' => 'post', 'class' => 'form-horizontal')); ?>
<?php echo '<div class="alert-error">' . Session::get_flash('error') . '</div>' ?>
<table class="table table-bordered table-striped">
    <tr>
        <th>
            <label class="control-label" for="ep_na">店舗名</label>
        </th>
        <td>
            <div class="col-xs-3">
                <?php echo Form::Input('ep_na', null, array('type' => 'text', 'size' => 40)); ?>
            </div>
        </td>
    </tr>
    <tr>
        <th>
            <label class="control-label" for="ep_pref_id">都道府県</label>
        </th>
        <td>
            <?php echo Form::select('ep_pref_id', "選択してください", array("選択してください", $pref)); ?> 
        </td>
    </tr>
    <tr>
        <th>
            <label class="control-label" for="ep_pos_code">郵便番号</label>
        </th>
        <td>
            <?php echo Form::input('ep_pos_code', null, array('type' => 'text', 'size' => 40)); ?>
        </td>
    </tr>
    <tr>
        <th>
            <label class="control-label" for="ep_street_addres">住所</label>
        </th>
        <td>
            <?php echo Form::input('ep_street_addres', null, array('type' => 'text', 'size' => 40)); ?>
        </td>
    </tr>
    <tr>
        <th>
            <label class="control-label" for="ep_phone_num">電話番号</label>
        </th>
        <td>
            <?php echo Form::input('ep_phone_num', null, array('type' => 'text', 'size' => 40)); ?>
        </td>
    </tr>
    <tr>
        <th>
            <label class="control-label" for="ep_fax_num">FAX</label>
        </th>
        <td>
            <?php echo Form::input('ep_fax_num', null, array('type' => 'text', 'size' => 40)); ?>
        </td>
    </tr>
    <tr>
        <th>
            <label class="control-label" for="ep_email_addres">メールアドレス</label>
        </th>
        <td>
            <?php echo Form::input('ep_email_addres', null, array('type' => 'text', 'size' => 40)); ?>
        </td>
    </tr>
</table>
<div class="form-actions">
    <?php echo Form::submit('submit', '新規登録', array('class' => 'btn btn-primary span3')); ?>
</div>
<?php echo Form::close(); ?>
