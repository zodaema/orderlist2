<?php
	$global_jquery['url'] = domain::getDomain().'/assets/javascripts/data/';
	$global_jquery['file'] = 'data.js';
	$page_detail['global']['jquery'] = $global_jquery;

	include 'global_function.php';
	require_once 'source/data.php';

	//	Page Detail
	$page_detail['name'] = 'ฐานข้อมูล';
		//	data All
		if($sub == ''){
			$page_detail['subname'] = 'ฐานข้อมูลทั้งหมด';
			$page_detail['css'][1] = 'data.css';
			$page_detail['jquery'][1] = 'data_all.js';
		}

	// Content
	if(!function_exists("content_request")) {
		function content_request($s){
			//	data All
			if($s == ''){
				echo '
				<section class="panel">
					<header class="panel-heading">
		
						<h2 class="panel-title">ตารางรายชื่อทั้งหมด</h2>
					</header>
					<div class="panel-body">
						<div class="control-panel">
                            <div class="row">
                                <div class="col-sm-12 text-left">
                                    <a href="#" id="advance_search_button" class="btn btn-primary"><i class="fa fa-search"></i> ค้นหาแบบละเอียด</a>
                                    <a href="#" id="refresh_button" class="btn btn-success"><i class="fa fa-refresh"></i> Refresh</a>
                                </div>
                            </div>
						</div>
						
						<div class="advance-search hide">
						    <form id="form-advance-search">
						        <div class="row">
						            <div class="col-sm-6">
						                <div class="col-sm-6">
						                    <input type="text" class="form-control" placeholder="ชื่อ" id="name" data-column="1">
						                </div>
						                <div class="col-sm-6">
						                    <input type="text" class="form-control" placeholder="นามสกุล" id="lastname" data-column="2">
						                </div>
                                    </div>
                                    <div class="col-sm-6">
						                <div class="col-sm-6">
						                    <input type="text" class="form-control" placeholder="รหัสนักศึกษา" id="id_member" data-column="0">
						                </div>
						                <div class="col-sm-6">
						                    <select class="form-control" id="year" data-column="5">
                                                <option value="">ปีที่เข้าศึกษา</option>';
                                                for($i= date("Y")+543;$i>=2510;$i--){
                                                    $value = $i-2500;
                                                    echo'<option value="'.$value.'">'.$i.'</option>';
                                                }
                                                echo'
                                            </select>
						                </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="col-sm-6">
                                            <select class="form-control populate" id="faculty" style="width: 100%" data-plugin-selectTwo data-column="4">
                                                <optgroup label="กรุณาเลือกคณะ">
                                                    <option value="">คณะ</option>
                                                        ';
                                                        $data_faculty_result = data_faculty_result();
                                                        while($data_faculty_row = $data_faculty_result->fetch_assoc()){
                                                            echo '<option value="'.$data_faculty_row['faculty'].'" >'.$data_faculty_row['faculty'].'</option>';
                                                        }
                                                        echo'
                                                </optgroup>
                                            </select>
                                        </div>
                                        <div class="col-sm-6">
                                            <input type="text" class="form-control" placeholder="อีเมล์" id="email">
                                        </div>
									</div>
									<div class="col-sm-6">
                                        <div class="col-sm-6">
                                            <select class="form-control" id="status" data-column="6">
                                                <option value="">สถานะของข้อมูล</option>
                                ';
                                $data_type_result = data_type_result();
                                while($data_type_row = $data_type_result->fetch_assoc()){
                                    echo '<option value="'.$data_type_row['id_type'].'" >'.$data_type_row['type'].'</option>';
                                }
                                echo'
                                            </select>
                                        </div>
                                        <div class="col-sm-6">
                                            <a href="#" id="reset_advance_search" class="btn btn-primary btn-block">ล้างข้อมูล</a>
                                        </div>
									</div>
                                </div>
                            </form>
                        </div>
		
						<table class="table table-bdataed table-striped mb-none" id="table-data-all">
							<thead>
								<tr>
									<th>รหัสนักศึกษา</th>
									<th>ชื่อ</th>
									<th>นามสกุล</th>
									<th width="10%">เบอร์โทร</th>
									<th>คณะ</th>
									<th>อีเมล์</th>
									<th>สถานะ</th>
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