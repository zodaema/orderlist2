<?php

    require_once '../config.php';
    require_once 'emailfilter_action.php';

    $database_data_name = 'data';
    $database_data_email_name = 'data_email';

    function insert_data_csv($select,$csv){
        $insert_data = array();
        foreach ($select as $item) {
            $search_num = array_search($item, $select);
            if($item != ''){
                if($item != 'email1' && $item != 'email2'){
                    $insert_data[$item] = $csv[$search_num];
                }
            }
        }

//        $insert_data['date'] = '2018-04-18 09:29:45';

        $output_data['field'] = implode(",", array_keys($insert_data));
        $output_data['newdata'] = "'" . implode("','", $insert_data) . "'";

        return $output_data;
    }

    function update_data_csv($select,$csv){
        $insert_data = array();
        foreach ($select as $item) {
            $search_num = array_search($item, $select);
            if($item != ''){
                if($item != 'email1' && $item != 'email2'){
                    $insert_data[$item] = $csv[$search_num];
                }
            }
        }

//        $insert_data['date'] = '2018-04-18 09:29:45';

        array_walk($insert_data, function(&$value, $key){
            $value = "`{$key}` = '{$value}'";
        });

        $output_data['value'] = implode(", ", array_values($insert_data));
        return $output_data;
    }

    function insert_data_email($id_user,$csv,$num1,$num2,$filter){
        global $mysqli_connect,$database_data_email_name;
        $email = $csv[$num1][$num2];

        if($filter == 'on'){
            $data_email = filter_email($email);
            if($data_email['newemail'] != ''){
                $email = $data_email['newemail'];
            }
        }

        if($email != ''){
            $select_data_sql = "SELECT * FROM `$database_data_email_name` WHERE `email` = '$email' AND `id_user` = '$id_user'";
            $select_data_result = $mysqli_connect->query($select_data_sql);
            $select_data_row = $select_data_result->fetch_assoc();

            if(!$select_data_row){
                $insert_data_email_sql = "INSERT INTO `$database_data_email_name`(`id_user`, `email`) VALUES ('$id_user','$email')";
                $insert_data_email_result = $mysqli_connect->query($insert_data_email_sql);
            }

            if($data_email['newemail'] != ''){
                $insert_log_data_sql = "INSERT INTO `log_import_data`(`log_name`, `log_detail`) VALUES ('email_filter','$data_email[email] to $data_email[newemail]')";
                $insert_log_data_result = $mysqli_connect->query($insert_log_data_sql);
            }
        }
    }

    if (!empty($_FILES)) {
        $File = $_FILES['file']['name'];
        $tempFile = $_FILES['file']['tmp_name'];
        $newFileName = 'csv_'.date('d-m-Y-H-i-s').'.'.pathinfo($File, PATHINFO_EXTENSION);
        $targetFile = '../import-file/';
        move_uploaded_file($tempFile, $targetFile.$newFileName);

        echo $newFileName;
    }

    if(isset($_GET['action'])) {
        $filename = $_GET['action'];
        $csv = array();
        $lines = file($domain . '/import-file/' . $filename, FILE_IGNORE_NEW_LINES);

        foreach ($lines as $key => $value) {
            $csv[$key] = str_getcsv($value);
        }

        $rowSize = count($csv);
        $columnSize = max(array_map('count', $csv));

        if($rowSize >= 5){
            $maxshowing = 5;
        }
        else{
            $maxshowing = $rowSize;
        }


//// show array
//echo '<pre>';
//print_r($csv);
//echo '</pre>';

        echo '<br><p class="text-center">มีจำนวนข้อมูลทั้งหมด <span class="highlight">' . $rowSize . '</span> รายการ</p>';
        echo '<h3 class="text-center">ตัวอย่างข้อมูล</h3>
              <form id="select-csv-col">
              <div class="table-responsive">
                <table class="table table-hover mb-none">
                    <thead>
                        <tr>
                        
        ';

                        for ($j = 0; $j < $columnSize; $j++) {
                    echo '<th><select id="import" class="form-control" name="select[' . $j . ']">
                            <option value="">เลือก</option>
                            <option value="id_user">รหัสนักศึกษา</option>
                            <option value="name">ชื่อ</option>
                            <option value="lastname">นามสกุล</option>
                            <option value="tel">เบอร์โทร</option>
                            <option value="faculty">คณะ</option>
                            <option value="email1">อีเมล์1</option>
                            <option value="email2">อีเมล์2</option>
                            <option value="id_type">ประเภทข้อมูล</option>
                          </select></th>
                     ';
        }
        echo '</tr></thead><tbody>';

        for ($i = 0; $i != $maxshowing; $i++) {
            echo '<tr>';
            for ($j = 0; $j < $columnSize; $j++) {
                echo '<td>' . $csv[$i][$j] . '</td>';
            }
            echo '</tr>';
        }
        echo '</table>
            </div>

            <div class="form-group" style="padding-top: 1.5em;padding-bottom: 1em;">
				<label class="col-sm-2 control-label">ตัวเลือกเพิ่มเติม : </label>
						
                    <div class="col-sm-10">
                        <div class="checkbox-custom checkbox-default">
                            <input type="checkbox" id="email_filter" name="email_filter" checked>
                            <label for="email_filter">เปิดใช้งานการกรองอีเมล์โดยระบบก่อนการบันทึกข้อมูล</label>
                        </div>
                        <div class="radio-custom">
                            <input type="radio" id="no_overwrite_available" name="overwrite" value="no_overwrite_available" checked>
                            <label for="no_overwrite_available">ไม่เขียนทับข้อมูลหากเป็นข้อมูลชนิด Available</label>
                        </div>
                        <div class="radio-custom">
                            <input type="radio" id="overwrite_all" name="overwrite" value="overwrite_all">
                            <label for="overwrite_all">เขียนทับทุกข้อมูล</label>
                        </div>
                        <div class="radio-custom">
                            <input type="radio" id="no_overwrite" name="overwrite" value="no_overwrite">
                            <label for="no_overwrite">ไม่เขียนทับทุกข้อมูล</label>
                        </div>
                    </div>
                </div>
        </form>

        <a href="#" id="import" class="mb-xs mt-xs mr-xs btn btn-primary btn-lg btn-block ladda-button" data-style="expand-right" data-file="'.$filename.'">
            <span class="ladda-label"><i class="fa fa-upload"></i> Import</span>
        </a>';
    }

    if(isset($_POST['action'])) {
        if(!isset($_SESSION)){ session_start(); }
        require_once '../config.php';
        require_once '../source/member.php';
        require_once '../source/permission.php';
        require_once '../source/secure/InputFilter.php';

        $member_row = member_row();
        if($_POST['action'] == 'import_csv_data') {
            if (permission_allow($member_row, 4)) {

                $filename = $_POST['filename'];
                $csv = array();
                $lines = file($domain . '/import-file/' . $filename, FILE_IGNORE_NEW_LINES);

                foreach ($lines as $key => $value) {
                    $csv[$key] = str_getcsv($value);
                }

                $rowSize = count($csv);
                $columnSize = max(array_map('count', $csv));
                $select = $_POST['select'];
                $selectSize = count($select);

                $search_num_id_user_select =  array_search('id_user', $select);
                if(!is_numeric($search_num_id_user_select)){
                    $return_data = array('no id user');
                    echo json_encode($return_data);
                }
                else {
                    for($i=0;$i!=$rowSize;$i++){

                        $id_user = $csv[$i][$search_num_id_user_select];

                        // Search Duplicate Data SQL
                        $select_data_sql = "SELECT * FROM `$database_data_name` WHERE `id_user` = '$id_user'";
                        $select_data_result = $mysqli_connect->query($select_data_sql);
                        $select_data_row = $select_data_result->fetch_assoc();

                        // Delete Duplicate Data SQL
                        $delete_data_sql = "DELETE FROM `$database_data_name` WHERE `id_user` = '$id_user'";

                        // Insert Log Data SQL
                        $insert_log_data_sql = "INSERT INTO `log_import_data`(`log_name`, `log_detail`) VALUES ('update_data','id_data = $select_data_row[id_data]')";

                        if(!$select_data_row){
                            $output_data = insert_data_csv($select,$csv[$i]);
                            $insert_data_sql[$i] = "INSERT INTO `$database_data_name` ($output_data[field]) VALUES ($output_data[newdata])";
                            $insert_data_result = $mysqli_connect->query($insert_data_sql[$i]);
                        }
                        else{
                            if(isset($_POST['overwrite'])){
                                if($_POST['overwrite'] == 'overwrite_all'){

                                    $insert_log_data_result = $mysqli_connect->query($insert_log_data_sql);
                                    $output_data = update_data_csv($select,$csv[$i]);
                                    $update_data_sql[$i] = "UPDATE `$database_data_name` SET $output_data[value] WHERE `id_data` = '$select_data_row[id_data]'";
                                    $update_data_result = $mysqli_connect->query($update_data_sql[$i]);
                                }
                                else if($_POST['overwrite'] == 'no_overwrite_available'){
                                    if($select_data_row['id_type'] != '1'){

                                        $insert_log_data_result = $mysqli_connect->query($insert_log_data_sql);
                                        $output_data = update_data_csv($select,$csv[$i]);
                                        $update_data_sql[$i] = "UPDATE `$database_data_name` SET $output_data[value] WHERE `id_data` = '$select_data_row[id_data]'";
                                        $update_data_result = $mysqli_connect->query($update_data_sql[$i]);
                                    }
                                }
                                else if($_POST['overwrite'] == 'no_overwrite'){}
                            }
                        }


                        if(isset($_POST['email_filter'])){
                            $email_filter = $_POST['email_filter'];
                        }
                        else $email_filter = 'off';


                        $search_num_email1_select =  array_search('email1', $select);
                        $search_num_email2_select =  array_search('email2', $select);
                        if(is_numeric($search_num_email1_select)){
                            insert_data_email($id_user,$csv,$i,$search_num_email1_select,$email_filter);
                        }

                        if(is_numeric($search_num_email2_select)){
                            insert_data_email($id_user,$csv,$i,$search_num_email2_select,$email_filter);
                        }

                    }

                    $return_data = array('success');
                    echo json_encode($return_data);
                }
            }
        }
    }
?>