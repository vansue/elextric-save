		<!--===== FOOTER =====-->
		<div id="footer" class="group">
			<div id="left-footer">
				<a href="index.php">Electronix</a>
			</div>

			<div id="center-footer">
				<p>Electronix Company. All Rights Reserved 2013</p>
				<a href="#"><img src="images/csscreme.jpg" alt=""></a><br />
				<a href="#"><img src="images/payment.gif" alt=""></a>
			</div>

			<div id="right-footer">
				<ul>
					<?php
					if (isset($_SESSION['user_level'])) {
						//Nếu có SESSION
						switch ($_SESSION['user_level']) {
							case 0://Khách hàng
								echo "
									<li><a href='edit-profile.php'>Thông tin người dùng</a></li>
									<li><a href='change-pass.php'>Đổi mật khẩu</a></li>
									<li><a href='logout.php'>Đăng xuất</a></li>
								";
								break;

							case 2://Admin
								echo "
									<li><a href='edit-profile.php'>Thông tin người dùng</a></li>
									<li><a href='change-pass.php'>Đổi mật khẩu</a></li>
									<li><a href='admin/index.php' target='bank'>Admin CP</a></li>
									<li><a href='logout.php'>Đăng xuất</a></li>
								";
								break;

							default:
								echo "
									<li><a href='register.php'>Đăng ký</a></li>
									<li><a href='login.php'>Đăng nhập</a></li>
								";
								break;
						}
					} else {
						//Nếu không có SESSION
						echo "
							<li><a href='index.php'>Trang chủ</a></li>
							<li><a href='index.php'>Giới thiệu</a></li>
							<li><a href='register.php'>Đăng ký</a></li>
							<li><a href='login.php'>Đăng nhập</a></li>
						";
					}

					?>
				</ul>
			</div>
		</div><!--end #footer-->
	</div><!--end #wrapper-->
</body>

</html>