<?php
	session_start();
	ob_start();
	include('../inc/functions.php');
	include('../inc/mysqli_connect.php');
	if ($_SERVER['REQUEST_METHOD'] == 'POST') {
		
		echo "<pre>";
			print_r($_FILES);
		echo "</pre>";
		
		if (isset($_FILES['image'])) {
			//Tạo một array trống cho biến $errors
			$errors = array();

			//Tạo một array để kiểm tra xem file upload có thuộc dạng cho phép
			$allowed = array('image/jpeg', 'image/jpg', 'image/png', 'image/x-png');
			//Kiểm tra xem file upload có nằm trong định dạng cho phép
			if (in_array(strtolower($_FILES['image']['type']), $allowed)) {
				//Nếu có trong định dạng cho phép, tách lấy phần mở rộng
				$ext = end(explode('.', $_FILES['image']['name']));
				$renamed = uniqid(rand(), true).'.'."$ext";

				//Đổi tên file
				if(!move_uploaded_file($_FILES['image']['tmp_name'], "../images/avatar/".$renamed)) {
					$errors[] = "<p>Server problem.</p>";
				}
			} else {
				$errors[] = "<p>Your file is not a valid type. Please choose a JPG or PNG image to upload.</p>";
			}
		}// end isset $_FILES

		//Kiểm tra lỗi
		if ($_FILES['image']['error'] > 0) {
			$errors[] = "<p>The file could not be uploaded because: <strong>";

			//In thông báo dựa vào lỗi
			switch ($_FILES['image']['error']) {
				case 1:
					$errors[] .= "The file exceeds the upload_max_filesize setting in php.ini";
					break;
				case 2:
					$errors[] .= "The file exceeds the MAX_FILE_SIZE in HTML form";
					break;

				case 3:
					$errors[] .= "The file was partially uploaded";
					break;

				case 4:
					$errors[] .= "NO file was uploaded";
					break;

				case 6:
					$errors[] .= "NO temporary folder was available";
					break;

				case 7:
					$errors[] .= "Unable to write to the disk";
					break;

				case 8:
					$errors[] .= "File upload stopped";
					break;
				default:
					$errors[] .= "A system error has occured";
					break;
			}//end switch
			$errors[] .= ".</strong></p>";
		}//end if error

		// Xóa file đã được upload và tồn tại trong thư mục tạm
		if (isset($_FILES['image']['tmp_name']) && is_file($_FILES['image']['tmp_name']) && file_exists($_FILES['image']['tmp_name'])) {
			unlink($_FILES['image']['tmp_name']);
		}

	}//end main if

	if (empty($errors)) {
		//Update CSDL
		$q = "UPDATE users SET avatar = '{$renamed}' WHERE user_id = {$_SESSION['uid']} LIMIT 1";
		$r = mysqli_query($dbc, $q);
			confirm_query($r, $q);
		if (mysqli_affected_rows($dbc) > 0) {
			//Update thành công, chuyển hướng người dùng về trang edit-profile.php
			redirect_to('edit-profile.php');
		}
	}

	report_error($errors);
?>