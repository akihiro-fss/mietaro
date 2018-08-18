<?php
/**
 *
 * 作成日：2018/8/8
 * 更新日：
 * 作成者：戸田滉洋
 * 更新者：
 *
 */
/**
 * The Top Electric.
 *
 * 検証の詳細表示
 * @package app
 * @extends Views
 */
?>

<?php echo Form::open(array('name' => 'year_compaire', 'method' => 'post', 'class' => 'form-horizontal')); ?>
    <table id="electric-data-table" class="table table-bordered">
        <tr>
            <th style="text-align:center;" rowspan="4">
              検証月
            </th>
            <th style="text-align:center;" colspan="3">
                基準年</br>
                <?php echo Form::input('onedaydate', 'onedaydate', array('type' => 'date')); ?>
            </th>
            <th style="text-align:center;" colspan="3">
                比較年</br>
                <?php echo Form::input('twodaydate', 'twodaydate', array('type' => 'date')); ?><input id="dummy_button" type="submit" class="btn btn-ptimary">
                <script>
                    $("#dummy_button").css("visibility", "hidden");
                </script>
            </th>
            <th style="text-align:center;" rowspan="2">
              比較1
            </th>
        </tr>
        <tr>
          <th style="text-align:center;" colspan ="6">
            <?php echo Form::submit('submit', '決定', array('class' => 'btn btn-primary  span3')); ?>
          </th>
        </tr>
        <tr>
          <th rowspan="2">使用電力量</th>
          <th rowspan="2">最大デマンド値(kw)</th>
          <th rowspan="2">CO2排出量(kg-CO2)</th>
          <th rowspan="2">使用電力量</th>
          <th rowspan="2">最大デマンド値(kw)</th>
          <th rowspan="2">CO2排出量(kg-CO2)</th>
          <th colspan="3">使用電力量</th>
          <th colspan="2">最大デマンド値</th>
        </tr>
        <tr>
          <th>削減量(kWh)</th>
          <th>CO2削減量(kg-CO2)</th>
          <th>比率(%)</th>
      　　<th>削減量(kW)</th>
          <th>比率(%)</th>
        </tr>
    </table>
<?php echo Form::close(); ?>
