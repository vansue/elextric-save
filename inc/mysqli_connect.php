<?php
	//Kết nối CSDL (server, user, pass, database)
	$dbc = mysqli_connect('localhost', 'root', '', 'elextric');

	//Kiểm tra xem kết nối có thành công không. Nếu không thành công báo lỗi ra trình duyệt và dừng thực thi phần lệnh tiếp theo
	if (!$dbc) {
		die("Khong the ket noi den CSDL: " . mysqli_connect_error());
	} else {
		//Nếu kết nối thành công. Đặt phương thức kết nối là UTF-8
		mysqli_set_charset($dbc, 'utf-8');
	}
