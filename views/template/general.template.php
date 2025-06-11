<?php
    $objCsrf = new Route();

    function content_request($path,$page_detail){
        
        if(isset($page_detail['permission'])){
            $objMemberDetail = new memberDetail();
            $objPermissionCheck = new checkPermission();

            if($objPermissionCheck->permissionAllow($objMemberDetail->thisLogin(),$page_detail['permission'])){
                include dirname( __FILE__ ).'/../'.$path;
            }
            else{
                error_log('404');
                include dirname( __FILE__ ).'/../404.html';
                return 404;
            }
        }
        else {	
            include dirname( __FILE__ ).'/../'.$path;
        }
    }
?>

<!DOCTYPE html>
<html lang="en" dir="ltr" data-nav-layout="vertical" data-theme-mode="light" data-header-styles="light" data-menu-styles="light" data-toggled="close">

<head>
    <?php
        include dirname( __FILE__ ).'/../../views/template/head.template.php';

        if(isset($page_detail['css'])){
            css_request($page_detail['css']);
        }
    ?>
</head>

<body>

    <div class="page">
            
        <?php 
            include dirname( __FILE__ ).'/../../views/template/header.template.php';
            include dirname( __FILE__ ).'/../../views/template/sidebar-left.template.php';
        ?>

        <!-- start: page -->
        <?php content_request($path,$page_detail); ?>
        <!-- end: page -->

        <?php include dirname( __FILE__ ).'/../../views/template/sidebar-right.template.php'; ?>

        <!-- FOOTER -->
        <footer class="footer mt-auto py-3 text-center">
            <div class="container">
                <span class=""> Copyright © <span id="year"></span> <a
                        href="javascript:void(0);" class="text-primary">Sash</a>.
                    Designed with <span class="bi bi-heart-fill text-danger"></span> by <a href="javascript:void(0);">
                        <span class="text-primary">Spruko</span>
                    </a> All
                    rights
                    reserved
                </span>
            </div>
        </footer>
        <!-- FOOTER CLOSED -->
        <?php $objCsrf->set_csrf(); ?>
    </div>

<?php 

    include dirname( __FILE__ ).'/../../views/template/footer.template.php';
    if(isset($page_detail['jquery'])){
        jquery_request($page_detail['jquery']);
    }
?>

</body>

</html>
