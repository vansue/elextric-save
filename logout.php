<?php
	ob_start();
	include('inc/header.php');
	require_once('inc/functions.php');
	require_once('inc/mysqli_connect.php');
	include('inc/first-sidebar.php');
?>

<?php
	if (!isset($_SESSION['user_level'])) :
		//Nếu người dùng chưa đăng nhập và không có thông tin trong hệ thống
		redirect_to();
	elseif ($_SESSION['user_level'] == 2):
		//Nếu có thông tin người dùng và đã đăng nhập sẽ logout người dùng
		$_SESSION = array(); //Xóa hết mọi SESSION
		session_destroy(); // Destroy session đã tạo
		setcookie(session_name(),'', time()-36000);//Xóa cookie của trình duyệt
		redirect_to('admin/login.php');
	else :
		//Nếu có thông tin người dùng và đã đăng nhập sẽ logout người dùng
		$_SESSION = array(); //Xóa hết mọi SESSION
		session_destroy(); // Destroy session đã tạo
		setcookie(session_name(),'', time()-36000);//Xóa cookie của trình duyệt

?>

<div id="main-content">
    <div class="title-content">
        <p>Đăng xuất</p>
    </div>
    <p class='notice success'>Bạn đã đăng xuất thành công. Trở về <a href="index.php">trang chủ</a></p>
</div><!--end #main-content-->
<?php endif; ?>
<?php
	include('inc/second-sidebar.php');
	include('inc/footer.php');
?>