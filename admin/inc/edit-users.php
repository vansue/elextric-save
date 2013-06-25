<?php
	/**************************** EDIT DANH MỤC BÀI VIẾT **********************************/
	$euid = $_GET['euid'];
	$duid = NULL;

	/* VALIDATE FORM */
	if ($_SERVER['REQUEST_METHOD'] == 'POST') {//Nếu đúng -> Form đã được submit -> xử lý form
		$errors = array();
        // Trim all incoming data
        $trimmed = array_map('trim', $_POST);

        if(preg_match('/^[\w]{2,10}$/i', $trimmed['first_name'])) {
            $fn = $trimmed['first_name'];
        } else {
            $errors[] = "first_name";
        }

        if(preg_match('/^[\w]{2,10}$/i', $trimmed['last_name'])) {
            $ln = $trimmed['last_name'];
        } else {
            $errors[] = "last name";
        }

        if(filter_var($trimmed['email'],FILTER_VALIDATE_EMAIL)) {
            $e = $trimmed['email'];
        } else {
            $errors[] = "email";
        }

        if (preg_match('/^[\w\'.-]{4,20}$/', $trimmed['pass'])) {
			if ($trimmed['pass'] == $trimmed['re_pass']) {
				$p = $trimmed['pass'];
			} else {
				$errors[] = "pass dif";
			}
		} else {
			$errors[] = "pass";
		}

        if (filter_var($trimmed['user_level'], FILTER_VALIDATE_INT, array('min_range'=>1))) {
            $ul = $trimmed['user_level'];
        } else {
            $errors[] = "user level";
        }

        // Check for address (not required)
        $add = (!empty($trimmed['address'])) ? $trimmed['address'] : NULL;

        // Check for phone (not required)
        $phone = (!empty($trimmed['phone'])) ? $trimmed['phone'] : NULL;

		if(empty($errors)) { //Nếu không có lỗi xảy ra thì chèn vào CSDL
			// Kiem tra xem email da co trong he thong hay chua
                $q = "SELECT user_id FROM users WHERE email = ? AND user_id != ?";
                if($stmt = mysqli_prepare($dbc, $q)) {

                    // Gan tham so cho cau lenh prepare
                    mysqli_stmt_bind_param($stmt, 'si', $e, $euid);

                    // Cho chay cau lenh prepare
                    mysqli_stmt_execute($stmt);

                    // Luu lai ket qua cua cau lenh prepare
                    mysqli_stmt_store_result($stmt);

                    if(mysqli_stmt_num_rows($stmt) == 0) {
                        // Email available, run query de update csdl
                        $query = "UPDATE users SET first_name = ?, last_name = ?, email = ?, user_level =?, pass = ?, address = ?, phone = ? WHERE user_id = ? LIMIT 1";
                        if($upd_stmt = mysqli_prepare($dbc, $query)) {

                            // Gan tham so
                            mysqli_stmt_bind_param($upd_stmt, 'sssiisss', $fn, $ln, $e, $ul, $p, $add, $phone, $euid);

                            // Cho chay cau lenh
                            mysqli_stmt_execute($upd_stmt) or die("Mysqli Error: $query ". mysqli_stmt_error($upd_stmt));

                            if(mysqli_stmt_affected_rows($upd_stmt) == 1) {
                                redirect_to('admin/users.php?msg=2');
                            } else {
                                $message = "<p class='notice'>Thông tin người dùng không chỉnh sửa được do lỗi hệ thống.</p>";
                            }
                        }
                    } else {
                        $message = "<p class='notice'>Sử dụng email khác. Email này đã có trong hệ thống.</p>";
                    }

                }// END if($STMT)
		} else {
			$messages = "<p class='notice'>Điền đầy đủ dữ liệu cho các trường.</p>";
		}
	}//end if submit form

	// Truy xuat csdl de hien thi thong tin nguoi dung
    if($user = fetch_user($euid)) : // Neu user ton tai, thi hien thi noi dung cua user
?>
	<!--FORM-->
	<div id="main-content">
		<div class="title-content">
			<p>Quản lý danh mục bài viết</p>
		</div>
		<?php
			if (!empty($messages)) echo $messages;
		?>
		<div>
			<form action="" method="POST" id="add-n-cat" class="add-form">
				<fieldset>
					<legend>Thêm mới, chỉnh sửa thông tin danh mục bài viết</legend>
					<label for="first-name">First Name
			            <?php if(isset($errors) && in_array('first_name',$errors)) echo "<p class='notice'>Điền họ của bạn.</p>";?>
			        </label>
			        <input type="text" name="first_name" value="<?php if(isset($user['first_name'])) echo strip_tags($user['first_name']); ?>" size="20" maxlength="40" tabindex='1' />

			        <label for="last-name">Last Name
			            <?php if(isset($errors) && in_array('last name',$errors)) echo "<p class='notice'>Điền tên của bạn.</p>";?>
			        </label>
			        <input type="text" name="last_name" value="<?php if(isset($user['last_name'])) echo strip_tags($user['last_name']); ?>" size="20" maxlength="40" tabindex='2' />

			        <label for="email">Email
			        	<?php if(isset($errors) && in_array('email',$errors)) echo "<p class='notice'>Điền email hợp lệ.</p>";?>
			        </label>
			        <input type="text" name="email" value="<?php if(isset($user['email'])) echo $user['email']; ?>" size="20" maxlength="40" tabindex='3' />

			        <label for="pass">Mật khẩu: <span class="required">*</span>
				        <?php if(isset($errors) && in_array('pass',$errors)) echo "<p class='notice'>Điền mật khẩu hợp lệ.</p>";?>
			        </label>
			        <input type="password" name="pass" value="<?php if(isset($_POST['pass'])) echo $_POST['pass']; ?>" size="20" maxlength="40" tabindex='4' />

			        <label for="re_pass">Nhập lại mật khẩu: <span class="required">*</span>
				        <?php if(isset($errors) && in_array('pass dif',$errors)) echo "<p class='notice'>Mật khẩu nhập lại không trùng.</p>";?>
			        </label>
			        <input type="password" name="re_pass" value="<?php if(isset($_POST['re_pass'])) echo $_POST['re_pass']; ?>" size="20" maxlength="40" tabindex='5' />

			        <label for="User Level">User Level:
			            <?php if(isset($errors) && in_array('user level',$errors)) echo "<p class='notice'>Chọn phân quyền người dùng.</p>";?>
			        </label>
			        <select name="user_level">
			        <?php
			            // Set up array for roles
			            $roles = array(1 => 'Registered Member', 2 => 'Admin');
			            foreach ($roles as $key => $role) {
			                echo "<option value='{$key}'";
			                    if($key == $user['user_level']) {echo "selected='selected'";}
			                echo ">".$role."</option>";
			            }
			        ?>
			        </select>

			        <label for="address">Địa chỉ:</label>
			        <input type="text" name="address" value="<?php if(isset($user['address'])) echo $user['address']; ?>" size="20" maxlength="40" tabindex='7' />

			        <label for="phone">Điện thoại:</label>
			        <input type="text" name="phone" value="<?php if(isset($user['phone'])) echo $user['phone']; ?>" size="20" maxlength="40" tabindex='8' />

					<p><input type="submit" name="submit" value="Cập nhật" /></p>
				</fieldset>
			</form>
		</div>

<?php
	else :
		redirect_to('admin/users.php?msg=1');
	endif;
?>