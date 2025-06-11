<?php
/**
 * Created by PhpStorm.
 * User: Nuttaphon
 * Date: 14/5/2561
 * Time: 0:11
 */
function member_group_name($id_group){
    global $mysqli_connect;
    $member_group_sql = "SELECT * FROM `account_group` where `id_group` = '$id_group'";
    $member_group_result = $mysqli_connect->query($member_group_sql);
    return $member_group_row = $member_group_result->fetch_assoc();
}

if(isset($_GET['action'])){
    if(!isset($_SESSION)){ session_start(); }
    require_once '../config.php';
    require_once '../source/member.php';
    require_once '../source/permission.php';
    require_once '../source/secure/InputFilter.php';
    $member_row = member_row();

    if($_GET['action'] == 'edit_profile') {
        $id_group = $member_row['id_group'];
        $member_group_name = member_group_name($id_group);
        echo '
            <div id="custom-content" class="zoom-anim-dialog modal-block modal-block-lg">
                <section class="panel">
                    <header class="panel-heading">
                        <h2 class="panel-title">แก้ไขข้อมูลส่วนตัว</h2>
                    </header>
                    <form id="edit_profile" class="form-horizontal mb-lg" novalidate="novalidate">
                        <div class="panel-body">
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label class="col-sm-3 control-label">กลุ่ม</label>
                                    <div class="col-sm-9" style="padding-top:0.5em;padding-bottom:0.5em;">
                                        <span>' . $member_group_name['group_name'] . '</span>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-3 control-label">Username</label>
                                    <div class="col-sm-9" style="padding-top:0.5em;">
                                        <input name="id_member" class="form-control" value="' . $member_row['id_member'] . '" type="hidden" >
                                        <input name="username" class="form-control" placeholder="Username ผู้ใช้" value="' . $member_row['username'] . '" readonly="readonly">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-3 control-label">ชื่อ</label>
                                    <div class="col-sm-9" style="padding-top:0.5em;padding-bottom:0.5em;">
                                        <input name="name" class="form-control" placeholder="ตั้งชื่อผู้ใช้งาน" value="' . $member_row['name'] . '">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-3 control-label">อีเมล</label>
                                    <div class="col-sm-9" style="padding-top:0.5em;padding-bottom:0.5em;">
                                        <input name="email" class="form-control" placeholder="อีเมล์" value="' . $member_row['email'] . '">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-3 control-label">เปลี่ยนรหัสผ่าน</label>
                                    <div class="col-sm-9" style="padding-top:0.5em;padding-bottom:0.5em;">
                                        <input name="password" type="password" class="form-control">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-3 control-label">พิมพ์รหัสผ่านอีกครั้ง<br>(พิมพ์เหมือนด้านบน)</label>
                                    <div class="col-sm-9" style="padding-top:0.5em;padding-bottom:0.5em;">
                                        <input name="password_again" type="password" class="form-control">
                                        <span id="warn_pass" class="label label-warning"></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <footer class="panel-footer">
                            <div class="row">
                                <div class="col-md-12 text-right">
                                    <button class="btn btn-primary modal-confirm">Submit</button>
                                    <button class="btn btn-default modal-dismiss">Cancel</button>
                                </div>
                            </div>
                        </footer>
                    </form>
                </section>
            </div>
        ';
    }
}

if(isset($_POST['action'])){
    if(!isset($_SESSION)){ session_start(); }
    require_once '../config.php';
    require_once '../source/member.php';
    require_once '../source/permission.php';
    require_once '../source/secure/InputFilter.php';
    require_once '../source/secure/PasswordHash.php';

    $member_row = member_row();

    if($_POST['action'] == 'edit_profile'){
        $id_member = $_POST['id_member'];
        $name = InputFilter($_POST['name']);
        $email = EmailFilter($_POST['email']);
        $password = $_POST['password'];
        $password_again = $_POST['password_again'];

        if($name!=''){
            if($password!=''){
                if($password == $password_again){
                    $hashpassword = create_hash(md5($password));
                    $update_account_sql = "UPDATE `account` SET `password`='$hashpassword',`name`='$name',`email`='$email' WHERE `id_member`= '$member_row[id_member]'";
                    $update_account_result = $mysqli_connect->query($update_account_sql);
                    $return_data = array('success');
                    echo json_encode($return_data);
                }
                else{
                    $return_data = array('password_different');
                    echo json_encode($return_data);
                }
            }
            else{
                $update_account_sql = "UPDATE `account` SET `name`='$name',`email`='$email' WHERE `id_member`= '$member_row[id_member]'";
                $update_account_result = $mysqli_connect->query($update_account_sql);
                $return_data = array('success');
                echo json_encode($return_data);
            }
        }
    }
}