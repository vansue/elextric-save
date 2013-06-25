<?php
	ob_start();
	include('inc/header.php');
	require_once('inc/functions.php');
	require_once('inc/mysqli_connect.php');
	include('inc/first-sidebar.php');
?>
<div id="main-content">
    <div class="title-content">
        <p>Kích hoạt tài khoản</p>
    </div>
<?php
    if (isset($_GET['x'], $_GET['y']) && filter_var($_GET['x'], FILTER_VALIDATE_EMAIL) && strlen($_GET['y']) == 32) {
        //Nếu các thông tin hợp lệ thì truy vấn CSDL
        $e = mysqli_real_escape_string($dbc, $_GET['x']);
        $a = mysqli_real_escape_string($dbc, $_GET['y']);
        $q = "UPDATE users SET active = NULL WHERE email = '{$e}' AND active = '{$a}' LIMIT 1";
        $r = mysqli_query($dbc, $q);
            confirm_query($r, $q);
        if (mysqli_affected_rows($dbc) == 1) {
            echo "<p class='notice success'>Tài khoản của bạn đã được kích hoạt thành công. Bạn có thể <a href='".BASE_URL."login.php'>đăng nhập</a> bây giờ.</p>";
        } else {
            echo "<p class='notice'>Tài khoản của bạn không thể kích hoạt. Làm ơn kích hoạt lại sau.</p>";
        }
    } else {
        redirect_to('admin/index.php');
    }
?>
</div><!--end #main-content-->
<?php
	include('inc/second-sidebar.php');
	include('incfooter.php');
?>