<?php
	/**************************** EDIT DANH MỤC BÀI VIẾT **********************************/
	$ecid = $_GET['ecid'];
	$dcid = NULL;
	//Lấy dữ liệu từ CSDL
	$q = "SELECT cat_name, position FROM n_categories WHERE cat_id = {$ecid}";
	$r = mysqli_query($dbc, $q);
		confirm_query($r, $q);
	if (mysqli_num_rows($r) == 1) {//Nếu category tồn tại trong CSDL, xuất dữ liệu ra ngoài trình duyệt
		list($ecat_name, $eposition) = mysqli_fetch_array($r, MYSQLI_NUM);
	} else {//Nếu ecid không hợp lệ
		redirect_to('admin/n-categories.php?msg=1');
	}
	/* VALIDATE FORM */
	if ($_SERVER['REQUEST_METHOD'] == 'POST') {//Nếu đúng -> Form đã được submit -> xử lý form
		//Kiểm tra các trường của form
		$errors = array();//Biến bắt lỗi
		if (empty($_POST['n-category'])) {
			$errors[] = "category";
		} else {
			$n_cat_name = mysqli_real_escape_string($dbc, strip_tags($_POST['n-category']));
		}

		if (isset($_POST['position']) && filter_var($_POST['position'], FILTER_VALIDATE_INT, array('min_range' => 1))) {//Kiểm tra giá trị nhập vào
			$position = $_POST['position'];
		} else {
			$errors[] = "position";
		}

		if(empty($errors)) { //Nếu không có lỗi xảy ra thì chèn vào CSDL
			//Cập nhật
			$q = "UPDATE n_categories ";
			$q .= " SET cat_name = '{$n_cat_name}', position = $position ";
			$q .= " WHERE cat_id = {$ecid}";
			$r = mysqli_query($dbc, $q);
				confirm_query($r, $q);
			if (mysqli_affected_rows($dbc) == 1) {
				redirect_to('admin/n-categories.php?msg=2');
			} else {
				$messages = "<p class='notice'>Không thể sửa danh mục do lỗi hệ thống.</p>";
			}
		} else {
			$messages = "<p class='notice'>Điền đầy đủ dữ liệu cho các trường.</p>";
		}
	}//end if submit form
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
					<label for="n-category">Tên danh mục: <span class="required">*</span>
						<?php
							if(isset($errors) && in_array('category', $errors)) {
								echo "<p class='notice'>Điền tên danh mục.</p>";
							}
						?>
					</label>
					<input type="text" name="n-category" id="n-category" value="<?php if(isset($ecat_name)) echo $ecat_name; ?>" size="20" maxlength="100" tabindex="1" />
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
							$q = "SELECT count(cat_id) AS count FROM n_categories";
							$r = mysqli_query($dbc, $q);
								confirm_query($r, $q);
							if(mysqli_num_rows($r) == 1) {
								list($num) = mysqli_fetch_array($r, MYSQLI_NUM);
								for ($i=1; $i <= $num+1; $i++) {//Tạo vòng for để tạo ra option, cộng thêm một giá trị cho position
									echo "<option value='{$i}'";
										if(isset($eposition) && $eposition == $i) {
											echo "selected='selected'";
										}
									echo ">".$i."</option>";
								}
							}
						?>
					</select>
					<p><input type="submit" name="submit" value="Cập nhật" /></p>
				</fieldset>
			</form>
		</div>