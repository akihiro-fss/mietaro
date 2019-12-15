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
 * 新規ユーザ情報作成画面
 * @package app
 * @extends Views
 */
?>
<?php $str_na = Model_BasicInfo::strlist(); ?>
<?php $ep_na = Model_EnterPrice::eplist(); ?>
<h3><?php echo Session::get_flash('success', 'ようこそ' . Auth::get_screen_name() . 'さん');
?></h3>
<div class="row">
    <div class="span4 offset1">
        <h2 style="text-align:center">新規ユーザ登録</h2>
        <?php echo Form::open(array('name' => 'create', 'method' => 'post', 'class' => 'form-horizontal')); ?>
        <?php echo '<div class="alert-error">' . Session::get_flash('error') . '</div>' ?>

        <div class="control-group">
            <label class="control-label" for="username">ユーザー名</label>
            <div class="controls">
                <?php echo Form::input('username', Input::post('username')); ?>
            </div>
        </div>
        <div class="control-group">
            <label class="control-label" for="email">Eメール</label>
            <div class="controls">
                <?php echo Form::input('email', Input::post('email')); ?>
            </div>
        </div>
        <div class="control-group">
            <label class="control-label" for="password">パスワード</label>
            <div class="controls">
                <?php echo Form::password('password'); ?>
            </div>
        </div>
        <?php if (Auth::member(100)): ?>
            <div class="control-group">
                <label class="control-label" for="ep_id">所属企業</label>
                <div class="controls">
                    <?php echo Form::select('ep_id', null, array('選択してください', $ep_na)); ?>
                </div>
            </div>
        <?php endif; ?>
        <?php if (Auth::member(100) or Auth::member(50)): ?>
            <div class="control-group">
                <label class="control-label" for="str_id">所属店舗ID</label>
                <div class="controls">
                    <select id="str_select_id" name="str_id">
                        <option value="NULL">選択してください</option>
                    </select>
                    <?php //echo Form::select('str_id', null, array('選択してください', $str_na)); ?>
                </div>
            </div>
        <?php endif; ?>
        <?php if (Auth::member(100)): ?>
            <div class="control-group">
                <label class="control-label" for="group">所属グループ</label>
                <div class="controls">
                    <?php echo Form::select('group', null, array('group' => Model_Admin::config_groups())); ?>
                </div>
            </div>
        <?php endif; ?>
        <div class="form-actions">
            <?php echo Form::submit('submit', '新規登録', array('class' => 'btn btn-primary span3')); ?>
            <?php echo Form::close(); ?>
        </div>
    </div><!--/span4 offset2-->
</div><!--/row-->
<script>
$(function() {
	$('#form_ep_id').change(function(){
		//所属企業が選択されたら所属店舗IDを切り替える
		var ep_id = $('#form_ep_id').val();

		$.ajax({
		    type: 'POST',
		    url: '/mietaro/public/admin/create/getStoreList',
		    data: {
		        "ep_id": ep_id
		    }
		}).fail(function () {
		    // エラー処理
		    console.log('店舗名の取得に失敗しました');
		}).done(function (res) {
		    // 成功処理
		    createSelectStoreList(res);
		});

	});

	//セレクトボックスに店舗名をセットする
	function createSelectStoreList(storeList){
		$('#str_select_id').children().remove();
		$('#str_select_id').append('<option value="NULL">選択してください</option>');
		var list = $.parseJSON(storeList);
		$.each(list,function(key,item){
			$('#str_select_id').append($('<option>').text(item.str_na).val(item.str_id));
		});
	}
});
</script>