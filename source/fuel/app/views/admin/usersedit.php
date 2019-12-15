<?php
/**
 *
 * 作成日：2017/07/11
 * 更新日：2017/12/30
 * 作成者：戸田滉洋
 * 更新者：丸山　隼
 *
 */
/**
 * The Top admin.
 * 
 * ユーザ一覧からのユーザ編集画面
 * @package app
 * @extends Views
 */
?>
<?php $group = Model_Admin::config_groups(); ?>
<?php $str_na = Model_BasicInfo::strlist(); ?>
<?php $str_name = Model_BasicInfo::strlist(); ?>
<?php $ep_na = Model_EnterPrice::eplist(); ?>
<?php echo Form::open(array('name' => 'usersEdit', 'method' => 'post', 'class' => 'form-horizontal')); ?>
<h3><?php echo Session::get_flash('success', 'ようこそ' . Auth::get_screen_name() . 'さん'); ?></h3>
<?php echo '<div class="alert-error">' . Session::get_flash('error') . '</div>' ?>
<table class="table table-bordered table-striped">
    <thead>
        <tr>
            <th>項目</th>
            <th>内容</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <th>
                <label class="control-label" for="uername">ユーザ名</label>
            </th>
            <td><?php echo $data->username; ?></td>
        </tr>
        <tr>
            <th>
                <label class="control-label" for="email">Eメール</label>
            </th>
            <td><?php echo Form::Input('email', $data->email, array('type' => 'text', 'size' => 40)); ?></td>
        </tr>
        <tr>
            <th>
                <label class="control-label" for="ep_id">企業名</label>
            </th>
            <td id="ep_id"><?php echo $ep_na[$data->ep_id]; ?></td>
        </tr>
        <?php if (Auth::member(100)): ?>
            <tr>
                <th>
                    <label class="control-label" for="str_id">店舗名</label>
                </th>
                <td>
                    <select id="str_select_id" name="str_id"></select>
                    <?php //echo Form::select('str_Id', $data->str_id, ); ?>
                </td>
            </tr>
        <?php elseif (Auth::member(50)): ?>
            <tr>
                <th>
                    <label class="control-label" for="str_id">店舗名</label>
                </th>
                <td><?php echo Form::select('str_Id', $data->str_id, $str_name); ?></td>
            </tr>
        <?php elseif (Auth::member(1)): ?>
            <tr>
                <th>
                    <label class="control-label" for="str_id">店舗名</label>
                </th>
                <td><?php echo $str_na[$data->str_id]; ?></td>
            </tr>
        <?php endif; ?>
        <tr>
            <th><label class="control-label" for="last_login">最終ログイン</label>
            </th>
            <td><?php echo date('Y/m/d H:i:s', $data->last_login); ?></td>
        </tr>

        <tr>
            <th>
                <label class="control-label" for="created_at">作成日</label>
            </th>
            <td><?php echo date('Y/m/d H:i:s', $data->created_at); ?></td>
        </tr>
        <tr>
            <th>
                <label class="control-label" for="updated_at">更新日</label>
            </th>
            <td><?php echo $data->updated_at > 0 ? date('Y/m/d H:i:s', $data->updated_at) : ''; ?></td>
        </tr>
    </tbody>
</table>
<div class="form-actions">
    <?php echo Form::submit('submit', '決定', array('class' => 'btn btn-primary span3')); ?>
    <?php echo Form::close(); ?>
</div>
<script>
    $(function () {
        var userId = <?= $user_id ?>

        $.ajax({
            type: 'POST',
            url: '/mietaro/public/admin/usersEdit/getUsersEditInfo',
            data: {
                "user_id": userId
            }
        }).fail(function () {
            // エラー処理
            console.log('ユーザ情報に紐づくデータの取得に失敗しました');
        }).done(function (res) {
            // 成功処理
            createSelectStoreList(res);
        });

        //セレクトボックスに店舗名をセットする
        function createSelectStoreList(storeList) {
            $('#str_select_id').children().remove();

            var list = $.parseJSON(storeList);
            $.each(list, function (key, item) {
                if (item.selected) {
                    $('#str_select_id').append($('<option>').text(item.str_na).val(item.str_id)).attr('selected', 'seleted');
                } else {
                    $('#str_select_id').append($('<option>').text(item.str_na).val(item.str_id));
                }
            });
        }
    });
</script>