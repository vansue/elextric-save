<?php
/**************************** XÓA DANH MỤC BÀI VIẾT **********************************/
$dcid = $_GET['dcid'];
$ecid = NULL;
$q = "DELETE FROM p_categories WHERE cat_id = {$dcid} LIMIT 1";
$r = mysqli_query($dbc, $q);
	confirm_query($r, $q);
	if(mysqli_affected_rows($dbc) == 1) {
		redirect_to('admin/p-categories.php?msg=3');
	} else {
		redirect_to('admin/p-categories.php?msg=1');
	}

