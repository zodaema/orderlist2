<?php
/**
 * Created by PhpStorm.
 * User: Nuttaphon
 * Date: 27/3/2561
 * Time: 16:04
 */

$global_jquery['url'] = domain::getDomain().'/assets/javascripts/emailfilter/';
$global_jquery['file'] = 'all.js';
$page_detail['global']['jquery'] = $global_jquery;

include 'global_function.php';

//	Page Detail
$page_detail['name'] = 'ระบบจัดการอีเมล์';
//	data All
if($sub == ''){
    $page_detail['subname'] = 'จัดการตัวกรองอีเมล์';
    $page_detail['css'][1] = 'emailfilter.css';
    $page_detail['jquery'][1] = 'emailfilter.js';

}

// Content
if(!function_exists("content_request")) {
    function content_request($s){
        //	data All
        if($s == ''){
            echo '
				<section class="panel">
					<header class="panel-heading">
		
						<h2 class="panel-title">จัดการตัวกรองอีเมล์</h2>
					</header>
					<div class="panel-body">
						<div class="control-panel">
                            <div class="row">
                                <div class="col-sm-12 text-left">
                                    <a href="#" id="refresh_button" class="btn btn-success"><i class="fa fa-refresh"></i> Refresh</a>
                                    <a href="source/emailfilter_add.php" id="email_filter_add" class="btn btn-success"><i class="fa fa-plus"></i> เพิ่มข้อมูล</a>
                                </div>
                            </div>
						</div>
		
						<table class="table table-bdataed table-striped mb-none" id="email_filter_table">
							<thead>
								<tr>
									<th>คีย์เวิร์ด</th>
									<th>แก้ไขเป็น</th>
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