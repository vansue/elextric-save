<?php
	ob_start();
	$title = "Thông tin người dùng | Elextronic";
	include('inc/header.php');
	require_once('inc/functions.php');
	require_once('inc/mysqli_connect.php');
	include('inc/first-sidebar.php');
	//Kiểm tra xem người dùng đã login chưa?
	is_logged_in();
?>

<?php
    if($_SERVER['REQUEST_METHOD'] == 'POST') {
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

        // Check for address (not required)
        $add = (!empty($trimmed['address'])) ? $trimmed['address'] : NULL;

        // Check for phone (not required)
        $phone = (!empty($trimmed['phone'])) ? $trimmed['phone'] : NULL;

        // Check for website (not required)
        $web = (!empty($trimmed['website'])) ? $trimmed['website'] : NULL;

        // Check for yahoo (not required)
        $yahoo = (!empty($trimmed['yahoo'])) ? $trimmed['yahoo'] : NULL;

        // Check for website (not required)
        $bio = (!empty($trimmed['bio'])) ? $trimmed['bio'] : NULL;

        if(empty($errors)) {
            $q = "UPDATE users SET
                first_name = ?, last_name = ?, email = ?, address = ?, phone = ?, website = ?, yahoo = ?, bio = ?
                WHERE user_id = ?
                LIMIT 1";
            $stmt = mysqli_prepare($dbc, $q);
            mysqli_stmt_bind_param($stmt, 'ssssssssi', $fn, $ln, $e, $add, $phone, $web, $yahoo, $bio, $_SESSION['uid']);
            mysqli_stmt_execute($stmt) or die("MySQL Error: $q" . mysqli_stmt_error($stmt));

            if(mysqli_stmt_affected_rows($stmt) > 0) {
                // Update thành công
                $message = "<p class='notice success'>Thông tin của bạn được cập nhật thành công.</p>";
            } else {
                // Có lỗi hệ thống xảy ra
                $message = "<p class='notice'>Thông tin của bạn không thể cập nhật do lỗi hệ thống.</p>";
            }
        }

    }// END $_SERVER IF

?>

<div id="main-content">
    <div class="title-content">
        <p>Thông tin người dùng</p>
    </div>
<?php
    if(!empty($message)) echo $message;
    //Truy xuất CSDL để hiển thị thông tin người dùng
    $user = fetch_user($_SESSION['uid']);
?>
    <form enctype="multipart/form-data" action="processors/avatar.php" method="post" class="add-form">
    	<fieldset>
    		<legend>Ảnh đại diện</legend>
    		<div>
    			<img src="images/avatar/<?php echo (!isset($user['avatar']) ? "no_avatar.jpg" : $user['avatar']); ?>" alt="avatar" class="avatar">
    			<p>Chọn 1 ảnh JPEG hoặc PNG hoặc GIF có kích thước nhỏ hơn 512Kb để làm ảnh đại diện.</p>
    			<input type="hidden" name="MAX_FILE_SIZE" value="524288" />
    			<input type="file" name="image" id = "avatar" />
    			<p><input class="change" type="submit" name="upload" value="Lưu thay đổi" /></p>
    		</div>
    	</fieldset>
    </form>

    <form action="" method="post" class="add-form">
        <fieldset class="in-form">
            <legend>Họ tên</legend>
            <div>
                <label for="first-name">Họ: <span class="required">*</span>
                    <?php if(isset($errors) && in_array('first_name',$errors)) echo "<p class='notice'>Điền họ của bạn.</p>";?>
                </label>
                <input type="text" name="first_name" value="<?php if(isset($user['first_name'])) echo strip_tags($user['first_name']); ?>" size="20" maxlength="40" tabindex='1' />
            </div>

            <div>
                <label for="last-name">Tên: <span class="required">*</span>
                    <?php if(isset($errors) && in_array('last name',$errors)) echo "<p class='notice'>Điền tên của bạn.</p>";?>
                </label>
                <input type="text" name="last_name" value="<?php if(isset($user['last_name'])) echo strip_tags($user['last_name']); ?>" size="20" maxlength="40" tabindex='1' />
            </div>
        </fieldset>
        <fieldset class="in-form">
            <legend>Liên hệ</legend>
            <div>
                <label for="email">Email: <span class="required">*</span>
                <?php if(isset($errors) && in_array('email',$errors)) echo "<p class='notice'>Điền email hợp lệ.</p>";?>
                </label>
                <input type="text" name="email" value="<?php if(isset($user['email'])) echo $user['email']; ?>" size="20" maxlength="40" tabindex='3' />
            </div>

            <div>
                <label for="address">Địa chỉ:</label>
                <input type="text" name="address" value="<?php echo (is_null($user['address'])) ? '' : strip_tags($user['address']); ?>" size="20" maxlength="40" tabindex='4' />
            </div>

            <div>
                <label for="phone">Điện thoại:</label>
                <input type="text" name="phone" value="<?php echo (is_null($user['phone'])) ? '' : strip_tags($user['phone']); ?>" size="20" maxlength="40" tabindex='4' />
            </div>

            <div>
                <label for="website">Website:</label>
                <input type="text" name="website" value="<?php echo (is_null($user['website'])) ? '' : strip_tags($user['website']); ?>" size="20" maxlength="40" tabindex='4' />
            </div>

            <div>
                <label for="yahoo">Yahoo Messenger:</label>
                <input type="text" name="yahoo" value="<?php echo (is_null($user['yahoo'])) ? '' : strip_tags($user['yahoo']); ?>" size="20" maxlength="40" tabindex='5' />
            </div>
        </fieldset>
        <fieldset class="in-form">
            <legend>Giới thiệu</legend>
            <div>
                <textarea cols="50" rows="20" name="bio"><?php echo (is_null($user['bio'])) ? '' : htmlentities($user['bio'], ENT_COMPAT, 'UTF-8'); ?></textarea>
            </div>
        </fieldset>
    <div><input type="submit" name="submit" value="Lưu thay đổi" /></div>
    </form>
</div><!--end #main-content-->
<?php
	include('inc/second-sidebar.php');
	include('inc/footer.php');
?>