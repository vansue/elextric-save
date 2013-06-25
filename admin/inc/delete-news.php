<?php
/**************************** XÓA BÀI VIẾT **********************************/
$dpid = $_GET['dpid'];
$epid = NULL;
$q = "DELETE FROM pages WHERE page_id = {$dpid} LIMIT 1";
$r = mysqli_query($dbc, $q);
	confirm_query($r, $q);
	if(mysqli_affected_rows($dbc) == 1) {
		redirect_to('admin/view-news.php?ncid='.$ncid.'&msg=1');
	} else {
		redirect_to('admin/view-news.php?ncid='.$ncid.'&msg=2');
	}