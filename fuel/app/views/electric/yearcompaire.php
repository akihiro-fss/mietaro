<?php
/**
 *
 * 作成日：2018/8/19
 * 更新日：2018/9/23
 * 作成者：戸田滉洋
 * 更新者：戸田滉洋
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
            基準年<br/>
            <input type="date" name="param_date_1" value="param_date_1" id="form_param_date_1" style="width:150px; height:30px">
        </th>
        </th>
        <th style="text-align:center;" colspan="3">
            比較年<br/>
            <input type="date" name="param_date_2" value="param_date_2" id="form_param_date_2" style="width:150px; height:30px">
        </th>
        <th style="text-align:center;" rowspan="2">
            比較
        </th>
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
<script>
    var yearcompaire = <?php echo json_encode($yearcompaire); ?>

    $('#form_param_date_1').val(yearcompaire.param_date_1);
    $('#form_param_date_2').val(yearcompaire.param_date_2);

//電力量テーブル作成
    var oneyearElectric = yearcompaire.oneyear_electric;
    var twoyearElectric = yearcompaire.twoyear_electric;
    var oneyearTotal = yearcompaire.oneyear_total;
    var twoyearTotal = yearcompaire.twoyear_total;
    var emission1 = yearcompaire.total_emission_1;
    var emission2 = yearcompaire.total_emission_2;
    var emission_factor = yearcompaire.emission_factor;
    var conversion_factor = yearcompaire.conversion_factor;
    var oneyear_emission  = yearcompaire.oneyear_emission;
    var twoyear_emission  = yearcompaire.twoyear_emission;
    var electric_raito = yearcompaire.electric_raito;
    var demand_raito = yearcompaire.demand_raito;
    var electric_R = yearcompaire.electric_R;
    var demand_R = yearcompaire.demand_R;
    var emission_R = yearcompaire.emission_R;
    var oneyear_electric_total = yearcompaire.oneyear_electric_total;
    var twoyear_electric_total = yearcompaire.twoyear_electric_total;
    var max_oneyear_demand = yearcompaire.max_oneyear_demand;
    var max_twoyear_demand = yearcompaire.max_twoyear_demand;
    var emission_oneyear_total = yearcompaire.emission_oneyear_total;
    var emission_twoyear_total = yearcompaire.emission_twoyear_total;
    var total_R = yearcompaire.total_R;
    var total_ER = yearcompaire.total_ER;
    var total_raito = yearcompaire.total_raito;
    var diffTotal = 0;

    // 検証用の比較表作成
    $.each(oneyearElectric, function (key, value) {
       
        $('#electric-data-table').append('<tr><td style="width:50px;">' + key + '</td><td>' + value[0] + '</td><td>' + value[1] 
                                        + '<br/>' + value[2] + '</td><td>' + oneyear_emission[key] + '</td><td>' 
                                        + twoyearElectric[key][0] + '</td><td>' + twoyearElectric[key][1] + '<br/>' + twoyearElectric[key][2] + '</td><td>'
                                        + twoyear_emission[key] + '</td><td>' + electric_R[key] + '</td><td>' + emission_R[key] + '</td><td>' 
                                        +  electric_raito[key] +'</td><td>' + demand_R[key] + '</td><td>' + demand_raito[key] + '</td></tr>');
    });

    // 検証用の比較の合計表
    $('#electric-data-table').append('<tr><td style="width:50px;">  合計  </td><td>' + oneyear_electric_total + '</td><td>' + max_oneyear_demand[0] + '<br/>' 
                                    + max_oneyear_demand[1] + '</td><td>' + emission_oneyear_total + '</td><td>' + twoyear_electric_total + '</td><td>'
                                    + max_twoyear_demand[0] + '<br/>' + max_twoyear_demand[1] + '</td><td>' + emission_twoyear_total + '</td><td>'
                                    + total_R + '</td><td>' + total_ER + '</td><td>' + total_raito + '</td><td>' + '' + '</td><td>' + '' + '</td></tr>');
</script>