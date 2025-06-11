<?php
/**
 * Created by PhpStorm.
 * User: Nuttaphon
 * Date: 8/4/2561
 * Time: 20:21
 */

function data_result_by_id_type($id_type){
    global $mysqli_connect;
    $data_result_by_id_type_sql = "SELECT * FROM `data` WHERE `id_type`='$id_type'";
    return $data_result_by_id_type_result = $mysqli_connect->query($data_result_by_id_type_sql);
}

function data_result_by_month_year($month,$year,$id_type){
    global $mysqli_connect;
    $data_result_by_month_year_sql = "SELECT * FROM `data` WHERE MONTH(date) = '$month' AND YEAR(date) = '$year' AND `id_type`='$id_type'";
    return $data_result_by_month_year_result = $mysqli_connect->query($data_result_by_month_year_sql);
}

function flot_pie_data(){
    $count_type1 = mysqli_num_rows(data_result_by_id_type('1'));
    $count_type2 = mysqli_num_rows(data_result_by_id_type('2'));
    $count_type3 = mysqli_num_rows(data_result_by_id_type('3'));
    $sum_count = $count_type1+$count_type2+$count_type3;
    $percent_type1 = round(($count_type1/$sum_count)*100);
    $percent_type2 = round(($count_type2/$sum_count)*100);
    $percent_type3 = round(($count_type3/$sum_count)*100);

    return '[{
                label: "Available",
                data: [
                    [1, '.$percent_type1.']
                ],
                color: \'#47a447\'
            }, {
                label: "Unavailable",
                data: [
                    [1, '.$percent_type2.']
                ],
                color: \'#d2322d\'
            }, {
                label: "Indeterminate",
                data: [
                    [1, '.$percent_type3.']
                ],
                color: \'#ed9c28\'
            }];';

}

function morris_bar_data($num){
    echo '[';
    for ($i=-($num-1); $i<1; $i++) {
        $month = date('m', strtotime("$i month"));
        $year = date('Y', strtotime("$i month"));
        $porsor = $year + 543;
        $count_data_type_1 =  mysqli_num_rows(data_result_by_month_year($month,$year,1));
        $count_data_type_2 =  mysqli_num_rows(data_result_by_month_year($month,$year,2));
        $count_data_type_3 =  mysqli_num_rows(data_result_by_month_year($month,$year,3));
        echo '{
                    y: \''.$month.'/'.$year.'\',
                    a: '.$count_data_type_1.',
                    b: '.$count_data_type_2.',
                    c: '.$count_data_type_3.'
                }';

        if($i==1){
            echo'';
        }
        else{
            echo ',';
        }
    }
    echo '];';
}