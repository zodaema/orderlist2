<?php
/**
 * Created by PhpStorm.
 * User: Nuttaphon
 * Date: 15/4/2561
 * Time: 1:03
 */

require_once '../config.php';

function data_result_by_id_type($id_type,$faculty){
    global $mysqli_connect;
    $data_sql = "SELECT * FROM `data` WHERE `id_type`='$id_type'";
    if($faculty != 'all'){
        $data_sql.="AND `faculty`='$faculty'";
    }
    return $data_result = $mysqli_connect->query($data_sql);
}

function data_email_by_id_user($id_user){
    global $mysqli_connect;
    $data_sql = "SELECT * FROM `data_email` WHERE `id_user`='$id_user'";
    return $data_result = $mysqli_connect->query($data_sql);
}

function data_result(){
    global $mysqli_connect;
    $data_sql = "SELECT * FROM `data`";
    return $data_result = $mysqli_connect->query($data_sql);
}

function email_result_limit(){
    global $mysqli_connect;
    $data_sql = "SELECT * FROM `data_email` LIMIT 1";
    return $data_result = $mysqli_connect->query($data_sql);
}

function fwrite_csv($id_type,$faculty){
    global $objWrite;
    $data_result_by_id_type = data_result_by_id_type($id_type,$faculty);
    while($data_result_by_id_type_row = $data_result_by_id_type->fetch_assoc()){
        $insert_data = array();
        foreach ($_POST['select-export'] as $value){
            if($value != ''){
                if($value == 'email'){
                    $data_email_by_id_user_result = data_email_by_id_user($data_result_by_id_type_row['id_user']);
                    $data_email = '';
                    while($data_email_by_id_user_row = $data_email_by_id_user_result ->fetch_assoc()){
                        $data_email.=$data_email_by_id_user_row['email'].' ';
                    }
                    $insert_data[$value] = $data_email;
                }
                else{
                    $insert_data[$value] = $data_result_by_id_type_row[$value];
                }
            }
        }
        $output_data['write'] = '"' . implode('","', $insert_data) . '"';
        fwrite($objWrite, $output_data['write']." \n");
    }
}

if(isset($_POST['action'])){
    if(!isset($_SESSION)){ session_start(); }
    require_once '../config.php';
    require_once '../source/member.php';
    require_once '../source/permission.php';
    require_once '../source/secure/InputFilter.php';

    $member_row = member_row();
    if($_POST['action'] == 'example'){
        $number = count($_POST['select-export']);
        echo 'เลข '.$number;
        $tr_num = 0;
        $data_result = data_result();
        while($data_row = $data_result->fetch_assoc()){
            echo '<tr>';
            for($i=1;$i<=$number;$i++){
                if($_POST['select-export'][$i] != ''){
                    if($_POST['select-export'][$i] == 'email'){
                        $email_result = data_email_by_id_user($data_row['id_user']);
                        while ($email_result_row = $email_result->fetch_assoc()) {
                            echo '<td>' . $email_result_row['email'] . '</td>';
                        }
                    }
                    else {
                        echo '<td>' . $data_row[$_POST['select-export'][$i]] . '</td>';
                    }
                }
                else{
                    echo '<td></td>';
                }
            }
            echo '</tr>';
            if (++$tr_num == 5) break;
        }

    }

    if($_POST['action'] == 'export_csv_data'){
        if(isset($_POST['select-export'])){
            $faculty = 'all';
            if(isset($_POST['faculty'])){
                $faculty = $_POST['faculty'];
            }
            $fileName = 'csv_'.date('d-m-Y-H-i-s').'.csv';
            $targetFile = '../export-file/';
            $realTargetFile = $domain.'/export-file/';
            $objWrite = fopen($targetFile.$fileName, 'w');
            fputs($objWrite,(chr(0xEF).chr(0xBB).chr(0xBF)));

            if(isset($_POST['available']) && $_POST['available'] == 'on'){
                fwrite_csv(1,$faculty);
            }
            if(isset($_POST['unavailable']) && $_POST['unavailable'] == 'on'){
                fwrite_csv(2,$faculty);
            }
            if(isset($_POST['indeterminate']) && $_POST['indeterminate'] == 'on'){
                fwrite_csv(3,$faculty);
            }

            fclose($objWrite);
            $return_data = array('success',$realTargetFile.$fileName);
            echo json_encode($return_data);
        }
        else{
            $return_data = array('no select');
            echo json_encode($return_data);
        }
    }
}