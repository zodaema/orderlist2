<?php
    class queryTable{
        public $mysqli_connect;

        public function __construct()
        {
            $objMysqlConnect = new mysqlConnect();
            $this->mysqli_connect = $objMysqlConnect->getConnection();
        }
        public function queryByName($table_name){
            return $this->mysqli_connect->query("SELECT * FROM `$table_name`");
        }
        public function queryByData($sql_data){
            return $this->mysqli_connect->query($sql_data);
        }
        public function search($table_name,$row_name,$value){
            return $this->mysqli_connect->query("SELECT * FROM `$table_name` WHERE `$row_name` = '$value'");
        }
        public function count($table_name,$row_name,$value){
            $count = mysqli_num_rows( $this->search($table_name,$row_name,$value));
            return $count; 
        }
    }

    // class queryAccount{
    //     private $mysqli_connect;

    //     public function __construct()
    //     {
    //         $objMysqlConnect = new mysqlConnect();
    //         $this->mysqli_connect = $objMysqlConnect->getConnection();
    //     }
    //     public function account(){
    //         return $this->mysqli_connect->query("SELECT * FROM `member`");
    //     }
    //     public function account_group(){
    //         return $this->mysqli_connect->query("SELECT * FROM `member_group`");
    //     }
    // }
    // class queryPermission{
    //     private $mysqli_connect;

    //     public function __construct()
    //     {
    //         $objMysqlConnect = new mysqlConnect();
    //         $this->mysqli_connect = $objMysqlConnect->getConnection();
    //     }
    //     public function permission(){
    //         return $this->mysqli_connect->query("SELECT * FROM `permission`");
    //     }

    //     public function permissionAllowByIdGroup($id_group,$id_permission){
    //         return $this->mysqli_connect->query("SELECT * FROM `permission_allow` WHERE `id_group`='$id_group' AND `id_permission`='$id_permission'");
    //     }
    // }
