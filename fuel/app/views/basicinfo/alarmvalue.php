<?php
/**
 *
 * 作成日：2017/7/15
 * 更新日：2018/01/01
 * 作成者：戸田滉洋
 * 更新者：戸田滉洋
 *
 */
/**
 *
 * デマンド警報及び月間目標を表示する
 * The Top alarmValue.
 *
 * @package app
 * @extends Views
 */
?>
<h3 style="text-align:left">各種設定</h3>
<?php echo Form::open(array('name' => 'login', 'method' => 'post', 'class' => 'form-horizontal')); ?>
<ul class="nav nav-tabs">
    <li class="nav-item"><a href="basic">店舗情報</a></li>
    <li class="nav-item"><a href="alarmValue">警報値・目標値設置</a></li>
    <li class="nav-item"><a href="PastPerformance">導入前実績</a></li>
    <li class="nav-item"><a href="Enterprice">企業情報</a></li>
</ul>
<?php echo Form::close(); ?>
<h2 style="text-align:left">デマンド警報値</h2>
<?php echo Form::open(array('name' => 'alarmValue', 'method' => 'post', 'class' => 'form-horizontal', 'action' => 'BasicInfo/alarmValue/update')); ?>
<?php echo '<div class="alert-error">' . Session::get_flash('error') . '</div>' ?>
<?php echo '<div class="alert-success">' . Session::get_flash('success') . '</div>' ?>
<?php foreach ($demand['data'] as $row): ?>
    <div class="control-group">
        <label class="control-label" for="demand_alarm">デマンド警報値</label>
        <div class="controls">
            <?php echo Form::input('demand_alarm', $row->demand_alarm, array('type' => 'number', 'size' => 20)); ?>
            <?php echo Form::submit('submit', '設定', array('class' => 'btn btn-primary span1')); ?>
        </div>
    </div>
<?php endforeach; ?>
・コメント記載場所

<h2 style="text-align:left">月間目標値</h2>
<?php if (is_null($month) || is_null($month['month'])): ?>
    <div class="control-group">
        <table class="table table-bordered table-striped">
            <tr>
                <th>
                    1月
                </th>
                <td>
                    <?php echo Form::input('january', null, array('type' => 'number', 'size' => 20)); ?>
                </td>
                <th>
                    2月
                </th>
                <td>
                    <?php echo Form::input('february', null, array('type' => 'number', 'size' => 20)); ?>
                </td>
            </tr>
            <tr>
                <th>
                    3月
                </th>
                <td>
                    <?php echo Form::input('march', null, array('type' => 'number', 'size' => 20)); ?>
                </td>
                <th>
                    4月
                </th>
                <td>
                    <?php echo Form::input('april', null, array('type' => 'number', 'size' => 20)); ?>
                </td>
            </tr>
            <tr>
                <th>
                    5月
                </th>
                <td>
                    <?php echo Form::input('may', null, array('type' => 'number', 'size' => 20)); ?>
                </td>
                <th>
                    6月
                </th>
                <td>
                    <?php echo Form::input('june', null, array('type' => 'number', 'size' => 20)); ?>
                </td>
            </tr>
            <tr>
                <th>
                    7月
                </th>
                <td>
                    <?php echo Form::input('july', null, array('type' => 'number', 'size' => 20)); ?>
                </td>
                <th>
                    8月
                </th>
                <td>
                    <?php echo Form::input('august', null, array('type' => 'number', 'size' => 20)); ?>
                </td>
            </tr>
            <tr>
                <th>
                    9月
                </th>
                <td>
                    <?php echo Form::input('september', null, array('type' => 'number', 'size' => 20)); ?>
                </td>
                <th>
                    10月
                </th>
                <td>
                    <?php echo Form::input('october', null, array('type' => 'number', 'size' => 20)); ?>
                </td>
            </tr>
            <tr>
                <th>
                    11月
                </th>
                <td>
                    <?php echo Form::input('november', null, array('type' => 'number', 'size' => 20)); ?>
                </td>
                <th>
                    12月
                </th>
                <td>
                    <?php echo Form::input('december', null, array('type' => 'number', 'size' => 20)); ?>
                </td>
            </tr>
        </table>
    </div>
<?php else: ?>
    <?php foreach ($month as $row): ?>
        <div class="control-group">
            <table class="table-condensed table-nonebordered">
                <tr>
                    <th>
                        1月
                    </th>
                    <td>
                        <?php echo Form::input('january', $row->january, array('type' => 'number')); ?>
                    </td>
                    <th>
                        2月
                    </th>
                    <td>
                        <?php echo Form::input('february', $row->february, array('type' => 'number')); ?>
                    </td>
                </tr>
                <tr>
                    <th>
                        3月
                    </th>
                    <td>
                        <?php echo Form::input('march', $row->march, array('type' => 'number')); ?>
                    </td>
                    <th>
                        4月
                    </th>
                    <td>
                        <?php echo Form::input('april', $row->april, array('type' => 'number')); ?>
                    </td>
                </tr>
                <tr>
                    <th>
                        5月
                    </th>
                    <td>
                        <?php echo Form::input('may', $row->may, array('type' => 'number')); ?>
                    </td>
                    <th>
                        6月
                    </th>
                    <td>
                        <?php echo Form::input('june', $row->june, array('type' => 'number')); ?>
                    </td>
                </tr>
                <tr>
                    <th>
                        7月
                    </th>
                    <td>
                        <?php echo Form::input('july', $row->july, array('type' => 'number')); ?>
                    </td>
                    <th>
                        8月
                    </th>
                    <td>
                        <?php echo Form::input('august', $row->august, array('type' => 'number')); ?>
                    </td>
                </tr>
                <tr>
                    <th>
                        9月
                    </th>
                    <td>
                        <?php echo Form::input('september', $row->september, array('type' => 'number')); ?>
                    </td>
                    <th>
                        10月
                    </th>
                    <td>
                        <?php echo Form::input('october', $row->october, array('type' => 'number')); ?>
                    </td>
                </tr>
                <tr>
                    <th>
                        11月
                    </th>
                    <td>
                        <?php echo Form::input('november', $row->november, array('type' => 'number')); ?>
                    </td>
                    <th>
                        12月
                    </th>
                    <td>
                        <?php echo Form::input('december', $row->december, array('type' => 'number')); ?>
                    </td>
                </tr>
            </table>
        </div>
    <?php endforeach; ?>
<?php endif; ?>
<div class="control-group">
    <label class="control-label" for=""></label>
    <div class="controls">
        <?php echo Form::submit('submit', '設定', array('class' => 'btn btn-primary span2')); ?>
    </div>
</div>
<?php echo Form::close(); ?>
