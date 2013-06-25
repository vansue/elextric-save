<?php
	ob_start();
	$title = "Đổi mật khẩu | Elextronic";
	include('inc/header.php');
	require_once('inc/functions.php');
	require_once('inc/mysqli_connect.php');
	include('inc/first-sidebar.php');
?>
<div id="main-content">
	<?php
		//Kiểm tra xem người dùng đã đăng nhập chưa
		is_logged_in();
		if ($_SERVER['REQUEST_METHOD'] == 'POST') {//Nếu đúng -> Form đã được submit -> xử lý form
			$errors = array();
			//Kiểm tra xem mật khẩu hiện tại đã được nhập đúng định dạng hay không
			if (isset($_POST['cur_pass']) && preg_match('/^[\w\'.-]{4,20}$/', trim($_POST['cur_pass']))) {
				//Truy vấn CSDL tìm xem mật khẩu có tồn tại hay không
				$cur_pass = mysqli_real_escape_string($dbc, trim($_POST['cur_pass']));
				$q = "SELECT last_name FROM users WHERE pass = SHA1('$cur_pass') AND user_id = {$_SESSION['uid']}";
				$r = mysqli_query($dbc, $q);
					confirm_query($r, $q);
				//Nếu có giá trị trả về thì sẽ làm tiếp
				if (mysqli_num_rows($r) == 1) {
					//Tìm thấy người dùng trong CSDL, cho phép người dùng thay đổi mật khẩu
					//Kiểm tra xem mật khẩu mới đã được nhập đúng định dạng hay không
					if (isset($_POST['new_pass']) && preg_match('/^[\w\'.-]{4,20}$/', trim($_POST['new_pass']))) {
						//Nếu đúng
						//Kiểm tra xem nhập lại pass có đúng không
						if ($_POST['new_pass'] == $_POST['conf_pass']) {
							$np = mysqli_real_escape_string($dbc, trim($_POST['new_pass']));
							//Nếu hai trường mật khẩu giống nhau, update CSDL với mật khẩu mới
							$q = "UPDATE users SET pass = SHA1('$np') WHERE user_id = {$_SESSION['uid']} LIMIT 1";
							$r = mysqli_query($dbc, $q);
								confirm_query($r, $q);
							//Kiểm tra xem có update thành công hay không
							if (mysqli_affected_rows($dbc) == 1) {
								//Nếu upadte thành công
								$errors[] = "<p class='notice success'>Đổi mật khẩu thành công.</p>";
							} else {
								//Nếu update không thành công
								$errors[] = "<p class='notice'>Không thể đổi mật khẩu do lỗi hệ thống.</p>";
							}
						} else {
							$errors[] = "<p class='notice'>Mật khẩu nhập lại không đúng.</p>";
						}
					} else {
						//Nếu sai
						$errors[] = "<p class='notice'>Điền mật khẩu mới hợp lệ của bạn.</p>";
					}

				} else {
					$errors[] = "<p class='notice'>Mật khẩu hiện tại không đúng.</p>";
				}
			} else {
				$errors[] = "<p class='notice'>Điền mật khẩu hiện tại hợp lệ của bạn.</p>";
			}
		}

	?>
	<div class="title-content">
		<p>Đổi mật khẩu</p>
	</div>

	<div>
		<?php if(isset($errors)) report_error($errors); ?>
		<form action="" method="POST" id="add-n-cat" class="add-form">
			<fieldset>
				<legend>Đổi mật khẩu</legend>
				<label for="cur_pass">Mật khẩu hiện tại: <span class="required">*</span></label>
				<input type="password" name="cur_pass" id="cur_pass" value="" size="60" maxlength="40" tabindex="3" />

				<label for="new_pass">Mật khẩu mới: <span class="required">*</span></label>
				<input type="password" name="new_pass" id="new_pass" value="" size="20" maxlength="40" tabindex="4" />

				<label for="conf_pass">Nhập lại mật khẩu mới: <span class="required">*</span></label>
				<input type="password" name="conf_pass" id="conf_pass" value="" size="20" maxlength="40" tabindex="5" />

				<p><input type="submit" name="submit" value="Đổi mật khẩu" /></p>
			</fieldset>
		</form>
	</div>
</div><!--end #main-content-->
<?php
	include('inc/second-sidebar.php');
	include('inc/footer.php');
?>