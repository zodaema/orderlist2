<?php
/**
 * Created by PhpStorm.
 * User: Nuttaphon
 * Date: 26/1/2561
 * Time: 17:07
 */

define( 'DB_NAME', 'rebello_orderlist');
define( 'DB_USER', 'root');
define( 'DB_PASSWORD', 'root');
define( 'DB_HOST', 'localhost' );
define( 'DB_CHARSET', 'utf8' );
define( 'DB_COLLATE', '' );

class domain{
    private static $protocol = NULL;
    public static $domain_directory = "/orderlist2";
    public static $domain;

    public static function getDomain(){
        if (isset($_SERVER['HTTPS'])) {
            self::$protocol = ($_SERVER['HTTPS'] && $_SERVER['HTTPS'] != "off") ? "https" : "http";
        } else {
            self::$protocol = 'http';
        }
        self::$domain = self::$protocol . "://" . $_SERVER['HTTP_HOST'] . self::$domain_directory;
        return self::$domain;
    }
}
class mysqlConnect{
    private $mysqli_connect = NULL;

    public function __construct()
    {
        $this->mysqli_connect = new mysqli(constant('DB_HOST'), constant('DB_USER'), constant('DB_PASSWORD'), constant('DB_NAME'));
        if ($this->mysqli_connect->connect_error) {
            die('Error : ('. $this->mysqli_connect->connect_errno .') '. $this->mysqli_connect->connect_error);
        }
        $this->mysqli_connect -> set_charset('utf8');
    }

    public function getConnection(){
        return $this->mysqli_connect;
    }
}

// Check Protocol
if(isset($_SERVER['HTTPS'])){
    $protocol = ($_SERVER['HTTPS'] && $_SERVER['HTTPS'] != "off") ? "https" : "http";
}
else{
    $protocol = 'http';
}

// Edit domain & Mysql
$domain_directory = "/orderlist2"; // If not set blank
$domain = $protocol . "://" . $_SERVER['HTTP_HOST'] . $domain_directory;
$db_username = 'root';
$db_password = 'root';
$db_name = 'rebello_orderlist';
$db_host = 'localhost';

$mysqli_connect = new mysqli($db_host, $db_username, $db_password, $db_name);
if ($mysqli_connect->connect_error) {
    die('Error : ('. $mysqli_connect->connect_errno .') '. $mysqli_connect->connect_error);
}
$mysqli_connect -> set_charset('utf8');







try {
    $pdo = new PDO("mysql:host=".constant('DB_HOST').";dbname=".constant('DB_NAME').";charset=".constant('DB_CHARSET'), constant('DB_USER'), constant('DB_PASSWORD'));
    // set the PDO error mode to exception
    $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    //echo "Connected successfully";
  } catch(PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
    exit;
  }