<?php

	if(isset($_GET['action'])){
		session_start();
		require_once '../config.php';
		require_once '../source/secure/InputFilter.php';
        require_once '../source/model/table_class.php';
        require_once '../source/model/member_class.php';

        $objMemberDetail = new memberDetail();
        $objPermissionCheck = new checkPermission();

        if($_GET['action'] == 'table_data_all_serverside') {
            if ($objPermissionCheck->permissionAllow($objMemberDetail->thisLogin(),2) == true) {
                $objTable = new queryTable;

                // QUERY GLOBAL DATA
                
                // $order_status_result = $objTable->queryByName('order_status');
                // while($order_status_row = $order_status_result->fetch_assoc()){
                //     if($status_data != 5){
                //         $status_data[$order_status_row['id_status']] = array(
                //             'status_name'=> $order_status_row['status']
                //         );
                //     }
                // }

                $customer_source_result = $objTable->queryByName('customer_source');
                while($customer_source_row = $customer_source_result->fetch_assoc()){
                    $customer_source_data[$customer_source_row['id_source']] = array(
                        'name'=> $customer_source_row['name']
                    );
                }

                $craftsman_order_hire_result = $objTable->queryByName('craftsman_order_hire');
                while($craftsman_order_hire_row = $craftsman_order_hire_result->fetch_assoc()){
                    $craftsman_order_hire_data[$craftsman_order_hire_row['id_hire']] = array(
                        'name' => $craftsman_order_hire_row['name']
                    );
                }

                $craftsman_order_status_result = $objTable->queryByName('craftsman_order_status');
                while($craftsman_order_status_row = $craftsman_order_status_result->fetch_assoc()){
                    if($craftsman_order_status_row['id_cos'] != '5'){
                        $craftsman_order_status_data[$craftsman_order_status_row['id_cos']] = array(
                            'name'=> $craftsman_order_status_row['name']
                        );
                    }
                }

				// START Query Table detail
                $data_result = $objTable->queryByName('orders');
                $totalData = mysqli_num_rows($data_result);
                $totalFiltered = $totalData;

                $filter_pam = [
                    'range_date' => $_GET['filter_date'],
                    'custom_range_date' => $_GET['filter_custom_daterange'],
                    'status' => $_GET['filter_status'],
                    'source' => $_GET['filter_source'],
                    'craftsman_hire' => $_GET['filter_craftsman_hire']
                ];

                $data_sql = "SELECT * FROM `orders` WHERE ";

                if($filter_pam['status'] != 0){
                    $data_sql .= "`id_cos` = '".$filter_pam['status']."'";
                }
                else{
                    $data_sql .= "`id_cos` != '5'";
                }

                if($filter_pam['craftsman_hire'] != 0){
                    $data_sql .= " AND `id_hire` = '".$filter_pam['craftsman_hire']."'";
                }

                // Filter ตามเงื่อนไขของวันที่
                $today_start = date("Y-m-d 00:00:00");
                $today_end = date("Y-m-d 23:59:59");

                switch ($filter_pam['range_date']) {
                    case 1:
                        // ทั้งหมด (ไม่ต้องเพิ่มเงื่อนไข)
                        break;
                
                    case 2:
                        // วันนี้
                        $data_sql .= " AND `orderdate` BETWEEN '$today_start' AND '$today_end'";
                        break;
                
                    case 3:
                        // เมื่อวาน
                        $yesterday_start = date("Y-m-d 00:00:00", strtotime("-1 day"));
                        $yesterday_end = date("Y-m-d 23:59:59", strtotime("-1 day"));
                        $data_sql .= " AND `orderdate` BETWEEN '$yesterday_start' AND '$yesterday_end'";
                        break;
                
                    case 4:
                        // 7 วันที่ผ่านมา
                        $seven_days_ago = date("Y-m-d 00:00:00", strtotime("-6 days")); // รวมวันนี้
                        $data_sql .= " AND `orderdate` BETWEEN '$seven_days_ago' AND '$today_end'";
                        break;
                
                    case 5:
                        // 30 วันที่ผ่านมา
                        $thirty_days_ago = date("Y-m-d 00:00:00", strtotime("-29 days")); // รวมวันนี้
                        $data_sql .= " AND `orderdate` BETWEEN '$thirty_days_ago' AND '$today_end'";
                        break;
                
                    case 6:
                        // เดือนนี้
                        $month_start = date("Y-m-01 00:00:00");
                        $month_end = date("Y-m-t 23:59:59");
                        $data_sql .= " AND `orderdate` BETWEEN '$month_start' AND '$month_end'";
                        break;
                
                    case 7:
                        // ปีนี้
                        $year_start = date("Y-01-01 00:00:00");
                        $year_end = date("Y-12-31 23:59:59");
                        $data_sql .= " AND `orderdate` BETWEEN '$year_start' AND '$year_end'";
                        break;
                    case 8:
                        if (!empty($filter_pam['custom_range_date'])) {
                            $date_parts = explode(' to ', $filter_pam['custom_range_date']);
                            if (count($date_parts) == 2) {
                                $start_date = date("Y-m-d 00:00:00", strtotime(trim($date_parts[0])));
                                $end_date = date("Y-m-d 23:59:59", strtotime(trim($date_parts[1])));
                                $data_sql .= " AND `orderdate` BETWEEN '$start_date' AND '$end_date'";
                            } elseif (count($date_parts) == 1) {
                                // กรณีเลือกแค่วันเดียว
                                $start_date = date("Y-m-d 00:00:00", strtotime(trim($date_parts[0])));
                                $end_date = date("Y-m-d 23:59:59", strtotime(trim($date_parts[0])));
                            }
                    
                            if (isset($start_date) && isset($end_date)) {
                                $data_sql .= " AND `orderdate` BETWEEN '$start_date' AND '$end_date'";
                            }
                        }
                        break;
                }


                // if(isset($filter_pam['source'])){
                //     // Check Customer Source
                //     $search_customer_like_this_source_result = $objTable->search('customer_details', 'id_source', $filter_pam['source']);
                //     $search_customer_like_this_source_row = $search_customer_like_this_source_result->fetch_assoc();
                //     $search_id_customer = implode("','",$search_customer_like_this_source_row['id_customer']);
                //     $item_output['test'] = $search_id_customer;
                //     // $data_sql .= " AND id_customer IN ('".$search_id_customer."')";
                // }

                // Searching
                $requestData= $_REQUEST;
                if( !empty($requestData['columns'][1]['search']['value'])){
                    $data_sql.=" AND ( `id_status` LIKE '%".$requestData['columns'][1]['search']['value']."%' )";
                }
                // End searching

                $data_result = $objTable->queryByData($data_sql);
                $totalFiltered = mysqli_num_rows($data_result);

                if($totalFiltered == 0){
                    $item_output = '';
                }
                else{
                    $columns = array(
                        1 => 'orderdate',
                        2 => 'id_cos',
                        4 => 'id_hire'
                    );

                    if(empty($requestData['order'])){return;}
                    $data_sql.=" ORDER BY ". $columns[$requestData['order'][0]['column']]." ".$requestData['order'][0]['dir']." LIMIT ".$requestData['start']." ,".$requestData['length']." ";

                    error_log($data_sql);
                    $data_result = $objTable->queryByData($data_sql);
                    while ($data_row = $data_result->fetch_assoc()) {
                        $data = $data_row;
                        $customer_details_result = $objTable->search('customer_details', 'id_customer', $data['id_customer']);
                        $customer_details_row = $customer_details_result->fetch_assoc();

                        $order_detail_result = $objTable->search('order_detail', 'id_order', $data['id_order']);
                        $data['order_detail'] = '';
                        while($order_detail_row = $order_detail_result->fetch_assoc()){
                            $data['order_detail'] = $data['order_detail']."- ".$order_detail_row['detail'].'<br>';
                        }

                        if($filter_pam['source'] != 0){
                            if($customer_details_row['id_source'] != $filter_pam['source']){
                                continue;
                            }
                        }

                        $item_output[] = array(
                            'source' => $customer_source_data[$customer_details_row['id_source']]['name'],
                            'id_order' => $data['id_order'],
                            'username' => $customer_details_row['username'],
                            'status' => $status_data[$data['id_status']]['status_name'],
                            'hire_data' => $craftsman_order_hire_data,
                            'hire' => $craftsman_order_hire_data[$data['id_hire']]['name'],
                            'id_hire' => $data['id_hire'],
                            'craftman_status_data' => $craftsman_order_status_data,
                            'craftsman_status' => $craftsman_order_status_data[$data['id_cos']]['name'],
                            'id_cos' => $data['id_cos'],
                            'order_detail' => $data['order_detail'],
                            'orderdate' => date("d-m-Y",strtotime($data['orderdate'])),
                            'DT_RowId' => $data['id_order'],
                            'DT_RowClass' => 'data_item'
                        );
                    }
                }
                $output['data'] = $item_output;

                $output = array(
                    "draw"            => intval( $requestData['draw'] ),
                    "recordsTotal"    => intval( $totalData ),  // total number of records
                    "recordsFiltered" => intval( $totalFiltered ), // total number of records after searching, if there is no searching then totalFiltered = totalData
                    "data" => $item_output,
                );

                echo json_encode($output);
            }
            else{
                $return_data = array('no permission',$_POST['id_order']);
                echo json_encode($return_data);
            }
        }
	}

    if(isset($_POST['action'])){
		session_start();
		require_once '../config.php';
		require_once '../source/secure/InputFilter.php';
        require_once '../source/model/table_class.php';
        require_once '../source/model/member_class.php';

        $objMemberDetail = new memberDetail();
        $objPermissionCheck = new checkPermission();

        if($_POST['action'] == 'change_craftman_status') {
            if ($objPermissionCheck->permissionAllow($objMemberDetail->thisLogin(),2) == true) {
                $objTable = new queryTable;

                $id_order = trim($_POST['id_order']);
                $id_cos = trim(InputFilter($_POST['id_cos']));

                $search_craftsman_order_result = $objTable->queryByData("SELECT * FROM `orders` WHERE `id_order` = '$id_order'");
                $search_craftsman_order_row = $search_craftsman_order_result->fetch_assoc();
                
                if(isset($search_craftsman_order_row)){
                    $change_cos = $objTable->queryByData( "UPDATE `orders` SET `id_cos`='$id_cos' WHERE `id_order` = $id_order" );
                    $return_data = array('success');
                    echo json_encode($return_data);
                }
                else{
                    error_log('craftsman_order_manager empty table id_order ='.$id_order);
                    $return_data = array('empty');
                    echo json_encode($return_data);
                }
            }
            else{
                $return_data = array('no permission');
                echo json_encode($return_data);
            }
        }

        if($_POST['action'] == 'edit_order_form') {
            if ($objPermissionCheck->permissionAllow($objMemberDetail->thisLogin(),2) == true) {
                $objTable = new queryTable;

                $id_order = trim($_POST['id_order']);

                $orderData_result = $objTable->search('orders','id_order',$id_order);
                $orderData_row = $orderData_result->fetch_assoc();

                $customerData_result = $objTable->search('customer_details','id_customer', $orderData_row['id_customer']);
                $customerData_row = $customerData_result->fetch_assoc();

                echo '
                    <div class="container mb-3">
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group mb-3">
                                    <label class="form-label">Order ID</label>
                                    <input name="orderid" class="form-control" value="'.$orderData_row['id_order'].'" disabled readonly>
                                </div>
                                <div class="form-group mb-3">
                                    <label class="form-label">ช่องทาง</label>
                                    <select name="source" id="id_source" class="form-select" required>
                                    
                                    ';

                                        $data = $objTable->queryByName('customer_source');
                                        while($data_row = $data->fetch_assoc()){
                                            echo '<option value="'.$data_row['id_source'].'" ';
                                            if($data_row['id_source'] == $customerData_row['id_source']) {
                                                echo 'selected';
                                            }
                                        
                                            echo '>'.$data_row['name'].'</option>';
                                        }

                                    echo '
                                    </select>
                                    <input name="username" class="form-control mt-1" placeholder="ชื่อจากที่มา" value="'.$customerData_row['username'].'" required>
                                </div>
                                <div class="form-group mb-3">
                                    <label class="form-label">วันที่</label>
                                    <input name="date" type="date" class="form-control" value="'. date("Y-m-d",strtotime($orderData_row['orderdate'])).'" required>
                                    <input name="time" type="time" class="form-control mt-1" value="'.date("h:m",strtotime($orderData_row['orderdate'])).'">
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label class="form-label">ข้อมูลลูกค้า</label>
                                    <div class="row mb-3">
                                        <textarea id="temporary_address" name="temporary_address" rows="3" class="form-control" placeholder="กล่องพักที่อยู่ลูกค้า"></textarea>
                                    </div>

                                    ';

                                    // Address
                                    $AddressData_result = $objTable->search('address_data','id_address', $orderData_row['id_address']);
                                    $AddressData_row = $AddressData_result->fetch_assoc();
                                    echo'
                                    <div class="row">
                                        <input name="name" class="form-control" placeholder="ชื่อผู้รับ" value="'.$AddressData_row['name'].'" required>
                                        <input name="tel" class="form-control mt-1" type="tel" placeholder="เบอร์โทร" value="'.$AddressData_row['tel'].'" required>
                                        <textarea name="address" rows="3" class="form-control mt-1" placeholder="ที่อยู่" required>'.$AddressData_row['address'].'</textarea>
                                    </div>
                                </div>
                            </div>

                            <div class="col-12 mb-3">
                                <label class="form-label">ออเดอร์</label>
                                <div class="row mb-3">
                                    <textarea id="temporary_detail" name="temporary_detail" rows="3" class="form-control" placeholder="กล่องพักรายละเอียดออเดอร์"></textarea>
                                </div>
                                <div name="orderDetail">';

                                $orderDetail_result = $objTable->search('order_detail', 'id_order' ,$orderData_row['id_order']);
                                $orderDetail_Count = $orderDetail_result->num_rows;
                                if($orderDetail_Count > 0){
                                    $i = 1;
                                    while($orderDetail_row = $orderDetail_result->fetch_assoc()){
                                        echo'
                                        <div class="row mb-3 order-detail-row">
                                            <label for="order_detail_'.$i.'" class="col-sm-1 col-form-label">#'.$i.'</label>
                                            <div class="col-sm-8">
                                                <textarea id="orderDetailDetail" name="order_detail_'.$i.'" rows="1" class="form-control" placeholder="รายละเอียดออเดอร์" required>'.$orderDetail_row['detail'].'</textarea>
                                                <input name="idDetail_'.$i.'" value="'.$orderDetail_row['id_detail'].'" style="display:none;" />
                                            </div>
                                            <div class="col-sm-2">
                                                <input id="orderDetailPrice" type="number" name="order_price_'.$i.'" class="form-control" placeholder="ราคา" data-id-detail="'.$orderDetail_row['id_detail'].'" value="'.$orderDetail_row['price'].'" >
                                            </div>';

                                            if($i>1){
                                                echo'
                                            <div class="col-sm-1">
                                                <button type="button" class="btn btn-danger btn-sm remove-detail">ลบ</button>
                                            </div>
                                                ';
                                            }
                                            
                                            echo'
                                        </div>
                                        ';
                                        $i++;
                                    }
                                }
                                echo'
                                </div>
                                <div class="more-area"></div>
                                <div class="col-sm-12 text-center">
                                    <button type="button" class="btn btn-primary add-more-order-detail">เพิ่ม</button>
                                    <input name="number_order_details" class="number_order_details" value="'.--$i.'" style="display:none;" />
                                </div>
                            </div>

                            <div class="row justify-content-center">
                                <div class="col-5 text-center">
                                    <label class="form-label">ค่าจัดส่ง</label>
                                    <input type="number" name="shipping_price" class="form-control text-center" value="'.floatval(NumberFilter($orderData_row['postprice'])).'">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                ';
            }
        }

        if($_POST['action'] == 'edit_order_submit') {
            if ($objPermissionCheck->permissionAllow($objMemberDetail->thisLogin(),2) == true) {
                $objTable = new queryTable;

                $return_data = array('start');

                if(!empty($_POST['username'] && $_POST['number_order_details'])){
                    $id_order = NumberFilter($_POST['orderid']);
                    $id_source = NumberFilter($_POST['source']);
                    $username = InputFilter($_POST['username']);
                    $date = InputFilter($_POST['date']);
                    $time = InputFilter($_POST['time']);
                    $name = InputFilter($_POST['name']);
                    $tel = NumberFilter($_POST['tel']);
                    $address = InputFilter($_POST['address']);
                    $new_number_order_details = NumberFilter($_POST['number_order_details']);
                    $shipping_price = NumberFilter($_POST['shipping_price']);
                    if($shipping_price == '') {$shipping_price = 0;}

                    $orderData_result = $objTable->search('orders','id_order',$id_order);
                    $orderData_row = $orderData_result->fetch_assoc();

                    $customerData_result = $objTable->search('customer_details','id_customer', $orderData_row['id_customer']);
                    $customerData_row = $customerData_result->fetch_assoc();

                    $AddressData_result = $objTable->search('address_data','id_address', $orderData_row['id_address']);
                    $AddressData_row = $AddressData_result->fetch_assoc();

                    ///----------------- SQL ZONE ---------------- ////
                        // Generate SQL Address
                        $update_address_sql = 'UPDATE `address_data` SET ';
                        $update_address_fields = [];

                        if($name != $AddressData_row['name']){
                            $update_address_fields[] = "`name`='".$name."'";
                        }
                        if($address != $AddressData_row['address']){
                            $update_address_fields[] = "`address`='".$address."'";
                        }
                        if($tel != $AddressData_row['tel']){
                            $update_address_fields[] = "`tel`='".$tel."'";
                        }
                        if (!empty($update_address_fields)) {
                            $update_address_sql .= implode(',', $update_address_fields);
                        }
                        $update_address_sql .= " WHERE `id_address` = '".$AddressData_row['id_address']."'";

                        // Generate SQL Customer Details
                        $update_customer_details_sql = 'UPDATE `customer_details` SET ';
                        $update_customer_fields = [];

                        if($username != $customerData_row['username']){
                            $update_customer_fields[] = "`username`='".$username."'";
                        }
                        if($id_source != $customerData_row['id_source']){
                            $update_customer_fields[] = "`id_source`='".$id_source."'";
                        }
                        if (!empty($update_customer_fields)) {
                            $update_customer_details_sql .= implode(',', $update_customer_fields);
                        }
                        $update_customer_details_sql .= " WHERE `id_customer` = '".$orderData_row['id_customer']."'";

                        // Generate SQL Orders
                        $update_orders_sql = 'UPDATE `orders` SET ';
                        $update_orders_fields = [];

                        if($shipping_price != $orderData_row['postprice']){
                            $update_orders_fields[] = "`postprice`='".$shipping_price."'";
                        }
                        $order_datetime = $date.' '.$time;
                        $convert_datetime = date('Y-m-d H:i:s', strtotime($order_datetime));
                        if($convert_datetime != $orderData_row['orderdate']){
                            $update_orders_fields[] = "`orderdate`='".$convert_datetime."'";
                        }
                        if (!empty($update_orders_fields)) {
                            $update_orders_sql .= implode(',', $update_orders_fields);
                        }
                        $update_orders_sql .= " WHERE `id_order` = '".$orderData_row['id_order']."'";

                    ///----------------- END SQL ZONE ---------------- ////

                    // Query
                    $update_order_detail_query = $objTable->queryByData($update_address_sql);
                    $update_order_detail_query = $objTable->queryByData($update_customer_details_sql);
                    $update_order_detail_query = $objTable->queryByData($update_orders_sql);

                    // Generate SQL Order Details
                    $totalprice = '0'+$shipping_price;
                    $order_detail_result = $objTable->search('order_detail','id_order',$id_order);
                    $old_number_order_details = $order_detail_result->num_rows;
                    
                    if($new_number_order_details < $old_number_order_details){
                        $remove_order_detail_result = $objTable->queryByData("DELETE FROM `order_detail` WHERE `id_order` = '$id_order'");
                        $old_number_order_details = 0;
                    }

                    if($new_number_order_details > $old_number_order_details){
                        $number_order_details = $new_number_order_details;
                    }
                    else{
                        $number_order_details = $old_number_order_details;
                    }

                    for($i=1; $i<=$number_order_details; $i++ ){
                        $new_order_detail = InputFilter($_POST['order_detail_'.$i]);
                        $new_order_price = InputFilter($_POST['order_price_'.$i]);
                        $id_detail = InputFilter($_POST['idDetail_'.$i]);

                        $order_detail_result = $objTable->search('order_detail','id_detail',$id_detail);
                        $order_detail_row = $order_detail_result->fetch_assoc();

                        if($i > $old_number_order_details){

                            if($new_order_detail !== ''){
                                $create_order_detail_result = $mysqli_connect->query("INSERT INTO `order_detail`(`id_order`, `id_product`, `amount`, `detail`, `price`) VALUES ('$id_order','1','1','$new_order_detail','$new_order_price')");
                            }

                        }
                        // else if($i < $old_number_order_details){
                        //     $remove_order_detail_result = $objTable->queryByData("DELETE FROM `order_detail` WHERE `id_detail` = '$id_detail'");
                        // }
                        else{
                            $update_order_detail_sql = 'UPDATE `order_detail` SET ';
                            $update_order_detail_fields = [];
                            if($new_order_detail != $order_detail_row['detail']){
                                $update_order_detail_fields[] = "`detail`='".$new_order_detail."'";
                            }
                            if($new_order_price != $order_detail_row['price']){
                                $update_order_detail_fields[] = "`price`='".$new_order_price."'";
                            }
                            if (!empty($update_order_detail_fields)) {
                                $update_order_detail_sql .= implode(',', $update_order_detail_fields);
                            }
                            $update_order_detail_sql .= " WHERE `id_detail` = '".$id_detail."'";
                            $return_data = array($update_order_detail_sql);
    
                            $update_order_detail_query = $objTable->queryByData($update_order_detail_sql);
                        }
                        
                        $totalprice += $new_order_price;
                    }

                    // Update Totalprice
                    $update_totalprice = $objTable->queryByData("UPDATE `orders` SET `price`='$totalprice' WHERE `id_order`='$id_order'");

                    $return_data = array('success');
                }
                else{

                }
                echo json_encode($return_data);
            }
        }

        if ($_POST['action'] == 'add_new_order') {
            if ($objPermissionCheck->permissionAllow($objMemberDetail->thisLogin(),2) == true) {
                $objTable = new queryTable;

                if(!empty($_POST['username'] && $_POST['number_order_details'])){

                    $id_source = NumberFilter($_POST['source']);
                    $username = InputFilter($_POST['username']);
                    $date = InputFilter($_POST['date']);
                    $time = InputFilter($_POST['time']);
                    $name = InputFilter($_POST['name']);
                    $tel = NumberFilter($_POST['tel']);
                    $address = InputFilter($_POST['address']);
                    $number_order_details = NumberFilter($_POST['number_order_details']);
                    $shipping_price = NumberFilter($_POST['shipping_price']);
                    if($shipping_price == '') {$shipping_price = 0;}

                    // CREATE CUSTOMER DETAIL
                    $search_customer_name_result = $objTable->queryByData("SELECT * FROM `customer_details` WHERE `username` = '$username'");
                    $search_customer_name_row = $search_customer_name_result->fetch_assoc();
                    
                    if(!$search_customer_name_row){
                        $insert_customer_detail_result = $mysqli_connect->query( "INSERT INTO `customer_details` (`id_source`, `username`) VALUES ('$id_source', '$username')" );
                        $id_customer = $mysqli_connect->insert_id;
                    }
                    else{
                        $id_customer = $search_customer_name_row['id_customer'];
                    }
                    
                    // CREATE ADDRESS DETAIL
                    // $search_address_result = $objTable->queryByData("SELECT * FROM `address_data` WHERE `tel` = '$tel'");
                    // $search_address_row = $search_address_result->fetch_assoc();

                    // if(isset($search_address_row)){
                    //     // Check duplicate address 
                    //     if($search_address_row['address'] !== $address){
                    //         $insert_address_result = $mysqli_connect->query("INSERT INTO `address_data`( `id_customer`, `name`, `tel`, `address`) VALUES ( '$id_customer','$name','$tel','$address')");
                    //         $insert_address_row['id_address'] = $mysqli_connect->insert_id;

                    //         $id_address = $insert_address_row['id_address'];
                    //     }
                    //     else{
                    //         $id_address = $search_address_row['id_address'];
                    //     }
                    // }
                    // else{
                        $insert_address_result = $mysqli_connect->query("INSERT INTO `address_data`( `id_customer`, `name`, `tel`, `address`) VALUES ( '$id_customer','$name','$tel','$address')");
                        $insert_address_row['id_address'] = $mysqli_connect->insert_id;

                        $id_address = $insert_address_row['id_address'];
                    // }
                    
                    // CREATE ORDER
                    if(($id_customer && $id_address) !== ''){
                        $order_datetime = $date.' '.$time;
                        $convert_datetime = date('Y-m-d H:i:s', strtotime($order_datetime));
                        $create_order_result = $mysqli_connect->query("INSERT INTO `orders`(`id_customer`, `id_status`, `id_address`, `id_cos`, `id_hire`, `price`, `postprice`, `orderdate`) VALUES ('$id_customer','1','$id_address', '1', '1', '0', '$shipping_price', '$convert_datetime')");
                        $create_order_row['id_order'] = $mysqli_connect->insert_id;
                    }

                    $id_order = $create_order_row['id_order'];

                    // CREATE ORDER DETAILS
                    if(isset($id_order)){
                        if($number_order_details == ''){
                            $number_order_details = 1;
                        }

                        $totalprice = '0'+$shipping_price;
                        for($i=1; $i<=$number_order_details; $i++ ){
                            $order_detail = InputFilter($_POST['order_detail_'.$i]);
                            $order_price = NumberFilter($_POST['order_price_'.$i]);

                            if($order_detail !== ''){
                                $create_order_detail_result = $mysqli_connect->query("INSERT INTO `order_detail`(`id_order`, `id_product`, `amount`, `detail`, `price`) VALUES ('$id_order','1','1','$order_detail','$order_price')");
                            }
                            
                            $totalprice += $order_price;
                        }

                        // Update Totalprice
                        $update_totalprice = $objTable->queryByData("UPDATE `orders` SET `price`='$totalprice' WHERE `id_order`='$id_order'");

                        // // CREATE HIRE MANAGER
                        // $search_craftsman_order_manager_result = $mysqli_connect->query("SELECT * FROM `craftsman_order_manager` WHERE 'id_order' = '$id_order'");
                        // $search_craftsman_order_manager_row = $search_craftsman_order_manager_result->fetch_assoc();
    
                        // if(!$search_craftsman_order_manager_row){
                        //     $create_order_detail_result = $mysqli_connect->query("INSERT INTO `craftsman_order_manager`(`id_order`, `id_hire`, `id_cos`) VALUES ('$id_order','1','1')");
                        // }
                    }
                    
                    $return_data = array('success');
                    echo json_encode($return_data);
                }
                else{
                    $return_data = array('blank');
                    echo json_encode($return_data);
                }

            }
            else{
                $return_data = array('no permission');
                echo json_encode($return_data);
            }
        }

        if ($_POST['action'] == 'delete_order') {
            if ($objPermissionCheck->permissionAllow($objMemberDetail->thisLogin(),2) == true) {
                $objTable = new queryTable;

                $id_order = trim($_POST['id_order']);

                if(isset($id_order)){
                    $change_status_orders = $objTable->queryByData("UPDATE `orders` SET `id_cos`='5' WHERE `id_order`='$id_order'");
                    echo 'success';
                }

            }
        }

        if ($_POST['action'] == 'delete_multi_order') {
            if ($objPermissionCheck->permissionAllow($objMemberDetail->thisLogin(),2) == true) {
                $objTable = new queryTable;

                $id_order = explode(",",$_POST['id_order']);

                foreach ($id_order as $v) {
                    $change_status_orders = $objTable->queryByData("UPDATE `orders` SET `id_cos`='5' WHERE `id_order`='$v'");
                }

                echo 'success';

                // echo json_encode($id_order);

                // if(isset($id_order)){
                //     $change_status_orders = $objTable->queryByData("UPDATE `orders` SET `id_cos`='5' WHERE `id_order`='$id_order'");
                //     echo 'success';
                // }

            }
        }

    }