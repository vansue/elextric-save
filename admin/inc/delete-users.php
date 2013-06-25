<?php
/**************************** XÓA DANH MỤC BÀI VIẾT **********************************/
$duid = $_GET['duid'];
$euid = NULL;
$q = "DELETE FROM users WHERE user_id = {$duid} LIMIT 1";
$r = mysqli_query($dbc, $q);
	confirm_query($r, $q);
	if(mysqli_affected_rows($dbc) == 1) {
		redirect_to('admin/users.php?msg=3');
	} else {
		redirect_to('admin/users.php?msg=1');
	}

