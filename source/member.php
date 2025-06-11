<?php
    function account_result(){
        global $mysqli_connect;
        $account_sql = "SELECT * FROM `member`";
        return $account_result = $mysqli_connect->query($account_sql);
    }

    function member_group_result(){
        global $mysqli_connect;
        $member_group_sql = "SELECT * FROM `member_group`";
        return $member_group_result = $mysqli_connect->query($member_group_sql);
    }

    function permission_result(){
        global $mysqli_connect;
        $permission_sql = "SELECT * FROM `permission`";
        return $permission_result = $mysqli_connect->query($permission_sql);
    }

    function permission_allow_search_by_id_group_result($id_group,$id_permission){
        global $mysqli_connect;
        $permission_allow_search_by_id_group_sql = "SELECT * FROM `permission_allow` WHERE `id_group`='$id_group' AND `id_permission`='$id_permission'";
        return $permission_allow_search_by_id_group_result = $mysqli_connect->query($permission_allow_search_by_id_group_sql);
    }

    function member_row(){
        global $mysqli_connect;

        if(isset($_SESSION['login'])){
            if(empty($_SESSION['member'])){
                $token = md5($_SESSION['login']);
                $search_token_sql = "SELECT `id_member` FROM `log_account_login` WHERE `token` = '$token'";
                $search_token_result = $mysqli_connect->query($search_token_sql);
                $search_token_row = $search_token_result->fetch_array();

                if(isset($search_token_row)){
                    $_SESSION['member']['id_member'] = $search_token_row['id_member'];
                    $id_member = $_SESSION['member']['id_member'];
                    return member_row_query($id_member);
                }
            }

            else{
                $id_member = $_SESSION['member']['id_member'];
                return member_row_query($id_member);
            }
        }

        return false;
    }

    function account_group_by_id($id_member){
        global $mysqli_connect;

        $search_account_group_sql = "SELECT `group_name` FROM `member_group` WHERE `id_group` = '$id_member[id_group]'";
        $search_account_group_result = $mysqli_connect->query($search_account_group_sql);
        return $search_account_group_result->fetch_array();
    }

    function account_group_by_id_group($id_group){
        global $mysqli_connect;
        $member_group_by_id_group_sql = "SELECT * FROM `member_group` WHERE `id_group` = '$id_group'";
        return $member_group_by_id_group_result = $mysqli_connect->query($member_group_by_id_group_sql);
    }

    function account_result_by_id_member($id_member){
        global $mysqli_connect;
        $account_result_by_id_member_sql = "SELECT * FROM `member` WHERE `id_member` = '$id_member'";
        return $account_result_by_id_member_result = $mysqli_connect->query($account_result_by_id_member_sql);
    }

    function member_row_query($id_member) {
        global $mysqli_connect;
        $member_sql = "SELECT * FROM `member` WHERE `id_member` = '$id_member'";
        $member_result = $mysqli_connect->query($member_sql);
        return $member_result->fetch_assoc();
    }

    function account_row_by_email($email) {
        global $mysqli_connect;
        $account_row_by_email_sql = "SELECT * FROM `member` WHERE `email` = '$email'";
        $account_row_by_email_result = $mysqli_connect->query($account_row_by_email_sql);
        return $account_row_by_email_result->fetch_assoc();
    }

    function account_group_search_by_id_group($id_group){
        global $mysqli_connect;
        $account_group_search_by_id_group_sql = "SELECT * FROM `account` WHERE `id_group` = '$id_group'";
        return $account_group_search_by_id_group_result = $mysqli_connect->query($account_group_search_by_id_group_sql);
    }

    if(isset($_GET['action'])){
        if(!isset($_SESSION)){ session_start(); }
        require_once '../config.php';
        require_once '../source/model/member_class.php';
        require_once '../source/secure/InputFilter.php';
        $objMemberDetail = new memberDetail();
        $objCheckPermission = new checkPermission();
        
        if($_GET['action'] == 'datatable-account'){
            if($objCheckPermission->permissionAllow($objMemberDetail->thisLogin(),1)) {
                $ObjQueryAccount = new queryAccount();
                $account_result = $ObjQueryAccount->account();

                $item_output = array();
                while ($account_row = $account_result->fetch_assoc()) {
                    $data = $account_row;
                    $account_group_by_id_row = account_group_by_id($account_row);

                    $item_output[] = array(
                        'id_member' => $data['id_member'],
                        'group_name' => $account_group_by_id_row['group_name'],
                        'username' => $data['username'],
                        'name' => $data['name'],
                        'DT_RowId' => $data['id_member'],
                        'DT_RowClass' => 'data_member'
                    );
                }
                $output['data'] = $item_output;
                echo json_encode($output);
            }
        }

        if($_GET['action'] == 'datatable-account-group'){
            if($objCheckPermission->permissionAllow($objMemberDetail->thisLogin(),1)) {
                $ObjQueryAccount = new queryAccount();
                $member_group_result = $ObjQueryAccount->account_group();

                $item_output = array();
                while ($member_group_row = $member_group_result->fetch_assoc()) {
                    $data = $member_group_row;
                    $account_group_search_by_id_group_result = account_group_search_by_id_group($member_group_row['id_group']);
                    $count_member = mysqli_num_rows($account_group_search_by_id_group_result);

                    $item_output[] = array(
                        'id_group' => $data['id_group'],
                        'group_name' => $data['group_name'],
                        'detail' => $data['detail'],
                        'amount' => $count_member,
                        'DT_RowId' => $data['id_group'],
                        'DT_RowClass' => 'data_account_group'
                    );
                }
                $output['data'] = $item_output;
                echo json_encode($output);
            }
        }

        if($_GET['action'] == 'add_account'){
            echo'
            <div id="custom-content" class="zoom-anim-dialog modal-block modal-block-lg">
                <section class="panel">
                    <header class="panel-heading">
                        <h2 class="panel-title">เพิ่มสมาชิก</h2>
                    </header>
                    <form id="add_account" class="form-horizontal mb-lg" novalidate="novalidate">
                        <div class="panel-body">
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label class="col-sm-3 control-label">Username</label>
                                    <div class="col-sm-9" style="padding-top:0.5em;">
                                        <input name="username" class="form-control" placeholder="Username ผู้ใช้">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-3 control-label">ชื่อ</label>
                                    <div class="col-sm-9" style="padding-top:0.5em;padding-bottom:0.5em;">
                                        <input name="name" class="form-control" placeholder="ตั้งชื่อผู้ใช้งาน">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-3 control-label">กลุ่ม</label>
                                    <div class="col-sm-9" style="padding-top:0.5em;padding-bottom:0.5em;">
                                        <select name="id_group" class="form-control">
			';

            $member_group_result = member_group_result();
            while($member_group_row = $member_group_result->fetch_assoc()){
                echo '<option value="'.$member_group_row['id_group'].'">'.$member_group_row['group_name'].'</option>';
            }

            echo '
											</select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-3 control-label">รหัสผ่าน</label>
                                    <div class="col-sm-9" style="padding-top:0.5em;padding-bottom:0.5em;">
                                        <input name="password" type="password" class="form-control">
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

        if($_GET['action'] == 'edit_account'){
            $id_member = $_GET['id'];
            $account_result = account_result_by_id_member($id_member);
            $account_row = $account_result->fetch_assoc();
            echo'
            <div id="custom-content" class="zoom-anim-dialog modal-block modal-block-lg">
                <section class="panel">
                    <header class="panel-heading">
                        <h2 class="panel-title">แก้ไขสมาชิก</h2>
                    </header>
                    <form id="edit_account" class="form-horizontal mb-lg" novalidate="novalidate">
                        <div class="panel-body">
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label class="col-sm-3 control-label">Username</label>
                                    <div class="col-sm-9" style="padding-top:0.5em;">
                                        <input name="id_member" class="form-control" value="'.$account_row['id_member'].'" type="hidden" >
                                        <input name="username" class="form-control" value="'.$account_row['username'].'" readonly="readonly">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-3 control-label">ชื่อ</label>
                                    <div class="col-sm-9" style="padding-top:0.5em;padding-bottom:0.5em;">
                                        <input name="name" class="form-control" value="'.$account_row['name'].'" placeholder="ตั้งชื่อผู้ใช้งาน">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-3 control-label">กลุ่ม</label>
                                    <div class="col-sm-9" style="padding-top:0.5em;padding-bottom:0.5em;">
                                        <select name="id_group" class="form-control">
			';

            $member_group_result = member_group_result();
            while($member_group_row = $member_group_result->fetch_assoc()){
                echo '<option value="'.$member_group_row['id_group'].'"';
                if($account_row['id_group'] == $member_group_row['id_group']){
                    echo 'selected';
                }
                echo '>'.$member_group_row['group_name'].'</option>';
            }

            echo '
											</select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-3 control-label">เปลี่ยนรหัสผ่าน</label>
                                    <div class="col-sm-9" style="padding-top:0.5em;padding-bottom:0.5em;">
                                        <input name="newpassword" type="password" class="form-control">
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

        if($_GET['action'] == 'edit_account_group'){
            $id_group = $_GET['id'];
            $account_group_by_id_group_result = account_group_by_id_group($id_group);
            $account_group_by_id_group_row = $account_group_by_id_group_result->fetch_assoc();
            echo'
            <div id="custom-content" class="zoom-anim-dialog modal-block modal-block-lg">
                <section class="panel">
                    <header class="panel-heading">
                        <h2 class="panel-title">แก้ไขกลุ่มสมาชิก</h2>
                    </header>
                    <form id="edit_account_group" class="form-horizontal mb-lg" novalidate="novalidate">
                        <div class="panel-body">
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label class="col-sm-3 control-label">ชื่อกลุ่ม</label>
                                    <div class="col-sm-9" style="padding-top:0.5em;">
                                        <input name="id_group" class="form-control" value="'.$account_group_by_id_group_row['id_group'].'" type="hidden" >
                                        <input name="group_name" class="form-control" value="'.$account_group_by_id_group_row['group_name'].'" placeholder="ชื่อกลุ่มผู้ใช้ เช่น Administartor">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-3 control-label">รายละเอียดกลุ่ม</label>
                                    <div class="col-sm-9" style="padding-top:0.5em;padding-bottom:0.5em;">
                                        <input name="detail" class="form-control" value="'.$account_group_by_id_group_row['detail'].'" placeholder="คำอธิบายกลุ่ม">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-3 control-label">การอนุญาต</label>
                                    <div class="col-sm-9" style="padding-top:0.5em;padding-bottom:0.5em;">
                                        <table class="table mb-none">
                                            <thead>
                                                <tr>
                                                    <th>รายละเอียด</th>
                                                    <th>อนุญาต</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                    ';

            $permission_result = permission_result();
            while($permission_row = $permission_result->fetch_assoc()){
                $permission_allow_result = permission_allow_search_by_id_group_result($account_group_by_id_group_row['id_group'],$permission_row['id_permission']);
                $permission_allow_row = $permission_allow_result->fetch_assoc();
                echo'
                <tr>
                    <td>'.$permission_row['detail'].'</td>
                    <td>
                        <input name="permission['.$permission_row['id_permission'].']" type="checkbox" class="js-switch" ';
                if(!empty($permission_allow_row)){
                    if($permission_allow_row['allow'] == 1){
                        echo 'checked';
                    }
                }
                echo'/>
                    </td>
                </tr>
                ';
            }

                echo '
                                            </tbody>
                                        </table>
                                        <script>
                                            var elems = Array.prototype.slice.call(document.querySelectorAll(\'form#edit_account_group .js-switch\'));

                                            elems.forEach(function(html) {
                                              var switchery = new Switchery(html,{ size: \'small\' });
                                            });
                                        </script>
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

        if($_GET['action'] == 'add_account_group'){
            echo'
            <div id="custom-content" class="zoom-anim-dialog modal-block modal-block-lg">
                <section class="panel">
                    <header class="panel-heading">
                        <h2 class="panel-title">เพิ่มกลุ่มสมาชิก</h2>
                    </header>
                    <form id="add_account_group" class="form-horizontal mb-lg" novalidate="novalidate">
                        <div class="panel-body">
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label class="col-sm-3 control-label">ชื่อกลุ่ม</label>
                                    <div class="col-sm-9" style="padding-top:0.5em;">
                                        <input name="id_group" class="form-control" type="hidden" >
                                        <input name="group_name" class="form-control" placeholder="ชื่อกลุ่มผู้ใช้ เช่น Administartor" >
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-3 control-label">รายละเอียดกลุ่ม</label>
                                    <div class="col-sm-9" style="padding-top:0.5em;padding-bottom:0.5em;">
                                        <input name="detail" class="form-control" placeholder="คำอธิบายกลุ่ม">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-3 control-label">การอนุญาต</label>
                                    <div class="col-sm-9" style="padding-top:0.5em;padding-bottom:0.5em;">
                                        <table class="table mb-none">
                                            <thead>
                                                <tr>
                                                    <th>รายละเอียด</th>
                                                    <th>อนุญาต</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                    ';

            $permission_result = permission_result();
            while($permission_row = $permission_result->fetch_assoc()){
                echo'
                <tr>
                    <td>'.$permission_row['detail'].'</td>
                    <td>
                        <input name="permission['.$permission_row['id_permission'].']" type="checkbox" class="js-switch" />
                    </td>
                </tr>
                ';
            }

            echo '
                                            </tbody>
                                        </table>
                                        <script>
                                            var elems = Array.prototype.slice.call(document.querySelectorAll(\'form#add_account_group .js-switch\'));

                                            elems.forEach(function(html) {
                                              var switchery = new Switchery(html,{ size: \'small\' });
                                            });
                                        </script>
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

        if($_POST['action'] == 'add_account_action') {
            if (permission_allow($member_row, 10)) {
                if($_POST['username'] == '' || $_POST['name'] == '' || $_POST['password']==''){
                    $return_data = array('empty');
                    echo json_encode($return_data);
                }
                else{
                    $email = trim($_POST['email']);
                    $name = $_POST['name'];
                    $id_group = $_POST['id_group'];
                    $password = create_hash(md5($_POST['password']));

                    $account_row_by_email = account_row_by_email($email);
                    if(isset($account_row_by_email)){
                        $return_data = array('username used');
                        echo json_encode($return_data);
                    }
                    else{
                        $insert_account_sql = "INSERT INTO `member`(`email`, `name`,`id_group`,`password`) VALUES ('$email','$name','$id_group','$password')";
                        $insert_account_result = $mysqli_connect->query($insert_account_sql);

                        $return_data = array('success');
                        echo json_encode($return_data);
                    }
                }

            }
            else{
                $return_data = array('no permission');
                echo json_encode($return_data);
            }
        }

        if($_POST['action'] == 'edit_account_action') {
            if (permission_allow($member_row, 8)) {
                if($_POST['username'] == '' || $_POST['name'] == ''){
                    $return_data = array('empty');
                    echo json_encode($return_data);
                }
                else{
                    $id_member = trim($_POST['id_member']);
                    $username = trim($_POST['username']);
                    $name = $_POST['name'];
                    $id_group = $_POST['id_group'];

                    $member_row_query = member_row_query($id_member);

                    if($_POST['newpassword'] != ''){
                        $newpassword = create_hash(md5($_POST['newpassword']));
                    }
                    else{
                        $newpassword = $member_row_query['password'];
                    }

                    $update_account_sql = "UPDATE `account` SET `name`='$name',`id_group`='$id_group',`password`='$newpassword' WHERE `id_member`='$id_member'";
                    $update_account_result = $mysqli_connect->query($update_account_sql);

                    $return_data = array('success');
                    echo json_encode($return_data);
                }

            }
            else{
                $return_data = array('no permission');
                echo json_encode($return_data);
            }
        }

        if($_POST['action'] == 'add_account_group_action') {
            if (permission_allow($member_row, 11)) {
                if($_POST['group_name'] == '' || $_POST['detail'] == ''){
                    $return_data = array('empty');
                    echo json_encode($return_data);
                }
                else{
                    $group_name = trim($_POST['group_name']);
                    $detail = trim($_POST['detail']);
                    $insert_account_group_sql = "INSERT INTO `account_group`(`group_name`, `detail`) VALUES ('$group_name','$detail')";
                    $insert_account_group_result = $mysqli_connect->query($insert_account_group_sql);
                    $id_group = $mysqli_connect->insert_id;

                    $permission_result = permission_result();
                    while($permission_row = $permission_result->fetch_assoc()){
                        $id_permission = $permission_row['id_permission'];
                        $input_permission = array();
                        if(isset($_POST['permission'])){
                            $input_permission = $_POST['permission'];
                        }
                        if(isset($input_permission[$id_permission])){
                            if($input_permission[$id_permission] == 'on'){
                                $insert_permission_allow_sql = "INSERT INTO `permission_allow`(`id_permission`, `id_group`, `allow`) VALUES ('$id_permission','$id_group','1')";
                                $insert_permission_allow_result = $mysqli_connect->query($insert_permission_allow_sql);
                            }
                        }
                    }

                    $return_data = array('success');
                    echo json_encode($return_data);
                }

            }
            else{
                $return_data = array('no permission');
                echo json_encode($return_data);
            }
        }

        if($_POST['action'] == 'edit_account_group_action') {
            if (permission_allow($member_row, 9)) {
                if($_POST['group_name'] == '' || $_POST['detail'] == ''){
                    $return_data = array('empty');
                    echo json_encode($return_data);
                }
                else{
                    $id_group = trim($_POST['id_group']);
                    $group_name = trim($_POST['group_name']);
                    $detail = trim($_POST['detail']);
                    $update_account_group_sql = "UPDATE `account_group` SET `group_name`='$group_name',`detail`='$detail' WHERE `id_group`='$id_group'";
                    $update_account_group_result = $mysqli_connect->query($update_account_group_sql);

                    $permission_result = permission_result();
                    while($permission_row = $permission_result->fetch_assoc()){
                        $id_permission = $permission_row['id_permission'];
                        $input_permission = array();
                        if(isset($_POST['permission'])){
                            $input_permission = $_POST['permission'];
                        }
                        if(isset($input_permission[$id_permission])){
                            if($input_permission[$id_permission] == 'on'){
                                $search_permission_allow_sql = "SELECT * FROM `permission_allow` WHERE `id_group`='$id_group' AND `id_permission`='$id_permission'";
                                $search_permission_allow_result = $mysqli_connect->query($search_permission_allow_sql);
                                $search_permission_allow_row = $search_permission_allow_result->fetch_assoc();
                                if(!isset($search_permission_allow_row)){
                                    $insert_permission_allow_sql = "INSERT INTO `permission_allow`(`id_permission`, `id_group`, `allow`) VALUES ('$id_permission','$id_group','1')";
                                    $insert_permission_allow_result = $mysqli_connect->query($insert_permission_allow_sql);
                                }

                            }
                        }
                        else{
                            $search_permission_allow_sql = "SELECT * FROM `permission_allow` WHERE `id_group`='$id_group' AND `id_permission`='$id_permission'";
                            $search_permission_allow_result = $mysqli_connect->query($search_permission_allow_sql);
                            $search_permission_allow_row = $search_permission_allow_result->fetch_assoc();
                            if(isset($search_permission_allow_row)){
                                $delete_permission_allow_sql = "DELETE FROM `permission_allow` WHERE `id_group`='$id_group' AND `id_permission`='$id_permission'";
                                $delete_permission_allow_result = $mysqli_connect->query($delete_permission_allow_sql);
                            }
                        }
                    }

                    $return_data = array('success');
                    echo json_encode($return_data);
                }

            }
            else{
                $return_data = array('no permission');
                echo json_encode($return_data);
            }
        }

        if($_POST['action'] == 'del_account') {
            if (permission_allow($member_row, 6)) {
                $id_member = $_POST['id'];
                if(!member_row_query($id_member)){
                    $return_data = array('no account');
                    echo json_encode($return_data);
                }
                else{
                    $del_account_sql = "DELETE FROM `account` WHERE `id_member`='$id_member'";
                    $del_account_result = $mysqli_connect->query($del_account_sql);

                    $return_data = array('success');
                    echo json_encode($return_data);
                }
            }
            else{
                $return_data = array('no permission');
                echo json_encode($return_data);
            }
        }

        if($_POST['action'] == 'del_account_group') {
            if (permission_allow($member_row, 7)) {
                $id_group = $_POST['id'];
                if(!account_group_search_by_id_group($id_group)){
                    $return_data = array('no account group');
                    echo json_encode($return_data);
                }
                else{
                    $del_account_sql = "DELETE FROM `account_group` WHERE `id_group`='$id_group'";
                    $del_account_result = $mysqli_connect->query($del_account_sql);

                    $return_data = array('success');
                    echo json_encode($return_data);
                }
            }
            else{
                $return_data = array('no permission');
                echo json_encode($return_data);
            }
        }
    }

?>
