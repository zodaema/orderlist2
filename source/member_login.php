<?php
    session_start();
    require_once '../config.php';
    require_once 'secure/PasswordHash.php';
    require_once 'secure/InputFilter.php';
    require_once 'model/member_class.php';
    require_once 'model/table_class.php';

	if(isset($_POST['action'])){
		if($_POST['action'] == 'login_normal') {

            // error_log(json_encode($_POST));
            if (empty($_POST['email']) OR empty($_POST['password'])) {
                echo 'fill';
            } else {
                $email = trim(InputFilter($_POST['email']));
                $password = trim(InputFilter($_POST['password']));
                $objLogin = new memberLogin();

                // error_log($email);
                if($objLogin->getLogin($email) == 'no_email'){
                    echo 'noemail';
                }
                else{
                    if ($objLogin->password_authenticate($_POST['password'])) {
                        echo $objLogin->member_login();

                        // if(isset($_SESSION['login'])){
                        //     if(empty($_SESSION['member'])){
                        //         $objTable = new queryTable();

                        //         $token = md5($_SESSION['login']);
                        //         $search_token_sql = "SELECT `id_member` FROM `log_account_login` WHERE `token` = '$token'";
                        //         $search_token_result = $objTable->queryByData($search_token_sql);
                        //         $search_token_row = $search_token_result->fetch_array();
                
                        //         if(isset($search_token_row)){
                        //             $_SESSION['member']['id_member'] = $search_token_row['id_member'];
                        //             $id_member = $_SESSION['member']['id_member'];
                        //             echo 'success';
                        //         }
                        //         else{
                        //             echo 'no Token';
                        //         }
                        //     }
                        // }

                        echo 'success';

                    } else {
                        echo 'error';
                    }
                }
            }
        }
	}