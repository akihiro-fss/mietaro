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
 * The Top admin.
 *
 * ユーザ一覧からのユーザ情報表示
 * @package app
 * @extends Views
 */
?>

<?php $id = $_GET['users']; ?>
<?php $row = Model_User::usersdata($id); ?>
<?php $group = Model_Admin::config_groups(); ?>
<?php $str_na = Model_BasicInfo::strlist(); ?>
<?php $ep_na = Model_EnterPrice::eplist(); ?>
<h3><?php echo Session::get_flash('success', 'ようこそ' . Auth::get_screen_name() . 'さん');
?></h3>
<h3 style="text-align:left">ユーザ情報</h3>
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
            <?php Session::set('users_id', $row['data']->id); ?>
            <td><?php echo $row['data']->username; ?></td>
        </tr>
        <tr>
            <th>Eメール</th>
            <td><?php echo $row['data']->email; ?></td>
        </tr>
        <tr>
            <th>所属企業</th>
            <?php if (is_null($row['data']->ep_id)): ?>
                <td>企業情報が設定されていおりません</td>
            <?php else: ?>
                <td><?php echo $ep_na[$row['data']->ep_id] ?></td>
            <?php endif; ?>
        </tr>
        <tr>
            <th>所属店舗</th>
            <?php if (is_null($row['data']->str_id)): ?>
                <td>店舗情報が設定されていおりません</td>
            <?php else: ?>
                <td><?php echo $str_na[$row['data']->str_id] ?></td>
            <?php endif; ?>
        </tr>
        <tr>
            <th>所属グループ</th>
            <td><?php echo $group[$row['data']->group]; ?></td>
        </tr>
        <tr>
            <th>最終ログイン</th>
            <td><?php echo date('Y/m/d H:i:s', $row['data']->last_login); ?></td>
        </tr>
        <tr>
            <th>作成日</th>
            <td><?php echo date('Y/m/d H:i:s', $row['data']->created_at); ?></td>
        </tr>
        <tr>
            <th>更新日</th>
            <td><?php echo $row['data']->updated_at > 0 ? date('Y/m/d H:i:s', $row['data']->updated_at) : ''; ?></td>
        </tr>

    </tbody>
</table>
<div class="form-actions">
    <?php echo Form::open(array('name' => 'usersEdit', 'method' => 'post', 'class' => 'form-horizontal')); ?>
    <?php echo Form::submit('submit', '編集', array('class' => 'btn btn-primary span3')); ?>
    <?php echo Form::close(); ?>
</div>