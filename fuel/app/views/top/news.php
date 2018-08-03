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
 * The Top top.
 *
 * お知らせ及びコメント表示画面
 * @package app
 * @extends Views
 */
?>


<?php if (Auth::member(100)): ?>
<?php echo '<div class="alert-error">' . Session::get_flash('error') . '</div>' ?>
<?php echo '<div class="alert-success">' . Session::get_flash('success') . '</div>' ?>
    <h3 style="text-alin:center">お知らせ投稿</h3>
    <?php echo Form::open(array('name' => 'news', 'method' => 'post', 'class' => 'form-horizontal')); ?>
    <?php echo Form::textarea('news', null, array('type' => 'text', 'size' => 40)); ?>
    <?php echo Form::submit('submit', '投稿', array('class' => 'btn btn-primary span1')); ?>
    <?php echo Form::close(); ?>
<?php endif; ?>

<h3 style="text-alin:center">お知らせ</h3>
<table class="table table-bordered table-striped">
    <thead>
        <tr>
            <th class="sp_none">日付</th>
            <th class="sp_none">news</th>

        </tr>
    </thead>
    <tbody>
        <?php foreach ($news['data'] as $row): ?>
            <tr>
                <td><?= date('Y/m/d H:i:s', $row->created_at); ?></td>
                <td><?php echo nl2br($row->news); ?></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
<h3 style="text-alin:center">コメント</h3>
<table class="table table-bordered table-striped">
    <thead>
        <tr>
            <th class="sp_none">日付</th>
            <th class="sp_none">コメント</th>

        </tr>
    </thead>
    <tbody>
        <?php foreach ($comment['comment'] as $data): ?>
            <tr>
                <td><?= date('Y/m/d H:i:s', $data->created_at); ?></td>
                <td><?php echo nl2br($data->comment); ?></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<script>
/* onloadでコメントを取得 */
getTodayNews();

/* コメント取得 */
function getTodayNews() {
    $.ajax({
        type: 'POST',
        url: '/mietaro/public/top/news/getNews',
        data: {}
    }).fail(function () {
        // エラー処理
        console.log('newsの取得に失敗');
    }).done(function (res) {
        // 成功処理
        var res = $.parseJSON(res)
        $('#form_news').val("");
        $('#form_news').val(res);
    });
}

</script>

