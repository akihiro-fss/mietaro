<?php
/**
 *
 * 作成日：2017/07/16
 * 更新日：2018/01/01
 * 作成者：戸田滉洋
 * 更新者：戸田滉洋
 *
 */
/**
 * The Top admin.
 *
 * ユーザ一覧をページネーションで表示
 * @package app
 * @extends Views
 */
?>
<?php $ep_na = Model_EnterPrice::eplist(); ?>
<?php $group = Model_Admin::config_groups(); ?>
<?php $str_na = Model_BasicInfo::strlist(); ?>

<h2 style="text-align:left">ユーザ一覧</h2>
<?php echo '<div class="alert-success">' . Session::get_flash('success') . '</div>' ?>
<?php echo '<div class="alert-error">' . Session::get_flash('error') . '</div>' ?>
<table class="table table-bordered table-striped">
    <thead>
        <tr>
            <th>ユーザ名</th>
            <th class="sp_none">グループ</th>
            <th>Eメール</th>
            <?php if (Auth::member(100)): ?>
                <th>企業情報</th>
            <?php endif; ?>
            <th class="sp_none">店舗情報</th>
            <th class="sp_none">最終ログイン</th>
            <th class="sp_none">作成日</th>
            <th class="sp_none">更新日</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($users as $row): ?>
            <tr>
                <td><?php echo Html::anchor('admin/users/?users=' . $row->id, $row->username); ?></td>
                <td class="sp_none"><?php echo $group[$row->group]; ?></td>
                <td><?= $row->email ?></td>
                <?php if (Auth::member(100)): ?>
                    <?php if (is_null($row->ep_id)): ?>
                        <td>企業情報が設定されていおりません</td>
                    <?php else: ?>
                        <td><?php echo $ep_na[$row->ep_id] ?></td>
                    <?php endif; ?>
                <?php endif; ?>
                <td class="sp_none"><?= $str_na[$row->str_id]; ?></td>
                <td class="sp_none"><?= $row->last_login > 0 ? date('Y/m/d H:i:s', $row->last_login) : ""; ?></td>
                <td class="sp_none"><?= date('Y/m/d H:i:s', $row->created_at); ?></td>
                <td class="sp_none"><?= $row->updated_at > 0 ? date('Y/m/d H:i:s', $row->updated_at) : ""; ?></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
<h4 style="text-align:center"><?php echo Pagination::create_links(); ?></h4>
