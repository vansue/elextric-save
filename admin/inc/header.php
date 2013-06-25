<?php
	session_start();
	ob_start();
	if (!isset($_SESSION['user_level']) or $_SESSION['user_level'] != 2) {
		header("Location: http://localhost:8080/elextric/admin/login.php");
		exit();
	}
?>
<!DOCTYPE html>
<html>

<head>
	<meta charset='UTF-8' />

	<title><?php echo (isset($title)) ? $title : "Administration Site | Elextronic"; ?></title>

	<link rel="stylesheet" type="text/css" href="../css/style.css" />

	<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
	<script type="text/javascript" src='../js/tooltip.js'></script>
	<script type="text/javascript" src='../js/clock.js'></script>
	<script type="text/javascript" src="../js/tinymce/tiny_mce.js"></script>
	<script type="text/javascript">
	tinyMCE.init({
        mode : "textareas",
        theme : "advanced",
        plugins : "emotions,spellchecker,advhr,insertdatetime,preview",
        // Theme options - button# indicated the row# only
        theme_advanced_buttons1 : "newdocument,|,bold,italic,underline,|,justifyleft,justifycenter,justifyright,fontselect,fontsizeselect,formatselect",
        theme_advanced_buttons2 : "cut,copy,paste,|,bullist,numlist,|,outdent,indent,|,undo,redo,|,link,unlink,anchor,image,|,code,preview,|,forecolor,backcolor",
        theme_advanced_buttons3 : "insertdate,inserttime,|,spellchecker,advhr,,removeformat,|,sub,sup,|,charmap,emotions",
        theme_advanced_toolbar_location : "top",
        theme_advanced_toolbar_align : "left",
        theme_advanced_statusbar_location : "bottom",
        theme_advanced_resizing : true
        });
	</script>

</head>

<body class="admin" onload="showTimeSec();">
	<div id="wrapper">
		<!--===== TOP =====-->
		<div id="top">
			<p><a href="index.php">Administration Site</a></p>
			<div id="language">
				<h3>Languages: </h3>
				<a href="#"><img src="../images/vi.gif" alt="VI" /></a>
				<a href="#"><img src="../images/en.png" alt="EN" /></a>
			</div>

			<div id="search">
				<a href="#">Advanced Search</a>
				<form action="search.html" method="post" name="fsearch" id="fsearch">
					<input type="text" name="txtSearch" id="txtSearch" />
					<input type="image" src="../images/search.gif" name="imgSearch" id="imgSearch" alt="imgSearch" />
				</form>
			</div>
		</div><!--end #top-->

		<!--===== HEADER =====-->
		<div id="header">
			<h1 id="logo"><a href="../index.php">Electronix</a></h1>
			<div id="slider">
				<img src="../images/slide_divider.png" alt="" class="slide-divider" />
				<div id="slide-content">
					<img src="../images/laptop.png" alt="" />

					<div id="slide-detail">
						<h3><a href="#">Samsung GX 2004 LM</a></h3>
						<p>
							Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do iusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim eniam, quis nostrud exercitation ullamco
						</p>
						<a href="#" id="detail">Xem tiếp</a>
					</div>

					<div class="group"></div>
					<div id="pagination">
						<a href="#" class="active">1</a>
						<a href="#">2</a>
						<a href="#">3</a>
						<a href="#">4</a>
						<a href="#">5</a>
					</div>
				</div><!--slide-content-->
				<img src="../images/slide_divider.png" alt="" class="slide-divider" />
			</div><!--end #slider-->
		</div><!--end #header-->

		<!--===== NAV MENU =====-->
		<div id="nav-menu" class="group">
			<img src="../images/menu_left.gif" alt="left-menu" />
			<ul>
				<li><a href="index.php" id="nav-home">Admin CP</a></li>
				<li><img src="../images/menu_divider.gif" alt="menu-divider" /></li>
				<li><a href="p-categories.php" id="nav-pro-cat">Danh mục sản phẩm</a></li>
				<li><img src="../images/menu_divider.gif" alt="menu-divider" /></li>
				<li><a href="products.php" id="nav-a-pro">Sản phẩm</a></li>
				<li><img src="../images/menu_divider.gif" alt="menu-divider" /></li>
				<li><a href="#" id="nav-ord">Đơn hàng</a></li>
				<li><img src="../images/menu_divider.gif" alt="menu-divider" /></li>
				<li><a href="n-categories.php" id="nav-new-cat">Danh mục bài viết</a></li>
				<li><img src="../images/menu_divider.gif" alt="menu-divider" /></li>
				<li><a href="news.php" id="nav-new">Bài viết</a></li>
				<li><img src="../images/menu_divider.gif" alt="menu-divider" /></li>
				<li><a href="users.php" id="nav-user">Thành viên</a></li>
				<li><img src="../images/menu_divider.gif" alt="menu-divider" /></li>
				<li><a href="contact.html" id="nav-comm">Bình luận</a></li>
			</ul>
			<img src="../images/menu_right.gif" alt="right-menu" />
		</div><!--end #nav-menu-->

		<!--===== NAVIGATION =====-->
		<div id="navigation">
			Navigation: <a href="#" class="current">Home</a>
		</div>