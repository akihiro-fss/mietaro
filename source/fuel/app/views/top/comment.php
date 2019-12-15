<?php
/**
 *
 * 作成日：2018/01/01
 * 更新日：2018/01/01
 * 作成者：戸田滉洋
 * 更新者：戸田滉洋
 *
 */
/**
 * The Top comment.
 *
 * コメントのページネーション
 * @package app
 * @extends Views
 */
?>
<h3 style="text-align:left">履歴参照</h3>
<ul class="nav nav-tabs">  
    <li class="nav-item"><a href="show">NEWS</a></li>
    <li class="nav-item"><a href="comment">コメント</a></li>
</ul>
<h2 style="text-align:left">コメント</h2>
<table class="table table-bordered table-striped">
    <thead>
        <tr>
            <th class="sp_none">日付</th>
            <th class="sp_none">news</th>

        </tr>
    </thead>
    <tbody>
        <?php foreach ($ondaycomment as $row): ?>
            <tr>
                <td><?= date('Y/m/d H:i:s', $row->created_at); ?></td>
                <td><?php echo nl2br($row->comment); ?></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
<h4 style="text-align:center"><?php echo Pagination::create_links(); ?></h4>


