<?php
    $title = "Quản lý danh mục sản phẩm | Elextronic";
	include('inc/header.php');
	require_once('../inc/functions.php');
	require_once('../inc/mysqli_connect.php');
	include('inc/first-sidebar.php');
?>

<!-- VALIDATE BIẾN $_GET -->
<?php
	if (isset($_GET['ecid'])) {
		if(filter_var($_GET['ecid'], FILTER_VALIDATE_INT, array('min_range'=>1))) {
			include('inc/edit-p-categories.php');
		} else {
			redirect_to('admin/index.php');
		}
	} elseif(isset($_GET['dcid'])) {
		if(filter_var($_GET['dcid'], FILTER_VALIDATE_INT, array('min_range'=>1))) {
			include('inc/delete-p-categories.php');
		} else {
			redirect_to('admin/index.php');
		}
	} else {
		include('inc/add-p-categories.php');
	}
?>

<!-- HIỂN THỊ DANH MỤC SẢN PHẨM -->
	<div class="title-content">
		<p>Danh mục sản phẩm</p>
	</div>
	<table>
    	<thead>
    		<tr>
    			<th><a href="p-categories.php?sort=cat">Tên danh mục</a></th>
    			<th><a href="p-categories.php?sort=pos">Vị trí</a></th>
    			<th><a href="p-categories.php?sort=by">Người tạo</a></th>
                <th>Edit</th>
                <th>Delete</th>
    		</tr>
    	</thead>
    	<tbody>
    	<?php
    		//Sắp xếp theo thứ tự của table head
    		if (isset($_GET['sort'])) {
    			switch ($_GET['sort']) {
    				case 'cat':
    					$order_by = 'cat_name';
    					break;
    				case 'pos':
    					$order_by = 'position';
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
    		//Truy xuất CSDL để hiển thị categories
    		$q = "SELECT c.cat_id, c.cat_name, c.position, c.user_id, CONCAT_WS(' ', first_name, last_name) AS name ";
    		$q .= " FROM p_categories AS c ";
    		$q .= " JOIN users AS u USING(user_id) ";
    		$q .= " ORDER BY {$order_by} ASC";
    		$r = mysqli_query($dbc, $q);
    			confirm_query($r, $q);
    		while ($cats = mysqli_fetch_array($r, MYSQLI_ASSOC)) :
    	?>
            <tr>
                <td><?php echo $cats['cat_name']; ?></td>
                <td><?php echo $cats['position']; ?></td>
                <td><?php echo $cats['name']; ?></td>
                <td class='edit'><a href="p-categories.php?ecid=<?php echo $cats['cat_id']; ?>"><img src="../images/b_edit.png" alt="edit"></a></td>
                <td class='delete'><a href="p-categories.php?dcid=<?php echo $cats['cat_id']; ?>"><img src="../images/b_drop.png" alt="drop"></a></td>
            </tr>
        <?php endwhile; ?>
    	</tbody>
    </table>
</div><!--end #main-content-->
<?php
	include('inc/second-sidebar.php');
	include('inc/footer.php');
?>