<?php
//    if(isset($_GET['p']) && isset($_GET['s'])){
//        $page_detail['page'] = $_GET['p'];
//        $page_detail['sub'] = $_GET['s'];
//
//        request_page_detail($page_detail['page'],$page_detail['sub']);
//    }

    function request_page_detail($page,$sub){
        $page_url = search_file_page_content($page);
        include 'page/'.$page_url;
        return $page_detail;
    }

    function search_file_page_content($page){
        if($page == '' OR $page == 'dashboard'){
            return 'dashboard.php';
        }
        else if($page == 'data'){
            return 'data.php';
        }
        else if($page == 'member'){
            return 'member.php';
        }
        else if($page == 'email_filter'){
            return 'email_filter.php';
        }
        else if($page == 'importexport'){
            return 'importexport.php';
        }
    }