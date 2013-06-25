<?php
	$title = "Quên mật khẩu | Elextronic";
	include('inc/header.php');
	require_once('inc/functions.php');
	require_once('inc/mysqli_connect.php');
	include('inc/first-sidebar.php');
?>

<?php
	if ($_SERVER['REQUEST_METHOD'] == 'POST') {
		//Bắt đầu xử lý form. Tạo biến $errors
		//Validate email
		if (isset($_POST['email']) && filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
			$e = mysqli_real_escape_string($dbc, $_POST['email']);

			//Kiểm tra CSDL xem email có tồn tại không
			$q = "SELECT user_id, first_name, last_name FROM users WHERE email = '{$e}'";
			$r = mysqli_query($dbc, $q);
				confirm_query($r, $q);
			if(mysqli_num_rows($r) == 1) {
				//Tìm thấy email trong CSDL
				list($uid, $fn, $ln) = mysqli_fetch_array($r, MYSQLI_NUM);

				if(isset($uid) && isset($fn) && isset($ln)) {
					//Nếu có $uid, thì chuẩn bị update lại mật khẩu người dùng
					$temp_pass = substr(md5(uniqid(rand(), true)), 3, 10);

					//Update CSDL với mật khẩu tạm thời
					$q = "UPDATE users SET pass = SHA1('$temp_pass') WHERE user_id = {$uid} LIMIT 1";
					$r = mysqli_query($dbc, $q);
						confirm_query($r, $q);
					if (mysqli_affected_rows($dbc) == 1) {
						//Nếu Update thành công thì email đến người dùng mật khẩu tạm thời
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
						$mail->Subject = "Mật khẩu tạm thời của bạn"; //Tiêu đề của thư
						$mail->CharSet = "utf-8";
						$mail->MsgHTML("Mật khẩu của bạn đã được tạm thời thay đổi thành: {$temp_pass} Sử dụng địa chỉ email và mật khẩu mới này để đăng nhập. Sau đó hãy đổi lại mật khẩu của bạn."); //Nội dung của bức thư.
						// $mail->MsgHTML(file_get_contents("email-template.html"), dirname(__FILE__));
						// Gửi thư với tập tin html
						$mail->AltBody = "This is a plain-text message body";//Nội dung rút gọn hiển thị bên ngoài thư mục thư.
						//$mail->AddAttachment("images/attact-tui.gif");//Tập tin cần attach

						//Tiến hành gửi email và kiểm tra lỗi
						if(!$mail->Send()) {
						  	$mesage = "<p class='notice'>Có lỗi khi gửi mail: ".$mail->ErrorInfo."</p>";
						} else {
						  	$mesage = "<p class='notice success'>Mật khẩu mới đã được gửi vào email của bạn. Sử dụng mật khẩu mới này để đăng nhập.</p>";
						}
					} else {
						$mesage = "<p class='notice'>Không đổi được mật khẩu do lỗi hệ thống.</p>";
					}
				} else {
					$mesage = "<p class='notice'>Không tìm thấy email trong CSDL.</p>";
				}
			} else {
				$mesage = "<p class='notice'>Không kiểm tra được email do lỗi hệ thống hoặc Không tìm thấy email trong CSDL.</p>";
			}
		} else {
			$mesage = "<p class='notice'>Điền email của bạn.</p>";
		}
	}
?>

<div id="main-content">
    <div class="title-content">
        <p>Quên mật khẩu</p>
    </div>

    <div>
		<?php if(!empty($mesage)) echo $mesage; ?>
		<form action="" method="POST" id="add-n-cat" class="add-form">
			<fieldset>
				<legend>Quên mật khẩu</legend>
				<label for="email">Email: <span class="required">*</span></label>
				<input type="text" name="email" id="email" value="<?php if(isset($_POST['email'])) {echo htmlentities($_POST['email']); } ?>" size="20" maxlength="100" tabindex="1" />

				<p><input type="submit" name="submit" value="Lấy lại mật khẩu" /></p>

			</fieldset>
		</form>
	</div>

</div><!--end #main-content-->
<?php
	include('inc/second-sidebar.php');
	include('inc/footer.php');
?>