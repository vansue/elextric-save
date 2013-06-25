<?php
	$title = "Đăng ký tài khoản | Elextronic";
	include('inc/header.php');
	require_once('inc/functions.php');
	require_once('inc/mysqli_connect.php');
	include('inc/first-sidebar.php');
?>
<div id="main-content">
	<?php
		if ($_SERVER['REQUEST_METHOD'] == 'POST') {//Nếu đúng -> Form đã được submit -> xử lý form
			$errors = array();
			//Mặc định cho các trường nhập liệu là FALSE
			$fn = $ln = $e = $p = FALSE;
			if (preg_match('/^[\w\'.-]{2,20}$/i', trim($_POST['first-name']))) {
				$fn = mysqli_real_escape_string($dbc, trim($_POST['first-name']));
			} else {
				$errors[] = 'first name';
			}

			if (preg_match('/^[\w\'.-]{2,20}$/', trim($_POST['last-name']))) {
				$ln = mysqli_real_escape_string($dbc, trim($_POST['last-name']));
			} else {
				$errors[] = 'last name';
			}

			if (filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
				$e = mysqli_real_escape_string($dbc, trim($_POST['email']));
			} else {
				$errors[] = 'email';
			}

			if (preg_match('/^[\w\'.-]{4,20}$/', trim($_POST['password1']))) {
				if ($_POST['password1'] == $_POST['password2']) {
					$p = mysqli_real_escape_string($dbc, trim($_POST['password1']));
				} else {
					$errors[] = "password dif";
				}
			} else {
				$errors[] = "password";
			}

			// Check for address (not required)
			if (isset($_POST['address'])) {
				$add = mysqli_real_escape_string($dbc, strip_tags($_POST['address']));
			} else {
				$add = NULL;
			}

	        // Check for phone (not required)
	        if (isset($_POST['phone'])) {
				$phone = mysqli_real_escape_string($dbc, strip_tags($_POST['phone']));
			} else {
				$phone = NULL;
			}

	        // Check for website (not required)
	        if (isset($_POST['web'])) {
				$web = mysqli_real_escape_string($dbc, strip_tags($_POST['web']));
			} else {
				$web = NULL;
			}

	        // Check for yahoo (not required)
	        if (isset($_POST['yahoo'])) {
				$yahoo = mysqli_real_escape_string($dbc, strip_tags($_POST['yahoo']));
			} else {
				$yahoo = NULL;
			}

	        // Check for website (not required)
	        if (isset($_POST['bio'])) {
				$bio = mysqli_real_escape_string($dbc, htmlentities($_POST['bio']));
			} else {
				$bio = NULL;
			}

			if ($fn && $ln && $e && $p) {
				//Nếu các biến đều có giá trị, truy vấn CSDL
				$q = "SELECT user_id FROM users WHERE email = '{$e}'";
				$r = mysqli_query($dbc, $q);
					confirm_query($r, $q);
				if (mysqli_num_rows($r) == 0) {
					//Email vẫn còn trống, cho phép người dùng đăng ký
					//Tạo ra một chuỗi Activation Key
					$a = md5(uniqid(rand(), true));

					//Chèn giá trị vào CSDL
					$q = "INSERT INTO users(first_name, last_name, email, pass, active, registration_date, address, phone, website, yahoo, bio, user_level) ";
					$q .= " VALUES ('{$fn}', '{$ln}', '{$e}', SHA1('$p'), '{$a}', NOW(), '{$add}', '{$phone}', '{$web}', '{$yahoo}', '{$bio}', 1)";
					$r = mysqli_query($dbc, $q);
						confirm_query($r, $q);
					if (mysqli_affected_rows($dbc) == 1) {
						// Khai báo thư viện phpmailer
						require "lib/class.phpmailer.php";

						// Khai báo tạo PHPMailer
						$mail = new PHPMailer();
						//Khai báo gửi mail bằng SMTP
						$mail->IsSMTP();
						//Tắt mở kiểm tra lỗi trả về, chấp nhận các giá trị 0 1 2
						// 0 = off không thông báo bất kì gì, tốt nhất nên dùng khi đã hoàn thành.
						// 1 = Thông báo lỗi ở client
						// 2 = Thông báo lỗi cả client và lỗi ở server
						$mail->SMTPDebug  = 0;

						$mail->Debugoutput = "html"; // Lỗi trả về hiển thị với cấu trúc HTML
						$mail->Host       = "smtp.gmail.com"; //host smtp để gửi mail
						$mail->Port       = 587; // cổng để gửi mail
						$mail->SMTPSecure = "tls"; //Phương thức mã hóa thư - ssl hoặc tls
						$mail->SMTPAuth   = true; //Xác thực SMTP
						$mail->Username   = "trongnghiahp85@gmail.com"; // Tên đăng nhập tài khoản Gmail
						$mail->Password   = "huong@83279"; //Mật khẩu của gmail
						$mail->SetFrom("elextronic@gmail.com", "Elextronic"); // Thông tin người gửi
						$mail->AddReplyTo("trongnghiahp85@gmail.com","Test Reply");// Ấn định email sẽ nhận khi người dùng reply lại.
						$mail->AddAddress("{$e}", "{$fn}"." "."{$ln}");//Email của người nhận
						$mail->Subject = "Kích hoạt tài khoản tại Elextronic"; //Tiêu đề của thư
						$mail->CharSet = "utf-8";
						$mail->MsgHTML("Cảm ơn bạn đã đăng ký ở trang Elextronic. Một email kích hoạt đã được gửi tới địa chỉ email mà bạn cung cấp. Phiền bạn click vào đường link để kích hoạt tài khoản: ".BASE_URL."activate.php?x=".urldecode($e)."&y=".$a); //Nội dung của bức thư.
						// $mail->MsgHTML(file_get_contents("email-template.html"), dirname(__FILE__));
						// Gửi thư với tập tin html
						$mail->AltBody = "This is a plain-text message body";//Nội dung rút gọn hiển thị bên ngoài thư mục thư.
						//$mail->AddAttachment("images/attact-tui.gif");//Tập tin cần attach

						//Tiến hành gửi email và kiểm tra lỗi
						if(!$mail->Send()) {
						  	$mesage = "<p class='notice'>Có lỗi khi gửi mail: " . $mail->ErrorInfo."</p>";
						} else {
						  	$mesage = "<p class='notice success'>Tài khoản của bạn đã được đăng ký thành công. Email đã được gửi tới địa chỉ của bạn. Bạn phải nhấn vào link để kích hoạt tài khoản trước khi sử dụng nó.</p>";
						}
					} else {
						$mesage = "<p class='notice'>Xin lỗi, đăng ký của bạn không thể thực hiện được do lỗi hệ thống.</p>";
					}
				} else {
					//Email đã tồn tại, phải đăng ký bằng email khác
					$mesage = "<p class='notice'>Email đã được sử dụng. Làm ơn sử dụng email khác để đăng ký.</p>";
				}
			} else {
				//Nếu một trong các biến không có giá trị
				$mesage = "<p class='notice'>Điền đầy đủ dữ liệu cho các trường yêu cầu.</p>";
			}
		} //end if submit form
	?>
	<div class="title-content">
		<p>Đăng ký tài khoản</p>
	</div>

	<div>
		<?php if(!empty($mesage)) echo $mesage; ?>
		<form action="" method="POST" id="add-n-cat" class="add-form">
			<fieldset>
				<legend>Đăng ký</legend>
				<label for="first-name">Họ: <span class="required">*</span>
					<?php
						if(isset($errors) && in_array('first name', $errors)) {
							echo "<p class='notice'>Điền họ của bạn.</p>";
						}
					?>
				</label>
				<input type="text" name="first-name" id="first-name" value="<?php if(isset($_POST['first-name'])) echo $_POST['first-name']; ?>" size="20" maxlength="40" tabindex="1" />

				<label for="last-name">Tên: <span class="required">*</span>
					<?php
						if(isset($errors) && in_array('last name', $errors)) {
							echo "<p class='notice'>Điền tên của bạn.</p>";
						}
					?>
				</label>
				<input type="text" name="last-name" id="last-name" value="<?php if(isset($_POST['last-name'])) echo $_POST['last-name']; ?>" size="20" maxlength="40" tabindex="2" />

				<label for="email">Email: <span class="required">*</span>
					<?php
						if(isset($errors) && in_array('email', $errors)) {
							echo "<p class='notice'>Điền email hợp lệ.</p>";
						}
					?>
				</label>
				<input type="text" name="email" id="email" value="<?php if(isset($_POST['email'])) echo htmlentities($_POST['email'], ENT_COMPAT, 'UTF-8'); ?>" size="60" maxlength="40" tabindex="3" />
				<span id="available"></span>

				<label for="password1">Mật khẩu: <span class="required">*</span>
					<?php
						if(isset($errors) && in_array('password', $errors)) {
							echo "<p class='notice'>Nhập mật khẩu của bạn.</p>";
						}
					?>
				</label>
				<input type="password" name="password1" id="password1" value="<?php if(isset($_POST['password1'])) echo $_POST['password1']; ?>" size="20" maxlength="40" tabindex="4" />

				<label for="password2">Nhập lại mật khẩu: <span class="required">*</span>
					<?php
						if(isset($errors) && in_array('password dif', $errors)) {
							echo "<p class='notice'>Mật khẩu không trùng nhau.</p>";
						}
					?>
				</label>
				<input type="password" name="password2" id="password2" value="<?php if(isset($_POST['password2'])) echo $_POST['password2']; ?>" size="20" maxlength="40" tabindex="5" />

				<label for="address">Địa chỉ:</label>
				<input type="text" name="address" id="address" value="<?php if(isset($_POST['address'])) echo htmlentities($_POST['address'], ENT_COMPAT, 'UTF-8'); ?>" size="60" maxlength="40" tabindex="3" />

				<label for="phone">Điện thoại:</label>
				<input type="text" name="phone" id="phone" value="<?php if(isset($_POST['phone'])) echo htmlentities($_POST['phone'], ENT_COMPAT, 'UTF-8'); ?>" size="60" maxlength="40" tabindex="3" />

				<label for="web">Website:</label>
				<input type="text" name="web" id="web" value="<?php if(isset($_POST['web'])) echo htmlentities($_POST['web'], ENT_COMPAT, 'UTF-8'); ?>" size="60" maxlength="40" tabindex="3" />

				<label for="yahoo">Yahoo:</label>
				<input type="text" name="yahoo" id="yahoo" value="<?php if(isset($_POST['yahoo'])) echo htmlentities($_POST['yahoo'], ENT_COMPAT, 'UTF-8'); ?>" size="60" maxlength="40" tabindex="3" />

				<label for="bio">Giới thiệu:</label>
				<textarea cols="50" rows="20" name="bio"><?php if(isset($_POST['bio'])) echo htmlentities($_POST['bio'], ENT_COMPAT, 'UTF-8'); ?></textarea>

				<p><input type="submit" name="submit" value="Đăng ký" /></p>
			</fieldset>
		</form>
	</div>
</div><!--end #main-content-->
<?php
	include('inc/second-sidebar.php');
	include('inc/footer.php');
?>