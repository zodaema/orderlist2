<?php
    $global_jquery['url'] = domain::getDomain().'/assets/javascripts/member/';
    $global_jquery['file'] = 'member_all.js';
    $page_detail['global']['jquery'] = $global_jquery;

    include 'global_function.php';
    require_once 'source/member.php';

    //	Page Detail
    $page_detail['name'] = 'จัดการสมาชิก';
    //	Dashboard
    if($sub == ''){

        $page_detail['subname'] = 'รายชื่อสมาชิก';
        $page_detail['css'][1] = 'member.css';
        $page_detail['jquery'][1] = 'member.js';

    }
    else if($sub == 'group'){
        $page_detail['subname'] = 'จัดการกลุ่มสมาชิก';
        $page_detail['css'][1] = 'member.css';
        $page_detail['jquery'][1] = 'account_group.js';
    }

    // Content
    if(!function_exists("content_request")) {
        function content_request($s){
            //	Dashboard
            if($s == ''){
                echo '
                    <section class="panel">
                        <header class="panel-heading">
                            <h2 class="panel-title">รายชื่อสมาชิก</h2>
                        </header>
                        <div class="panel-body">
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
                    </section>';
            }
            else if($s == 'group'){
                echo '
                    <section class="panel">
                        <header class="panel-heading">
                            <h2 class="panel-title">จัดการกลุ่มสมาชิก</h2>
                        </header>
                        <div class="panel-body">
                            <div class="control-panel">
                                <div class="row">
                                    <div class="col-sm-12 text-left">
                                        <a href="#" id="refresh_button" class="btn btn-success"><i class="fa fa-refresh"></i> Refresh</a>
                                        <a href="source/member.php?action=add_account_group" id="add_account_group" class="btn btn-success"><i class="fa fa-plus"></i> เพิ่มข้อมูล</a>
                                    </div>
                                </div>
                            </div>
                            <table class="table table-bordered table-striped mb-none" id="datatable-account-group">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>ชื่อกลุ่ม</th>
                                        <th>รายละเอียด</th>
                                        <th>จำนวนสมาชิก</th>
                                        <th>ดำเนินการ</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </section>
                ';
            }
        }
    }

?>