<?php
	$title = "Thêm mới, chỉnh sửa sản phẩm | Elextronic";
	include('inc/header.php');
	require_once('../inc/functions.php');
	require_once('../inc/mysqli_connect.php');
	include('inc/first-sidebar.php');

	/*** VALIDATE BIẾN $_GET['ncid'] ***/

	if (isset($_GET['pcid']) && filter_var($_GET['pcid'], FILTER_VALIDATE_INT, array('min_range'=>1))) {
		$pcid = $_GET['pcid'];
		//Kiểm tra xem danh mục có tồn tại không
		$q = "SELECT cat_id FROM p_categories";
		$r = mysqli_query($dbc, $q);
			confirm_query($r, $q);
		while ($cats = mysqli_fetch_array($r, MYSQLI_ASSOC)) {
			if($cats['cat_id'] == $pcid) {
				$flag = TRUE;
				break;
			}
		}
		if(!isset($flag)) {redirect_to('admin/index.php');}
	} else {
		redirect_to('admin/index.php');
	}

	/*** VALIDATE BIẾN $_GET[epid, dpid] ***/

	if (isset($_GET['epid'])) {
		if(filter_var($_GET['epid'], FILTER_VALIDATE_INT, array('min_range'=>1))) {
			include('inc/edit-products.php');
		} else {
			redirect_to('admin/index.php');
		}
	} elseif(isset($_GET['dpid'])) {
		if(filter_var($_GET['dpid'], FILTER_VALIDATE_INT, array('min_range'=>1))) {
			include('inc/delete-products.php');
		} else {
			redirect_to('admin/index.php');
		}
	} else {
		include('inc/add-products.php');
	}

	include('inc/second-sidebar.php');
	include('inc/footer.php');
?>