<?php
    require_once 'source/model/table_class.php';
    $objTable = new queryTable;

?>

<div class="main-content app-content mt-0">
    <div class="side-app">

        <!-- CONTAINER -->
        <div class="main-container container-fluid">

            <!-- PAGE-HEADER -->
            <div class="page-header">
                <h1 class="page-title"><?php echo $page_detail['name']; ?></h1>
                <div>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="javascript:void(0)"><?php echo $page_detail['subname']; ?></a></li>
                        <li class="breadcrumb-item active" aria-current="page"><?php echo $page_detail['name']; ?></li>
                    </ol>
                </div>

            </div>
            <!-- PAGE-HEADER END -->

            <!-- Row -->
            <div class="row row-sm">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">member</h3>
                        </div>
                        <div class="card-body">

                        <div class="control-panel">
                                <div class="row">
                                    <div class="col-sm-12 text-left">
                                        <a href="#" id="refresh_button" class="btn btn-success"><i class="fa fa-refresh"></i> Refresh</a>
                                        <a href="source/member.php?action=add_account" id="add_account" class="btn btn-success"><i class="fa fa-plus"></i> เพิ่มข้อมูล</a>
                                    </div>
                                </div>
                            </div>
                            <table class="table table-bordered table-striped mb-none" id="datatable-account">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th >กลุ่ม</th>
                                        <th>Username</th>
                                        <th>Name</th>
                                        <th>ดำเนินการ</th>
                                    </tr>
                                </thead>
                            </table>

                        </div>
                    </div>
                </div>
            </div>
            <!-- End Row -->
        </div>
        <!-- CONTAINER CLOSED -->

    </div>
</div>