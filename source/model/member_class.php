<?php
    class memberLogin{
        private $mysqli_connect;
        private $email;
        private $password;
        private $member_row;
        private $ipaddress;

        public function __construct(){
            $objMysqlConnect = new mysqlConnect();
            $this->mysqli_connect = $objMysqlConnect->getConnection();
        }

        public function getLogin($email){
            $this->get_client_ip();
            $this->email = $email;
            $member_result = $this->mysqli_connect->query("SELECT * FROM `account` WHERE `username` = '$this->email'");
            $this->member_row = $member_result->fetch_array();
            if($this->member_row != NULL){
                $this->password = $this->member_row['password'];
            }
            else{
                return 'no_email';
            }
        }

        public function get_client_ip(){
            $this->ipaddress = '';
            if (isset($_SERVER['HTTP_CLIENT_IP']))
                $this->ipaddress = $_SERVER['HTTP_CLIENT_IP'];
            else if(isset($_SERVER['HTTP_X_FORWARDED_FOR']))
                $this->ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
            else if(isset($_SERVER['HTTP_X_FORWARDED']))
                $this->ipaddress = $_SERVER['HTTP_X_FORWARDED'];
            else if(isset($_SERVER['HTTP_FORWARDED_FOR']))
                $this->ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
            else if(isset($_SERVER['HTTP_FORWARDED']))
                $this->ipaddress = $_SERVER['HTTP_FORWARDED'];
            else if(isset($_SERVER['REMOTE_ADDR']))
                $this->ipaddress = $_SERVER['REMOTE_ADDR'];
            else
                $this->ipaddress = 'UNKNOWN';
        }

        private function createToken(){
            return md5(uniqid(rand(), true));
        }

        public function member_login(){
            $token = $this->createToken();
            $_SESSION['login'] = array( 'token' => $token );
            error_log($_SESSION['login']['token']);
            $this->log_account_login($token);

            $search_token_result = $this->mysqli_connect->query("SELECT `id_member` FROM `log_account_login` WHERE `token` = '$token'");
            // if($this->remember == 'on'){
                setcookie('login[token]', $token, time()+31556926, '/', $_SERVER['HTTP_HOST']);
            // }
        }

        private function log_account_login($token){
            $secure_newtoken = md5($token);

            $id_member = $this->member_row['id_member'];
            $log_account_login_sql = "INSERT INTO `log_account_login`(`id_member`, `date`, `ip`, `location`, `token`) VALUES ('$id_member', CURRENT_TIMESTAMP, '$this->ipaddress', '', '$secure_newtoken')";
            // error_log($log_account_login_sql);
            $log_account_login_result = $this->mysqli_connect->query($log_account_login_sql);
        }

        private function hashPassword($password){
            return md5($password);
        }

        public function password_authenticate($password){
            return validate_password($this->hashPassword($password), $this->password);
        }
    }

    class memberDetail{
        protected $mysqli_connect;

        public function __construct(){
            $objMysqlConnect = new mysqlConnect();
            $this->mysqli_connect = $objMysqlConnect->getConnection();
        }
        public function getDetail($id_member) {
            $member_result = $this->mysqli_connect->query("SELECT * FROM `account` WHERE `id_member` = '$id_member'");
            return $member_result->fetch_assoc();
        }
        public function thisLogin(){
            
            if(isset($_COOKIE['login']['token'])){
                if(isset($_SESSION['login']['token'])){
                    unset($_SESSION['login']['token']);
                }
                $_SESSION = array();
                $_SESSION['login']['token'] = $_COOKIE['login']['token'];
            }

            if(isset($_SESSION['login']['token'])){
                if(empty($_SESSION['member'])){
                    $token = md5($_SESSION['login']['token']);
                    $search_token_result = $this->mysqli_connect->query("SELECT `id_member` FROM `log_account_login` WHERE `token` = '$token'");
                    $search_token_row = $search_token_result->fetch_array();

                    if(isset($search_token_row)){
                        $_SESSION['member']['id_member'] = $search_token_row['id_member'];
                        return $this->getDetail($_SESSION['member']['id_member']);
                    }
                }

                else{
                    return $this->getDetail($_SESSION['member']['id_member']);
                }
            }

            return false;
        }
    }

    class checkPermission{
        private $mysqli_connect;

        public function __construct()
        {
            $objMysqlConnect = new mysqlConnect();
            $this->mysqli_connect = $objMysqlConnect->getConnection();
        }

        public function permissionAllow($member_row,$id_permission){
            $permission_allow_result = $this->mysqli_connect->query("SELECT * FROM `permission_allow` WHERE `id_permission` = '$id_permission' AND `id_group` = '$member_row[id_group]'");
            $permission_allow_row = $permission_allow_result->fetch_assoc();

            if(empty($permission_allow_row)){
                return false;
            }
            else if($permission_allow_row['allow'] == true){
                return true;
            }
            else return false;
            
        }
    }
    
    class queryAccount{
        private $mysqli_connect;

        public function __construct()
        {
            $objMysqlConnect = new mysqlConnect();
            $this->mysqli_connect = $objMysqlConnect->getConnection();
        }
        public function account(){
            return $this->mysqli_connect->query("SELECT * FROM `account`");
        }
        public function account_group(){
            return $this->mysqli_connect->query("SELECT * FROM `account_group`");
        }
    }

    class queryPermission{
        private $mysqli_connect;

        public function __construct()
        {
            $objMysqlConnect = new mysqlConnect();
            $this->mysqli_connect = $objMysqlConnect->getConnection();
        }
        public function permission(){
            return $this->mysqli_connect->query("SELECT * FROM `permission`");
        }

        public function permissionAllowByIdGroup($id_group,$id_permission){
            return $this->mysqli_connect->query("SELECT * FROM `permission_allow` WHERE `id_group`='$id_group' AND `id_permission`='$id_permission'");
        }
    }

    