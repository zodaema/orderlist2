<?php
	function permission_allow($member_row,$id_permission){
		global $mysqli_connect;
		
		$permission_allow_sql = "SELECT * FROM `permission_allow` WHERE `id_permission` = '$id_permission' AND `id_group` = '$member_row[id_group]'";
		$permission_allow_result = $mysqli_connect->query($permission_allow_sql);
		$permission_allow_row = $permission_allow_result->fetch_assoc();
		
		if($permission_allow_row['allow'] == true){
			return true;
		}
		else return false;
	}
?>