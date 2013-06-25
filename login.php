<?php
	ob_start();
	include('inc/header.php');
	require_once('inc/functions.php');
	require_once('inc/mysqli_connect.php');
	include('inc/first-sidebar.php');
?>

<?php
	if ($_SERVER['REQUEST_METHOD'] == 'POST') {
		//Bắt đầu xử lý form. Tạo biến $errors
		$errors = array();
		//Validate email
		if (isset($_POST['email']) && filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
			$e = mysqli_real_escape_string($dbc, $_POST['email']);
		} else {
			$errors[] = 'email';
		}

		//Validate pasword
		if (isset($_POST['pass']) && preg_match('/^[\w\'.-]{4,20}$/', $_POST['pass'])) {
			$p = mysqli_real_escape_string($dbc, $_POST['pass']);
		} else {
			$errors[] = 'pass';
		}

		if (empty($errors)) {
			//Bắt đầu truy vấn CSDL để lấy thông tin người dùng
			$q = "SELECT user_id, last_name, user_level FROM users WHERE (email = '{$e}' AND pass = SHA1('$p')) AND active IS NULL LIMIT 1";
			$r = mysqli_query($dbc, $q);
				confirm_query($r, $q);
			if (mysqli_num_rows($r) == 1) {
				//Nếu tìm thấy thông tin người dùng trong CSDL, sẽ chuyển hướng người dùng về trang thích hợp
				list($uid, $ln, $user_level) = mysqli_fetch_array($r, MYSQLI_NUM);
				$_SESSION['uid'] = $uid;
				$_SESSION['last_name'] = $ln;
				$_SESSION['user_level'] = $user_level;

				redirect_to();
			} else {
				$mesage = "<p class='notice'>Email hoặc mật khẩu không đúng. Hoặc bạn vẫn chưa kích hoạt tài khoản.</p>";
			}
		} else {
			$messages = "<p class='notice'>Điền đầy đủ dữ liệu cho các trường.</p>";
		}
	} //end if submit form
?>

<div id="main-content">
    <div class="title-content">
        <p>Đăng nhập</p>
    </div>

    <div>
		<?php if(!empty($mesage)) echo $mesage; ?>
		<form action="" method="POST" id="add-n-cat" class="add-form">
			<fieldset>
				<legend>Đăng nhập</legend>
				<label for="email">Email: <span class="required">*</span>
					<?php
						if(isset($errors) && in_array('email', $errors)) {
							echo "<p class='warning'>Điền email của bạn.</p>";
						}
					?>
				</label>
				<input type="text" name="email" id="email" value="<?php if(isset($_POST['email'])) {echo htmlentities($_POST['email']); } ?>" size="20" maxlength="100" tabindex="1" />

				<label for="pass">Mật khẩu: <span class="required">*</span>
					<?php
						if(isset($errors) && in_array('pass', $errors)) {
							echo "<p class='warning'>Điền mật khẩu của bạn.</p>";
						}
					?>
				</label>
				<input type="password" name="pass" id="pass" value="" size="20" maxlength="20" tabindex="2" />

				<p id="qmk"><a href="retrieve-pass.php">Quên mật khẩu?</a></p>
				<p><input type="submit" name="submit" value="Đăng nhập" /></p>

			</fieldset>
		</form>
	</div>

</div><!--end #main-content-->
<?php
	include('inc/second-sidebar.php');
	include('inc/footer.php');
?>