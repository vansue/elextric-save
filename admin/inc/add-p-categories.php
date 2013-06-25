<?php
/**************************** THÊM MỚI DANH MỤC SẢN PHẨM ****************************/
		$ecid = null;
		$dcid = null;
		/* VALIDATE FORM */
		if ($_SERVER['REQUEST_METHOD'] == 'POST') {//Nếu đúng -> Form đã được submit -> xử lý form
			//Kiểm tra các trường của form
			$errors = array();//Biến bắt lỗi
			if (empty($_POST['p-category'])) {
				$errors[] = "category";
			} else {
				$p_cat_name = mysqli_real_escape_string($dbc, strip_tags($_POST['p-category']));
			}

			if (isset($_POST['position']) && filter_var($_POST['position'], FILTER_VALIDATE_INT, array('min_range' => 1))) {//Kiểm tra giá trị nhập vào
				$position = $_POST['position'];
			} else {
				$errors[] = "position";
			}

			if(empty($errors)) { //Nếu không có lỗi xảy ra thì chèn vào CSDL
				//Thêm mới
				$q = "INSERT INTO p_categories (user_id, cat_name, position) ";
				$q .= " VALUES (1, '{$p_cat_name}', $position)";
				$r = mysqli_query($dbc, $q);
					confirm_query($r, $q);
				if (mysqli_affected_rows($dbc) == 1) {
					$messages = "<p class='notice success'>Thêm mới danh mục thành công.</p>";
				} else {
					$messages = "<p class='notice'>Không thể thêm danh mục vào CSDL do lỗi hệ thống.</p>";
				}
			} else {
				$messages = "<p class='notice'>Điền đầy đủ dữ liệu cho các trường.</p>";
			}
		} //end if submit form
?>
		<!--FORM-->
		<div id="main-content">
			<div class="title-content">
				<p>Quản lý danh mục sản phẩm</p>
			</div>
			<?php
				if (!empty($messages)) {
					echo $messages;
				} else {
					if(isset($_GET['msg'])) {
						$msg = $_GET['msg'];
						switch ($msg) {
							case '1':
								echo "<p class='notice'>Danh mục không tồn tại.</p>";
								break;
							case '2':
								echo "<p class='notice success'>Sửa danh mục thành công.</p>";
								break;
							case '3':
								echo "<p class='notice success'>Xóa danh mục thành công.</p>";
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
						<legend>Thêm mới, chỉnh sửa thông tin danh mục sản phẩm</legend>
						<label for="p-category">Tên danh mục: <span class="required">*</span>
							<?php
								if(isset($errors) && in_array('category', $errors)) {
									echo "<p class='notice'>Điền tên danh mục.</p>";
								}
							?>
						</label>
						<input type="text" name="p-category" id="p-category" value="<?php if(isset($_POST['p-category'])) echo strip_tags($_POST['p-category']); ?>" size="20" maxlength="100" tabindex="1" />
						<label for="position">Vị trí: <span class="required">*</span>
							<?php
								if(isset($errors) && in_array('position', $errors)) {
									echo "<p class='notice'>Chọn vị trí danh mục.</p>";
								}
							?>
						</label>
						<select name="position" tabindex="2">
							<!--================ Lấy dữ liệu trong CSDL để hiển thị ==================-->
							<?php
								$q = "SELECT count(cat_id) AS count FROM p_categories";
								$r = mysqli_query($dbc, $q);
									confirm_query($r, $q);
								if(mysqli_num_rows($r) == 1) {
									list($num) = mysqli_fetch_array($r, MYSQLI_NUM);
									for ($i=1; $i <= $num+1; $i++) {//Tạo vòng for để tạo ra option, cộng thêm một giá trị cho position
										echo "<option value='{$i}'";
											if(isset($_POST['position']) && $_POST['position'] == $i) {
												echo "selected='selected'";
											}
										echo ">".$i."</option>";
									}
								}
							?>
						</select>
						<p><input type="submit" name="submit" value="Thêm mới" /></p>
					</fieldset>
				</form>
			</div>