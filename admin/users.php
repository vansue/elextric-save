<?php
    $title = "Quản lý thành viên | Elextronic";
	include('inc/header.php');
	require_once('../inc/functions.php');
	require_once('../inc/mysqli_connect.php');
	include('inc/first-sidebar.php');
?>

<!-- VALIDATE BIẾN $_GET -->
<?php
    //Phân trang danh mục bài viết
    //Đặt số trang muốn hiển thị ra trình duyệt
    $display = 5;
    //Xác định vị trí bắt đầu
    $start = (isset($_GET['s']) && filter_var($_GET['s'], FILTER_VALIDATE_INT, array('min_range' => 1))) ? $_GET['s'] : 0;
	if (isset($_GET['euid'])) {
		if(filter_var($_GET['euid'], FILTER_VALIDATE_INT, array('min_range'=>1))) {
			include('inc/edit-users.php');
		} else {
			redirect_to('admin/index.php');
		}
	} elseif(isset($_GET['duid'])) {
		if(filter_var($_GET['duid'], FILTER_VALIDATE_INT, array('min_range'=>1))) {
			include('inc/delete-users.php');
		} else {
			redirect_to('admin/index.php');
		}
	} else {
		include('inc/add-users.php');
	}
?>

<!-- HIỂN THỊ DANH SÁCH THÀNH VIÊN -->
	<div class="title-content">
		<p>Danh sách thành viên</p>
	</div>
	<table>
    	<thead>
    		<tr>
    			<th><a href="users.php?sort=name">Họ tên</a></th>
    			<th><a href="users.php?sort=email">Email</a></th>
                <th><a href="users.php?sort=level">Level</a></th>
                <th>Địa chỉ</th>
                <th>Điện thoại</th>
                <th>Edit</th>
                <th>Delete</th>
    		</tr>
    	</thead>
    	<tbody>
    	<?php
    		//Sắp xếp theo thứ tự của table head
    		if (isset($_GET['sort'])) {
    			switch ($_GET['sort']) {
    				case 'name':
    					$order_by = 'name';
    					break;
    				case 'email':
    					$order_by = 'email';
    					break;
    				case 'level':
    					$order_by = 'user_level';
    					break;
    				default:
    					$order_by = 'name';
    					break;
    			}
    		} else {
    			$order_by = 'name';
    		}

    		//Truy xuất CSDL lấy thông tin người dùng
            $users = fetch_users($order_by);

            //In kết quả ra trình duyệt
            foreach ($users as $user) :
        ?>

            <tr>
                <td><?php echo $user['name']; ?></td>
                <td><?php echo $user['email']; ?></td>
                <td><?php echo $user['user_level']; ?></td>
                <td><?php echo $user['address']; ?></td>
                <td><?php echo $user['phone']; ?></td>
                <td class='edit'><a href="users.php?euid=<?php echo $user['user_id']; ?>"><img src="../images/b_edit.png" alt="edit"></a></td>
                <td class='delete'><a href="users.php?duid=<?php echo $user['user_id']; ?>"><img src="../images/b_drop.png" alt="drop"></a></td>
            </tr>

        <?php endforeach; ?>
    	</tbody>
    </table>
</div><!--end #main-content-->
<?php
	include('inc/second-sidebar.php');
	include('inc/footer.php');
?>