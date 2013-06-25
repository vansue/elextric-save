<?php
/**************************** THÊM MỚI DANH MỤC BÀI VIẾT ****************************/
$epid = null;
$dpid = null;
if($_SERVER['REQUEST_METHOD'] == 'POST') {//Nếu đúng -> Form đã được submit -> xử lý form
	//Kiểm tra các trường của form
	$errors = array();//Biến bắt lỗi
	//Tạo một array trống cho biến $errs
	$errs = array();
	if(empty($_POST['page-name'])) {
		$errors[] = "page-name";
	} else {
		$page_name = mysqli_real_escape_string($dbc, strip_tags($_POST['page-name']));
	}

	if(isset($_POST['position']) && filter_var($_POST['position'], FILTER_VALIDATE_INT, array('min_range' => 1))) {//Kiểm tra giá trị nhập vào
		$position = $_POST['position'];
	} else {
		$errors[] = "position";
	}

	if(empty($_POST['content'])) {
		$errors[] = "content";
	} else {
		$content = mysqli_real_escape_string($dbc, $_POST['content']);
	}

	//Upload ảnh đại diện
	if (isset($_FILES['image'])) {

		//Tạo một array để kiểm tra xem file upload có thuộc dạng cho phép
		$allowed = array('image/jpeg', 'image/jpg', 'image/png', 'image/x-png');
		//Kiểm tra xem file upload có nằm trong định dạng cho phép
		if (in_array(strtolower($_FILES['image']['type']), $allowed)) {
			//Nếu có trong định dạng cho phép, tách lấy phần mở rộng
			$ext = end(explode('.', $_FILES['image']['name']));
			$renamed = uniqid(rand(), true).'.'."$ext";

			//Đổi tên file
			if(!move_uploaded_file($_FILES['image']['tmp_name'], "../images/news/".$renamed)) {
				$errs[] = "<p class='notice'>Lỗi hệ thống.</p>";
			}
		} else {
			$errs[] = "<p class='notice'>File của bạn không đúng định dạng. Chọn ảnh PNG hoặc JPG để upload.</p>";
		}
	}// end isset $_FILES

	//Kiểm tra lỗi
	if ($_FILES['image']['error'] > 0) {
		$errs[] = "<p class='notice'>File không thể upload do: <strong>";

		//In thông báo dựa vào lỗi
		switch ($_FILES['image']['error']) {
			case 1:
				$errs[] .= "The file exceeds the upload_max_filesize setting in php.ini";
				break;
			case 2:
				$errs[] .= "The file exceeds the MAX_FILE_SIZE in HTML form";
				break;

			case 3:
				$errs[] .= "The file was partially uploaded";
				break;

			case 4:
				$errs[] .= "NO file was uploaded";
				break;

			case 6:
				$errs[] .= "NO temporary folder was available";
				break;

			case 7:
				$errs[] .= "Unable to write to the disk";
				break;

			case 8:
				$errs[] .= "File upload stopped";
				break;
			default:
				$errs[] .= "A system error has occured";
				break;
		}//end switch
		$errs[] .= ".</strong></p>";
	}//end if error

	// Xóa file đã được upload và tồn tại trong thư mục tạm
	if (isset($_FILES['image']['tmp_name']) && is_file($_FILES['image']['tmp_name']) && file_exists($_FILES['image']['tmp_name'])) {
		unlink($_FILES['image']['tmp_name']);
	}

	if(empty($errors) && empty($errs)) { //Nếu không có lỗi xảy ra thì chèn vào CSDL
		$q = "INSERT INTO pages (user_id, cat_id, page_name, intro_img, content, position, post_on) ";
		$q .= " VALUES (1, $ncid, '{$page_name}', '{$renamed}', '{$content}', $position, NOW())";
		$r = mysqli_query($dbc, $q);
			confirm_query($r, $q);
		if (mysqli_affected_rows($dbc) == 1) {
			redirect_to('admin/view-news.php?ncid='.$ncid.'&msg=3');
		} else {
			$messages = "<p class='notice'>Không thể thêm bài viết vào CSDL do lỗi hệ thống.</p>";
		}
	} else {
		$messages = "<p class='notice'>Điền đầy đủ dữ liệu cho các trường.</p>";
	}
} //end if submit form
?>

<!--FORM-->
<div id="main-content">
	<div class="title-content">
		<p>Thêm mới bài viết</p>
	</div>
	<?php
		if (!empty($messages)) {
			echo $messages;
		}
	?>
	<div>
		<form id="add-news" action="" method="post" enctype="multipart/form-data" class="add-form">
			<fieldset>
				<legend>Thêm mới bài viết</legend>
				<label for="page">Tên bài viết: <span class="required">*</span>
					<?php
					if(isset($errors) && in_array('page-name', $errors)) {
						echo "<p class='notice'>Điền tên bài viết.</p>";
					}
				?>
				</label>
				<input type="text" name="page-name" id="page-name" value="<?php if (isset($_POST['page-name']))	echo strip_tags($_POST['page-name']); ?>" size="20" maxlength="100" tabindex="1" />

				<label for="image">Ảnh đại diện: <span class="required">*</span>
					<?php if(isset($errs)) report_error($errs); ?>
				</label> <br />
    			<input type="hidden" name="MAX_FILE_SIZE" value="524288" class="hidden" />
    			<input type="file" name="image" />

				<label for="position">Vị trí: <span class="required">*</span>
					<?php
					if(isset($errors) && in_array('position', $errors)) {
						echo "<p class='notice'>Chọn vị trí bài viết.</p>";
					}
				?>
				</label>
				<select name="position">
					<?php
					$q = "SELECT count(page_id) AS count FROM pages WHERE cat_id={$ncid}";
					$r = mysqli_query($dbc, $q);
						confirm_query($r, $q);
					if(mysqli_num_rows($r) == 1) {
						list($num) = mysqli_fetch_array($r, MYSQLI_NUM);
						for ($i=1; $i <= $num+1; $i++) {//Tạo vòng for để tạo ra option, cộng thêm một giá trị cho position
							echo "<option value='{$i}'";
								if(isset($_POST['position']) && $_POST['position'] == $i) echo "selected='selected'";
							echo ">".$i."</option>";
						}
					}
				?>
				</select>

				<label for="page-content">Nội dung bài viết: <span class="required">*</span>
					<?php
					if(isset($errors) && in_array('content', $errors)) {
						echo "<p class='notice'>Nhập nội dung bài viết.</p>";
					}
				?>
				</label>
				<textarea name="content" cols="50" rows="20"><?php if(isset($_POST['content'])) echo $_POST['content']; ?></textarea>

				<p><input type="submit" name="submit" value="Thêm mới bài viết" /></p>
			</fieldset>
		</form>
	</div>

</div><!--end #main-content-->