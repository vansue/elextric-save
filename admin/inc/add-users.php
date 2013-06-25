<?php
/**************************** THÊM MỚI DANH MỤC BÀI VIẾT ****************************/
	$euid = null;
	$duid = null;
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
			//Kiểm tra xem email đã có trong hệ thống hay chưa
			$q = "SELECT user_id FROM users WHERE email = ?";
			if($stmt = mysqli_prepare($dbc, $q)) {
				//Gán tham số cho câu lệnh prepare
				mysqli_stmt_bind_param($stmt, 's', $e);

				//Cho chạy câu lệnh prepare
				mysqli_stmt_execute($stmt) or die("MySQL Error: $q" . mysqli_stmt_error($stmt));

				//Lưu lại kết quả của câu lệnh prepare
				mysqli_stmt_store_result($stmt);

				if (mysqli_stmt_num_rows($stmt) == 0) {
					//Email khả dụng, insert vào CSDL
					$query = "INSERT INTO users (first_name, last_name, pass, email, user_level, address, phone, registration_date) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
					if ($ins_stmt = mysqli_prepare($dbc, $query)) {
						mysqli_stmt_bind_param($ins_stmt, "ssssissd", $fn, $ln, SHA1($p), $e, $ul, $add, $phone, getdate());
						mysqli_stmt_execute($ins_stmt) or die("MySQL Error: $q" . mysqli_stmt_error($ins_stmt));
						if(mysqli_stmt_affected_rows($ins_stmt) == 1) {
							$messages = "<p class='notice success'>Thêm người dùng thành công.</p>";
						} else {
							$messages = "<p class='notice'>Không thể thêm người dùng do lỗi hệ thống.</p>";
						}
					}
				} else {
					$messages = "<p class='notice'>Dùng email khác để đăng ký. Email này đã tồn tại trong hệ thống.</p>";
				}
			}
		} else {
			$messages = "<p class='notice'>Điền đầy đủ dữ liệu cho các trường.</p>";
		}
	} //end if submit form
?>
	<!--FORM-->
	<div id="main-content">
		<div class="title-content">
			<p>Quản lý thành viên</p>
		</div>
		<?php
			if (!empty($messages)) {
				echo $messages;
			} else {
				if(isset($_GET['msg'])) {
					$msg = $_GET['msg'];
					switch ($msg) {
						case '1':
							echo "<p class='notice'>Thành viên không tồn tại.</p>";
							break;
						case '2':
							echo "<p class='notice success'>Thông tin người dùng được chỉnh sửa thành công.</p>";
							break;
						case '3':
							echo "<p class='notice success'>Xóa thành viên thành công.</p>";
							break;
						default:
							redirect_to('admin/index.php');
							break;
					}
				}
			}
		?>
		<div>
			<form action="" method="POST" id="add-n-cat" class="add-form">
				<fieldset>
					<legend>Thêm mới, chỉnh sửa thông tin thành viên</legend>

					<label for="first-name">Họ: <span class="required">*</span>
			            <?php if(isset($errors) && in_array('first_name',$errors)) echo "<p class='notice'>Điền họ của bạn.</p>";?>
			        </label>
			        <input type="text" name="first_name" value="<?php if(isset($_POST['first_name'])) echo strip_tags($_POST['first_name']); ?>" size="20" maxlength="40" tabindex='1' />

			        <label for="last-name">Tên: <span class="required">*</span>
			            <?php if(isset($errors) && in_array('last name',$errors)) echo "<p class='notice'>Điền tên của bạn.</p>";?>
			        </label>
			        <input type="text" name="last_name" value="<?php if(isset($_POST['last_name'])) echo strip_tags($_POST['last_name']); ?>" size="20" maxlength="40" tabindex='2' />

			        <label for="email">Email: <span class="required">*</span>
				        <?php if(isset($errors) && in_array('email',$errors)) echo "<p class='notice'>Điền email hợp lệ.</p>";?>
			        </label>
			        <input type="text" name="email" value="<?php if(isset($_POST['email'])) echo $_POST['email']; ?>" size="20" maxlength="60" tabindex='3' />

			        <label for="pass">Mật khẩu: <span class="required">*</span>
				        <?php if(isset($errors) && in_array('pass',$errors)) echo "<p class='notice'>Điền mật khẩu hợp lệ.</p>";?>
			        </label>
			        <input type="password" name="pass" value="<?php if(isset($_POST['pass'])) echo $_POST['pass']; ?>" size="20" maxlength="40" tabindex='4' />

			        <label for="re_pass">Nhập lại mật khẩu: <span class="required">*</span>
				        <?php if(isset($errors) && in_array('pass dif',$errors)) echo "<p class='notice'>Mật khẩu nhập lại không trùng.</p>";?>
			        </label>
			        <input type="password" name="re_pass" value="<?php if(isset($_POST['re_pass'])) echo $_POST['re_pass']; ?>" size="20" maxlength="40" tabindex='5' />

			        <label for="User Level">User Level: <span class="required">*</span>
			            <?php if(isset($errors) && in_array('user level',$errors)) echo "<p class='notice'>Chọn phân cấp người dùng.</p>";?>
			        </label>
			        <select name="user_level">
			        <?php
			            // Set up array for roles
			            $roles = array(1 => 'Registered Member', 2 => 'Admin');
			            foreach ($roles as $key => $role) {
			                echo "<option value='{$key}'";
			                    if(isset($_POST['user_level']) && $key == $_POST['user_level']) {echo "selected='selected'";}
			                echo ">".$role."</option>";
			            }
			        ?>
			        </select>

			        <label for="address">Địa chỉ:</label>
			        <input type="text" name="address" value="<?php if(isset($_POST['address'])) echo $_POST['address']; ?>" size="20" maxlength="40" tabindex='7' />

			        <label for="phone">Điện thoại:</label>
			        <input type="text" name="phone" value="<?php if(isset($_POST['phone'])) echo $_POST['phone']; ?>" size="20" maxlength="40" tabindex='8' />

					<p><input type="submit" name="submit" value="Thêm mới" /></p>
				</fieldset>
			</form>
		</div>