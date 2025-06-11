<?php

	if(isset($_GET['action'])){
		session_start();
		require_once '../config.php';
		require_once '../source/secure/InputFilter.php';
        require_once '../source/model/member_class.php';

        $objMemberDetail = new memberDetail();
        $objPermissionCheck = new checkPermission();

        if($_GET['action'] == 'product_array') {

            $data_result = $pdo->query('SELECT * FROM `product`');
            $totalFiltered = $data_result->rowCount();
            $totalData = $totalFiltered;

            // Default DATA
            $dataFilter = array(
                "itemPerPage" => "12",
                "orderBy"=> "id_product",
                "sort"=> "DESC",
                "currentPage"=> "1"
            );
            if(isset($_GET['itemPerPage'])){
                $dataFilter['itemPerPage'] = trim(NumberFilter($_GET['itemPerPage']));
            }
            if(isset($_GET['orderBy'])){
                $dataFilter['orderBy'] = trim(InputFilter($_GET['orderBy']));
            }
            if(isset($_GET['sort'])){
                $dataFilter['sort'] = trim(InputFilter($_GET['sort']));
            }
            if(isset($_GET['currentPage'])){
                $dataFilter['currentPage'] = trim(NumberFilter($_GET['currentPage']));
            }

            $offsetPage = ($dataFilter['currentPage'] - 1) * $dataFilter['itemPerPage'];

            $data_sql = "SELECT * FROM `product` WHERE 1 ";
            $data_sql.= "ORDER BY $dataFilter[orderBy] ";
            if($dataFilter['sort'] == "DESC"){
                $data_sql.="DESC";
            }
            else{
                $data_sql.="ASC";
            }
            $data_sql.= " LIMIT :offsetPage, :itemPerPage";
            $data_result = $pdo->prepare($data_sql);
            $data_result->execute([ 
                // 'orderBy' => $dataFilter["orderBy"],
                // 'sort' => $dataFilter["sort"],
                'offsetPage' => $offsetPage,
                'itemPerPage' => $dataFilter["itemPerPage"]
            ]);
            
            foreach ($data_result as $data_row){
                $collectData[] = $data_row;
            }

            $output = array(
                "recordsTotal"    => intval( $totalData ),  // total number of records
                "recordsFiltered" => intval( $totalFiltered ), // total number of records after searching, if there is no searching then totalFiltered = totalData
                "data" => $collectData
            );

            echo json_encode($output);
        }

        if($_GET['action'] == 'show_cart') {
            $member_row = $objMemberDetail->getDetail($_SESSION['member']['id_member']);
            $cartdata_result = $pdo->prepare("SELECT * FROM `cart` WHERE id_member = :id_member");
            $cartdata_result->bindParam('id_member', $member_row['id_member'], PDO::PARAM_INT);
            $cartdata_result->execute();

            $cartData = array();
            foreach ($cartdata_result as $cartdata_row){

                $product_result = $pdo->prepare("SELECT * FROM `product` WHERE id_product = :id_product");
                $product_result->bindParam('id_product', $cartdata_row['id_product'], PDO::PARAM_INT);
                $product_result->execute();
                $product_row = $product_result->fetch(PDO::FETCH_ASSOC);
                
                $cartData[] = array(
                    "id_cart" => $cartdata_row['id_cart'],
                    "id_product" => $cartdata_row['id_product'],
                    "product" => $product_row['product'],
                    "amount" => $cartdata_row['amount'],
                    "price" => $product_row['price'],
                    "thumbnail" => $product_row['img']
                );    
            }

            $output = array(
                "Cart" => $cartData
            );

            echo json_encode($output);
        }
	}

    if(isset($_POST['action'])){
		session_start();
		require_once '../config.php';
		require_once '../source/secure/InputFilter.php';
        require_once '../source/model/member_class.php';

        $objMemberDetail = new memberDetail();
        $objPermissionCheck = new checkPermission();

        if($_POST['action'] == 'addtocart'){
            $cartdata = json_decode(trim(stripslashes(json_encode($_POST['cartdata'])),'"'), true);
            $member_detail = $objMemberDetail->thisLogin();

            if(count($cartdata) > 0){
                for($i=0; $i<count($cartdata); $i++){
                    $search_duplicate = $pdo->prepare("SELECT * FROM `cart` WHERE `id_member` = :id_member AND `id_product` = :id_product");
                    $search_duplicate->execute([
                        "id_member" => $member_detail['id_member'],
                        "id_product" => NumberFilter($cartdata[$i]['id_product'])
                    ]);
                    $search_duplicate_row = $search_duplicate->fetch(PDO::FETCH_ASSOC);

                    if($search_duplicate_row !== false){
                        $this_amount = $search_duplicate_row['amount']+NumberFilter($cartdata[$i]['amount']);

                        $queryData = $pdo->prepare("UPDATE `cart` SET `amount`=:amount WHERE `id_member` = :id_member AND `id_product` = :id_product");
                        $queryData->execute([
                            "amount" => $this_amount,
                            "id_member" => $member_detail['id_member'],
                            "id_product" => NumberFilter($cartdata[$i]['id_product'])
                        ]);
                    }
                    else{
                        $queryData = $pdo->prepare("INSERT INTO `cart`(`id_product`, `id_member`, `amount`) VALUES (:id_product,:id_member,:amount)");
                        $queryData->execute([
                            "amount" => NumberFilter($cartdata[$i]['amount']),
                            "id_member" => $member_detail['id_member'],
                            "id_product" => NumberFilter($cartdata[$i]['id_product'])
                        ]);
                    }
                }
                echo 'success';
            }
            else{
                echo 'No Data';
            }
        }

        if($_POST['action'] == 'deletecart'){
            $member_detail = $objMemberDetail->thisLogin();

            if(isset($_POST['id_cart'])){
                $search_cart = $pdo->prepare("SELECT * FROM `cart` WHERE `id_cart` = :id_cart");
                $search_cart->bindParam('id_cart', $_POST['id_cart'], PDO::PARAM_INT);
                $search_cart->execute();
    
                $search_cart_row = $search_cart->fetch(PDO::FETCH_ASSOC);
    
                if($member_detail['id_member'] == $search_cart_row['id_member']){
                    $delete_cart = $pdo->prepare("DELETE FROM `cart` WHERE `id_cart` = :id_cart");
                    $delete_cart->bindParam('id_cart', $_POST['id_cart'], PDO::PARAM_INT);
                    $delete_cart->execute();
                    echo 'success';
                }
                else{
                    echo 'Not your cart';
                }
            }
            else{
                echo 'No Data';
            }
        }

        if($_POST['action'] == 'cart_edit_amount'){
            $member_detail = $objMemberDetail->thisLogin();

            $amount = NumberFilter($_POST['value']);
            $id_cart = NumberFilter($_POST['pk']);

            if($amount != 0 && $amount <= 1000){
                $search_cart = $pdo->prepare("SELECT * FROM `cart` WHERE `id_cart` = :id_cart");
                $search_cart->bindParam('id_cart', $id_cart, PDO::PARAM_INT);
                $search_cart->execute();

                $search_cart_row = $search_cart->fetch(PDO::FETCH_ASSOC);
                if($member_detail['id_member'] == $search_cart_row['id_member']){

                    $update_cart = $pdo->prepare("UPDATE `cart` SET `amount`=:amount WHERE `id_cart` = :id_cart");
                    $update_cart->execute([
                        "amount" => $amount,
                        "id_cart" => $id_cart
                    ]);

                    echo 'success';
                }
                else{
                    echo 'Not your cart';
                }
            }
            else{
                echo 'max: 1000';
            }

        }
    }