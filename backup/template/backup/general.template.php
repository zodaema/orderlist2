<?php
    $domain = domain::getDomain();

    $objPage = new route();
    $page_detail = $objPage->request_page_detail($page_request,$sub_page_request);

    if(empty($page_detail)){
        var_dump('EMPTY PAGE DETAIL');
        //404
    }
?>

<!doctype html>
<html lang="en" dir="ltr">
<head>
    <?php
        include 'template/head.template.php';

        if(isset($page_detail['css'])){
            css_request($page_detail['css']);
        }
    ?>
</head>

<body class="app sidebar-mini ltr light-mode">

    <div class="page">
        <div class="page-main">
            <?php include 'template/header.template.php'; ?>

            <?php include 'template/sidebar-left.template.php'; ?>

                <!-- start: page -->
                <?php content_request($sub_page_request); ?>
                <!-- end: page -->
        </div>

        <?php include 'template/sidebar-right.template.php'; ?>

        <!-- FOOTER -->
        <footer class="footer">
            <div class="container">
                <div class="row align-items-center flex-row-reverse">
                    <div class="col-md-12 col-sm-12 text-center">
                        Copyright © <span id="year"></span> <a href="javascript:void(0)">Sash</a>. Designed with <span class="fa fa-heart text-danger"></span> by <a href="javascript:void(0)"> Spruko </a> All rights reserved.
                    </div>
                </div>
            </div>
        </footer>
        <!-- FOOTER CLOSED -->
    </div>

<?php 

    include 'template/footer.template.php';
    if(isset($page_detail['jquery']) OR isset($page_detail['global']['jquery'])){
        jquery_request($page_detail['jquery'],$page_detail['global']['jquery']);
    }
?>

</body>

</html>
