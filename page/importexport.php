<?php
/**
 * Created by PhpStorm.
 * User: Nuttaphon
 * Date: 5/4/2561
 * Time: 20:38
 */

    $global_jquery['url'] = domain::getDomain().'/assets/javascripts/importexport/';
    $global_jquery['file'] = 'all.js';
    $page_detail['global']['jquery'] = $global_jquery;

    include 'global_function.php';
    require_once 'source/data.php';

    //	Page Detail
    $page_detail['name'] = 'Import & Export';
    //	data All
    if($sub == ''){
        $page_detail['subname'] = 'อิมพอร์ตข้อมูล และ เอกพอร์ตข้อมูล';
        $page_detail['css'][1] = 'importexport.css';
        $page_detail['jquery'][1] = 'importexport.js';
    }

    // Content
    if(!function_exists("content_request")) {
        function content_request($s){
            global $domain;
            //	data All
            if($s == ''){
                echo '
                    <section class="panel">
                        <header class="panel-heading">
            
                            <h2 class="panel-title">Import </h2>
                        </header>
                        <div class="panel-body">
                            <form action="'.$domain.'/source/import_csv.php" class="dropzone dz-square" id="dropzoneImportCsv"></form>
                            
                            <div id="csv-showup"></div>
                        </div>
                    </section>
                    
                    
                      <section class="panel-2">
                        <header class="panel-heading">
            
                            <h2 class="panel-title">Export </h2>
                        </header>
                        <div class="panel-body">
                            <form id="export-file">
                                <label class="control-label">กรุณาเลือกคอลลัมน์ที่ต้องการส่งออก : </label>
                                <div class="table-responsive">
                                    <table class="table table-hover mb-none">
                                        <thead>
                                            <tr>
                                                <th><select id="export" class="form-control" name="select-export[1]">
                                                    <option value="">เลือก</option>
                                                    <option value="id_user">รหัสนักศึกษา</option>
                                                    <option value="name">ชื่อ</option>
                                                    <option value="lastname">นามสกุล</option>
                                                    <option value="tel">เบอร์โทร</option>
                                                    <option value="faculty">คณะ</option>
                                                    <option value="email">อีเมล์</option>
                                                    <option value="id_type">ประเภทข้อมูล</option>
                                                  </select>
                                                </th>
                                                <th><select id="export" class="form-control" name="select-export[2]">
                                                    <option value="">เลือก</option>
                                                    <option value="id_user">รหัสนักศึกษา</option>
                                                    <option value="name">ชื่อ</option>
                                                    <option value="lastname">นามสกุล</option>
                                                    <option value="tel">เบอร์โทร</option>
                                                    <option value="faculty">คณะ</option>
                                                    <option value="email">อีเมล์</option>
                                                    <option value="id_type">ประเภทข้อมูล</option>
                                                  </select>
                                                </th>
                                                <th><select id="export" class="form-control" name="select-export[3]">
                                                    <option value="">เลือก</option>
                                                    <option value="id_user">รหัสนักศึกษา</option>
                                                    <option value="name">ชื่อ</option>
                                                    <option value="lastname">นามสกุล</option>
                                                    <option value="tel">เบอร์โทร</option>
                                                    <option value="faculty">คณะ</option>
                                                    <option value="email">อีเมล์</option>
                                                    <option value="id_type">ประเภทข้อมูล</option>
                                                  </select>
                                                </th>
                                                <th><select id="export" class="form-control" name="select-export[4]">
                                                    <option value="">เลือก</option>
                                                    <option value="id_user">รหัสนักศึกษา</option>
                                                    <option value="name">ชื่อ</option>
                                                    <option value="lastname">นามสกุล</option>
                                                    <option value="tel">เบอร์โทร</option>
                                                    <option value="faculty">คณะ</option>
                                                    <option value="email">อีเมล์</option>
                                                    <option value="id_type">ประเภทข้อมูล</option>
                                                  </select>
                                                </th>
                                                <th><select id="export" class="form-control" name="select-export[5]">
                                                    <option value="">เลือก</option>
                                                    <option value="id_user">รหัสนักศึกษา</option>
                                                    <option value="name">ชื่อ</option>
                                                    <option value="lastname">นามสกุล</option>
                                                    <option value="tel">เบอร์โทร</option>
                                                    <option value="faculty">คณะ</option>
                                                    <option value="email">อีเมล์</option>
                                                    <option value="id_type">ประเภทข้อมูล</option>
                                                  </select>
                                                </th>
                                                <th><select id="export" class="form-control" name="select-export[6]">
                                                    <option value="">เลือก</option>
                                                    <option value="id_user">รหัสนักศึกษา</option>
                                                    <option value="name">ชื่อ</option>
                                                    <option value="lastname">นามสกุล</option>
                                                    <option value="tel">เบอร์โทร</option>
                                                    <option value="faculty">คณะ</option>
                                                    <option value="email">อีเมล์</option>
                                                    <option value="id_type">ประเภทข้อมูล</option>
                                                  </select>
                                                </th>
                                                <th><select id="export" class="form-control" name="select-export[7]">
                                                    <option value="">เลือก</option>
                                                    <option value="id_user">รหัสนักศึกษา</option>
                                                    <option value="name">ชื่อ</option>
                                                    <option value="lastname">นามสกุล</option>
                                                    <option value="tel">เบอร์โทร</option>
                                                    <option value="faculty">คณะ</option>
                                                    <option value="email">อีเมล์</option>
                                                    <option value="id_type">ประเภทข้อมูล</option>
                                                  </select>
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody id="example">
                                        </tbody>
                                        
                                    </table>
                                </div>
                                
                                <div class="form-group" style="padding-top: 1.5em;">
                                    <label class="col-sm-2 control-label">ชนิดของข้อมูลที่ต้องการ : </label>
                                        
                                    <div class="col-sm-10">
                                        <div class="checkbox-custom checkbox-default">
                                            <input type="checkbox" id="available" name="available" checked>
                                            <label for="available">Available</label>
                                        </div>
                                        <div class="checkbox-custom checkbox-default">
                                            <input type="checkbox" id="unavailable" name="unavailable">
                                            <label for="unavailable">Unavailable</label>
                                        </div>
                                        <div class="checkbox-custom checkbox-default">
                                            <input type="checkbox" id="indeterminate" name="indeterminate">
                                            <label for="indeterminate">Indeterminate</label>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="form-group" style="padding-bottom: 1.5em;">
                                    <label class="col-sm-2 control-label">คณะที่ต้องการ : </label>
                                        
                                    <div class="col-sm-10">
                                        <select data-plugin-selectTwo class="form-control populate" name="faculty">
                                            <optgroup label="กรุณาเลือกคณะ">
                                                <option value="all">ทั้งหมด</option>
                                ';
                                $data_faculty_result = data_faculty_result();
                                while($data_faculty_row = $data_faculty_result->fetch_assoc()){
                                    echo '<option value="'.$data_faculty_row['faculty'].'" >'.$data_faculty_row['faculty'].'</option>';
                                }
                                echo'
                                                </optgroup>
                                            </select>
                                    </div>
                                </div>
                                
                                <a href="#" id="export-button" class="mb-xs mt-xs mr-xs btn btn-primary btn-lg btn-block ladda-button" data-style="expand-right">
                                    <span class="ladda-label"><i class="fa fa-download"></i> Export</span>
                                </a>            
                            </form>
                        </div>
                    </section>
                ';
            }
        }
    }

?>