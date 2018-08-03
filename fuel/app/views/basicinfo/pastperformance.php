<?php
/**
 *
 * 作成日：2017/12/10
 * 更新日：2017/12/30
 * 作成者：戸田滉洋
 * 更新者：戸田滉洋
 *
 */
/**
 * The Top BasicInfo.
 *
 * 導入前実績作成画面及び表示画面
 * @package app
 * @extends Views
 */
?>
<h3 style="text-align:left">各種設定</h3>
<?php echo '<div class="alert-error">' . Session::get_flash('error') . '</div>' ?>
<ul class="nav nav-tabs">  
    <li class="nav-item"><a href="basic">店舗情報</a></li>
    <li class="nav-item"><a href="alarmValue">警報値・目標値設置</a></li>
    <li class="nav-item"><a href="PastPerformance">導入前実績</a></li>
    <li class="nav-item"><a href="Enterprice">企業情報</a></li>
</ul>
<h2 style="text-align:left">導入前実績値</h2>
<?php echo Form::open(array('name' => 'PastPefromance', 'method' => 'post', 'class' => 'form-horizontal')); ?>
<?php if (is_null($month)): ?>
    <div class="control-group">
        <label class="control-label" for="p_year">導入前実績の年</label>
        <div class="controls">
            <?php echo Form::input('p_year', null, array('type' => 'text', 'size' => 20)); ?>
        </div>
        <table class="table table-bordered table-striped">
            <tr>
                <th></th>
                <th>
                    使用電力
                </th>
                <th>
                    最大デマンド値
                </th>
            </tr>
            <tr>
                <th>
                    1月
                </th>
                <td>
                    <?php echo Form::input('january_kwh', null, array('type' => 'text', 'size' => 20)); ?>
                </td>
                <td>
                    <?php echo Form::input('january_kw', null, array('type' => 'text', 'size' => 20)); ?>
                </td>
            </tr>
            <tr>
                <th>
                    2月
                </th>
                <td>
                    <?php echo Form::input('february_kwh', null, array('type' => 'text', 'size' => 20)); ?>
                </td>
                <td>
                    <?php echo Form::input('february_kw', null, array('type' => 'text', 'size' => 20)); ?>
                </td>
            </tr>
            <tr>
                <th>
                    3月
                </th>
                <td>
                    <?php echo Form::input('march_kwh', null, array('type' => 'text', 'size' => 20)); ?>
                </td>
                <td>
                    <?php echo Form::input('march_kw', null, array('type' => 'text', 'size' => 20)); ?>
                </td>
            </tr>
            <tr>
                <th>
                    4月
                </th>
                <td>
                    <?php echo Form::input('april_kwh', null, array('type' => 'text', 'size' => 20)); ?>
                </td>
                <td>
                    <?php echo Form::input('april_kw', null, array('type' => 'text', 'size' => 20)); ?>
                </td>
            </tr>
            <tr>
                <th>
                    5月
                </th>
                <td>
                    <?php echo Form::input('may_kwh', null, array('type' => 'text', 'size' => 20)); ?>
                </td>
                <td>
                    <?php echo Form::input('may_kw', null, array('type' => 'text', 'size' => 20)); ?>
                </td>
            </tr>
            <tr>
                <th>
                    6月
                </th>
                <td>
                    <?php echo Form::input('june_kwh', null, array('type' => 'text', 'size' => 20)); ?>
                </td>
                <td>
                    <?php echo Form::input('june_kw', null, array('type' => 'text', 'size' => 20)); ?>
                </td>
            </tr>
            <tr>
                <th>
                    7月
                </th>
                <td>
                    <?php echo Form::input('july_kwh', null, array('type' => 'text', 'size' => 20)); ?>
                </td>
                <td>
                    <?php echo Form::input('july_kw', null, array('type' => 'text', 'size' => 20)); ?>
                </td>
            </tr>
            <tr>
                <th>
                    8月
                </th>
                <td>
                    <?php echo Form::input('august_kwh', null, array('type' => 'text', 'size' => 20)); ?>
                </td>
                <td>
                    <?php echo Form::input('august_kw', null, array('type' => 'text', 'size' => 20)); ?>
                </td>
            </tr>
            <tr>
                <th>
                    9月
                </th>
                <td>
                    <?php echo Form::input('september_kwh', null, array('type' => 'text', 'size' => 20)); ?>
                </td>
                <td>
                    <?php echo Form::input('september_kw', null, array('type' => 'text', 'size' => 20)); ?>
                </td>
            </tr>
            <tr>
                <th>
                    10月
                </th>
                <td>
                    <?php echo Form::input('october_kwh', null, array('type' => 'text', 'size' => 20)); ?>
                </td>
                <td>
                    <?php echo Form::input('october_kw', null, array('type' => 'text', 'size' => 20)); ?>
                </td>
            </tr>
            <tr>
                <th>
                    11月
                </th>
                <td>
                    <?php echo Form::input('november_kwh', null, array('type' => 'text', 'size' => 20)); ?>
                </td>
                <td>
                    <?php echo Form::input('november_kw', null, array('type' => 'text', 'size' => 20)); ?>
                </td>
            </tr>
            <tr>
                <th>
                    12月
                </th>
                <td>
                    <?php echo Form::input('december_kwh', null, array('type' => 'text', 'size' => 20)); ?>
                </td>
                <td>
                    <?php echo Form::input('december_kw', null, array('type' => 'text', 'size' => 20)); ?>
                </td>
            </tr>
        </table>
        <div class="control-group">
            <label class="control-label" for=""></label>
            <div class="controls">
                <?php echo Form::submit('submit', '設定', array('class' => 'btn btn-primary span4')); ?>
            </div>
        </div>
    <?php else: ?>
        <div class="control-group">
            <table class="table table-bordered table-striped">
                <tr>
                    <th>
                        <?php echo $month->p_year; ?>年
                    </th>
                    <th>
                        使用電力
                    </th>
                    <th>
                        最大デマンド値
                    </th>
                </tr>
                <tr>
                    <th>
                        1月
                    </th>
                    <td>
                        <?php echo $month->january_kwh; ?>
                    </td>
                    <td>
                        <?php echo $month->january_kw; ?>
                    </td>
                </tr>
                <tr>
                    <th>
                        2月
                    </th>
                    <td>
                        <?php echo $month->february_kwh; ?>
                    </td>
                    <td>
                        <?php echo $month->february_kw; ?>
                    </td>
                </tr>
                <tr>
                    <th>
                        3月
                    </th>
                    <td>
                        <?php echo $month->march_kwh; ?>
                    </td>
                    <td>
                        <?php echo $month->march_kw; ?>
                    </td>
                </tr>
                <tr>
                    <th>
                        4月
                    </th>
                    <td>
                        <?php echo $month->april_kwh; ?>
                    </td>
                    <td>
                        <?php echo $month->april_kw; ?>
                    </td>
                </tr>
                <tr>
                    <th>
                        5月
                    </th>
                    <td>
                        <?php echo $month->may_kwh; ?>
                    </td>
                    <td>
                        <?php echo $month->may_kw; ?>
                    </td>
                </tr>
                <tr>
                    <th>
                        6月
                    </th>
                    <td>
                        <?php echo $month->june_kwh; ?>
                    </td>
                    <td>
                        <?php echo $month->june_kw; ?>
                    </td>
                </tr>
                <tr>
                    <th>
                        7月
                    </th>
                    <td>
                        <?php echo $month->july_kwh; ?>
                    </td>
                    <td>
                        <?php echo $month->july_kw; ?>
                    </td>
                </tr>
                <tr>
                    <th>
                        8月
                    </th>
                    <td>
                        <?php echo $month->august_kwh; ?>
                    </td>
                    <td>
                        <?php echo $month->august_kw; ?>
                    </td>
                </tr>
                <tr>
                    <th>
                        9月
                    </th>
                    <td>
                        <?php echo $month->september_kwh; ?>
                    </td>
                    <td>
                        <?php echo $month->september_kw; ?>
                    </td>
                </tr>
                <tr>
                    <th>
                        10月
                    </th>
                    <td>
                        <?php echo $month->october_kwh; ?>
                    </td>
                    <td>
                        <?php echo $month->october_kw; ?>
                    </td>
                </tr>
                <tr>
                    <th>
                        11月
                    </th>
                    <td>
                        <?php echo $month->november_kwh; ?>
                    </td>
                    <td>
                        <?php echo $month->november_kw; ?>
                    </td>
                </tr>
                <tr>
                    <th>
                        12月
                    </th>
                    <td>
                        <?php echo $month->december_kwh; ?>
                    </td>
                    <td>
                        <?php echo $month->december_kw; ?>
                    </td>
                </tr>
            </table>
        </div>
    <?php endif; ?>
</div>
<?php echo Form::close(); ?>

