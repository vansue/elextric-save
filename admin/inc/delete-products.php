<?php
/**************************** XÓA SẢN PHẨM **********************************/
$dpid = $_GET['dpid'];
$epid = NULL;
$q = "DELETE FROM products WHERE pro_id = {$dpid} LIMIT 1";
$r = mysqli_query($dbc, $q);
	confirm_query($r, $q);
	if(mysqli_affected_rows($dbc) == 1) {
		redirect_to('admin/view-products.php?pcid='.$pcid.'&msg=1');
	} else {
		redirect_to('admin/view-products.php?pcid='.$pcid.'&msg=2');
	}