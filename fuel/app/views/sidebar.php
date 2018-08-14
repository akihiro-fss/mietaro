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
 * The Top Views.
 *
 * サイドバーの表示画面
 * @package app
 * @extends Views
 */
?>
<?php $side = Controller_Sidebar::data(); ?>
<h3 style="text-align: center"><?php echo Session::get_flash('success1', 'ようこそ' . Auth::get_screen_name() . 'さん'); ?></h3>
<h4 style="text-align: center"><?php echo date('Y/m/d'); ?></h4>
<br/>

<h5 style="text-align: center"><?php echo Html::anchor('top/news', 'NEWS'); ?></h5>
<h5 style="text-align: center"><?php echo Html::anchor('top/show', '履歴参照'); ?></h5>
<h5 style="text-align: center"><?php echo Html::anchor('basicinfo/storedata', '店舗'); ?></h5>
<h5 style="text-align: center"><?php echo Html::anchor('electric/yearcompaire', '検証'); ?></h5>
<h5 style="text-align: center"><?php echo Html::anchor('top/blankfile', '節電ツール'); ?></h5>
<table frame="box" style="text-align: center">
    <tr>
        <th style="text-align: center">
            月間目標値
        </th>
    </tr>
    <tr>
        <td style="text-align: center">
            <?php echo $side['month']; ?>
        </td>

    </tr>
    <tr>
        <th style="text-align: center">
            月間推測値
        </th>
    </tr>
    <tr>
        <td>
            表示できない
        </td>
    </tr>
    <tr>
        <th>
            前年同月最大デマンド値
        </th>
    </tr>
    <tr>
        <td>
            表示できない
        </td>
    </tr>
    <tr>
        <th>
            １日目標
        </th>
    </tr>
    <tr>
        <td>
            <?php echo $side['MToneday']; ?>
        </td>
    </tr>
    <tr>
        <th>
            今月の使用電力量
        </th>
    </tr>
    <tr>
        <td>
            <?php echo $side['electricMonth']; ?>
        </td>
    </tr>
    <tr>
        <th>
            今月のCO2排出量
        </th>
    </tr>
    <tr>
        <td>
            表示できない
        </td>
    </tr>
    <tr>
        <th>
            現在の契約電力
        </th>
    </tr>
    <tr>
        <td>
            表示できない
        </td>
    </tr>
    <tr>
        <th>
            現在のデマンド警報値
        </th>
    </tr>
    <tr>
        <td>
            <?php echo $side['demandKey']; ?>
        </td>
    </tr>
</table>
</br>
