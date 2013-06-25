<?php
    $title = "Danh mục sản phẩm | Elextronic";
    include('inc/header.php');
	require_once('../inc/functions.php');
	require_once('../inc/mysqli_connect.php');
	include('inc/first-sidebar.php');
?>
<!-- VALIDATE BIẾN $_GET -->
<?php

    //Phân trang danh mục sản phẩm
    //Đặt số trang muốn hiển thị ra trình duyệt
    $display = 5;
    //Xác định vị trí bắt đầu
    $start = (isset($_GET['s']) && filter_var($_GET['s'], FILTER_VALIDATE_INT, array('min_range' => 1))) ? $_GET['s'] : 0;

	if (isset($_GET['pcid']) && filter_var($_GET['pcid'], FILTER_VALIDATE_INT, array('min_range'=>1))) {
		$pcid = $_GET['pcid'];
        $q = "SELECT cat_name FROM p_categories WHERE cat_id={$pcid}";
        $r = mysqli_query($dbc, $q);
            confirm_query($r, $q);
        if(mysqli_num_rows($r) == 1) {
            list($cat_name) = mysqli_fetch_array($r, MYSQLI_NUM);
        } else {
            redirect_to('admin/login.php');
        }
	} else {
		redirect_to('admin/index.php');
	}
?>

<div id="main-content">
	<div class="title-content">
		<p>Danh mục sản phẩm: <?php if(isset($cat_name)) echo $cat_name; ?></p>
	</div>
<?php
    if(isset($_GET['msg'])) {
        $msg = $_GET['msg'];
        switch ($msg) {
            case '1':
                echo "<p class='notice success'>Xóa sản phẩm thành công.</p>";
                break;
            case '2':
                echo "<p class='notice'>Sản phẩm không tồn tại.</p>";
                break;
            case '3':
                echo "<p class='notice success'>Thêm mới sản phẩm thành công.</p>";
                break;
            case '4':
                echo "<p class='notice success'>Chỉnh sửa sản phẩm thành công.</p>";
                break;
            default:
                redirect_to('admin/index.php');
                break;
        }
    }
?>
	<div class="product-box">
		<img src="../images/product_box_top.gif" alt="" class="top-prod-box" />
		<div class="cen-prod-box">
			<a href="m-products.php?pcid=<?php echo $pcid; ?>"><img src="../images/news.png" alt=""></a>
			<h4><a href="m-products.php?pcid=<?php echo $pcid; ?>">Thêm mới sản phẩm</a></h4>
		</div>
		<img src="../images/product_box_bottom.gif" alt="" class="bot-prod-box" />
	</div><!--end .product-box-->

	<div class="title-content">
		<p>Danh sách sản phẩm</p>
	</div>
	<table>
    	<thead>
    		<tr>
    			<th><a href="view-products.php?sort=pro&pcid=<?php if(isset($pcid)) echo $pcid; ?>">Tên sản phẩm</a></th>
                <th>Giá</th>
                <th><a href="view-products.php?sort=pos&pcid=<?php if(isset($pcid)) echo $pcid; ?>">Vị trí</a></th>
    			<th><a href="view-products.php?sort=date&pcid=<?php if(isset($pcid)) echo $pcid; ?>">Ngày tạo</a></th>
    			<th><a href="view-products.php?sort=by&pcid=<?php if(isset($pcid)) echo $pcid; ?>">Người tạo</a></th>
                <th>Edit</th>
                <th>Delete</th>
    		</tr>
    	</thead>
    	<tbody>
		<?php
    		//Sắp xếp theo thứ tự của table head
    		if (isset($_GET['sort'])) {
    			switch ($_GET['sort']) {
    				case 'pro':
    					$order_by = 'pro_name';
    					break;
                    case 'pos':
                        $order_by = 'position';
                        break;
    				case 'date':
    					$order_by = 'post_on';
    					break;
    				case 'by':
    					$order_by = 'name';
    					break;
    				default:
    					$order_by = 'position';
    					break;
    			}
    		} else {
    			$order_by = 'position';
    		}
    		//Truy xuất CSDL để hiển thị products
    		$q = "SELECT p.pro_id, p.pro_name, p.price, p.position, p.post_on, p.cat_id, p.user_id, CONCAT_WS(' ', u.first_name, u.last_name) AS name ";
    		$q .= " FROM products AS p ";
    		$q .= " JOIN users AS u USING(user_id) ";
    		$q .= " WHERE cat_id = {$pcid} ";
    		$q .= " ORDER BY {$order_by} ASC ";
            $q .= " LIMIT {$start}, {$display}";
    		$r = mysqli_query($dbc, $q);
    			confirm_query($r, $q);
    		while ($products = mysqli_fetch_array($r, MYSQLI_ASSOC)) :
    	?>
            <tr>
                <td><?php echo $products['pro_name']; ?></td>
                <td><?php echo $products['price']; ?></td>
                <td><?php echo $products['position']; ?></td>
                <td><?php echo $products['post_on']; ?></td>
                <td><?php echo $products['name']; ?></td>
                <td class='edit'><a href="m-products.php?pcid=<?php echo $pcid; ?>&epid=<?php echo $products['pro_id']; ?>"><img src="../images/b_edit.png" alt="edit"></a></td>
                <td class='delete'><a href="m-products.php?pcid=<?php echo $pcid; ?>&dpid=<?php echo $products['pro_id']; ?>"><img src="../images/b_drop.png" alt="drop"></a></td>
            </tr>
        <?php endwhile; ?>
    	</tbody>
    </table>
    <?php pagination($pcid, $display, 'view-products.php', 'pcid', 'pro_id', 'products'); ?>
</div><!--end #main-content-->
<?php
	include('inc/second-sidebar.php');
	include('inc/footer.php');
?>