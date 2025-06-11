<?php
	session_start();

// Require
	require_once __DIR__.'/config.php';
	require_once __DIR__.'/source/model/member_class.php';
    require_once __DIR__.'/source/model/table_class.php';
    require_once __DIR__.'/source/model/template_control_class.php';
    require_once __DIR__.'/source/global_function.php';

// Login Check
    // if(isset($_COOKIE['login'])){
    //     $_SESSION['login']['token'] = $_COOKIE['login'];
    // }
    // else if(empty($_SESSION['login'])){
    // 	header("location:login.php");
    // 	exit();
	// }

//	Permission Check
	$objMemberDetail = new memberDetail();
    if(!$objMemberDetail->thisLogin()){
        header("location:login.php");
    	exit();
    }
    // $objPermissionCheck = new checkPermission();
	// if($objPermissionCheck->permissionAllow($objMemberDetail->thisLogin(),1) == false){
	// 	header("location:../");
    // 	exit();
	// }
	// else{

//  Request Page
        // $p = 'dashboard'; // Main Page Default
        // $s = ''; // Sub Page Default
        // if(isset($_GET['p'])) {
        //     $p = $_GET['p'];
        // }
        // if(isset($_GET['s'])){
        //     $s = $_GET['s'];
        // }

        // $objPage->thisPage($p,$s);
        
        $objPage = new route();
        $objPage->storePath('','dashboard.php');
        $objPage->storePath('404','404.html');
        $objPage->storePath('dashboard','dashboard.php');
        $objPage->storePath('orders','orders.php');
        $objPage->storePath('shop','shop.php');
        $objPage->storePath('cart','cart.php');
        $objPage->storePath('member','member.php');
	// }

    