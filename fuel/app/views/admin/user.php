<?php
/**
 *
 * 作成日：2017/07/16
 * 最終更新日：2017/12/12
 * 作成者：戸田滉洋
 * 最終更新者：戸田滉洋
 *
 */
/**
 * The Top admin.
 *
 * @package app
 * @extends Views
 */
?>
<?php $group = Model_Admin::config_groups(); ?>
<?php $str_na = Model_BasicInfo::strlist(); ?>
<?php $ep_na = Model_EnterPrice::eplist(); ?>
<h3 style="text-align:left">ユーザ情報</h3>
<?php echo '<div class="alert-error">' . Session::get_flash('error') . '</div>' ?>
<?php echo '<div class="alert-success">' . Session::get_flash('success') . '</div>' ?>
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>項目</th>
                    <th>内容</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <th>ユーザ名</th>
                    <td><?php echo $data->username; ?></td>
                </tr>
                <tr>
                    <th>Eメール</th>
                    <td><?php echo $data->email; ?></td>
                </tr>
                <tr>
                    <th>企業名</th>
                    <td><?php echo $ep_na[$data->ep_id]; ?></td>
                </tr>
                <tr>
                    <th>店舗名</th>
                    <td><?php echo $str_na[$data->str_id]; ?></td>
                </tr>
                <tr>
                    <th>権限情報</th>
                    <td><?php echo $group[$data->group]; ?></td>
                </tr>
                <tr>
                    <th>最終ログイン</th>
                    <td><?php echo date('Y/m/d H:i:s', $data->last_login); ?></td>
                </tr>
                <tr>
                    <th>作成日</th>
                    <td><?php echo date('Y/m/d H:i:s', $data->created_at); ?></td>
                </tr>
                <tr>
                    <th>更新日</th>
                    <td><?php echo $data->updated_at > 0 ? date('Y/m/d H:i:s', $data->updated_at) : ''; ?></td>
                </tr>
            </tbody>
        </table>
        <div class="form-actions">
            <?php echo Form::open(array('name' => 'user', 'method' => 'post', 'class' => 'form-horizontal')); ?>
            <?php echo Form::submit('submit', '編集', array('class' => 'btn btn-primary span3')); ?>
            <?php echo Form::close(); ?>
        </div>
