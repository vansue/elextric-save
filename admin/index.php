<?php
	include('inc/header.php');
	require_once('../inc/functions.php');
	require_once('../inc/mysqli_connect.php');
	include('inc/first-sidebar.php');
?>

	<div id="main-content">
		<div class="title-content">
			<p>Control Panel</p>
		</div>

		<div class="product-box">
			<img src="../images/product_box_top.gif" alt="" class="top-prod-box" />
			<div class="cen-prod-box">
				<a href="p-categories.php"><img src="../images/ab_pro_cat.png" alt=""></a>
				<h4><a href="p-categories.php">Danh mục sản phẩm</a></h4>
			</div>
			<img src="../images/product_box_bottom.gif" alt="" class="bot-prod-box" />
		</div><!--end .product-box-->

		<div class="product-box">
			<img src="../images/product_box_top.gif" alt="" class="top-prod-box" />
			<div class="cen-prod-box">
				<a href="products.php"><img src="../images/ab_pro.png" alt=""></a>
				<h4><a href="products.php">Sản phẩm</a></h4>
			</div>
			<img src="../images/product_box_bottom.gif" alt="" class="bot-prod-box" />
		</div><!--end .product-box-->

		<div class="product-box">
			<img src="../images/product_box_top.gif" alt="" class="top-prod-box" />
			<div class="cen-prod-box">
				<a href="#"><img src="../images/ab_ord.png" alt=""></a>
				<h4><a href="#">Quản lý đơn hàng</a></h4>
			</div>
			<img src="../images/product_box_bottom.gif" alt="" class="bot-prod-box" />
		</div><!--end .product-box-->
		<div class="product-box">
			<img src="../images/product_box_top.gif" alt="" class="top-prod-box" />
			<div class="cen-prod-box">
				<a href="n-categories.php"><img src="../images/ab_news_cat.png" alt=""></a>
				<h4><a href="n-categories.php">Danh mục bài viết</a></h4>
			</div>
			<img src="../images/product_box_bottom.gif" alt="" class="bot-prod-box" />
		</div><!--end .product-box-->

		<div class="product-box">
			<img src="../images/product_box_top.gif" alt="" class="top-prod-box" />
			<div class="cen-prod-box">
				<a href="news.php"><img src="../images/ab_news.png" alt=""></a>
				<h4><a href="news.php">Bài viết</a></h4>
			</div>
			<img src="../images/product_box_bottom.gif" alt="" class="bot-prod-box" />
		</div><!--end .product-box-->

		<div class="product-box">
			<img src="../images/product_box_top.gif" alt="" class="top-prod-box" />
			<div class="cen-prod-box">
				<a href="users.php"><img src="../images/ab_user.png" alt=""></a>
				<h4><a href="users.php">Quản lý thành viên</a></h4>
			</div>
			<img src="../images/product_box_bottom.gif" alt="" class="bot-prod-box" />
		</div><!--end .product-box-->

		<div class="product-box">
			<img src="../images/product_box_top.gif" alt="" class="top-prod-box" />
			<div class="cen-prod-box">
				<a href="#"><img src="../images/ab_comm.png" alt=""></a>
				<h4><a href="#">Quản lý bình luận</a></h4>
			</div>
			<img src="../images/product_box_bottom.gif" alt="" class="bot-prod-box" />
		</div><!--end .product-box-->

	</div><!--end #main-content-->
<?php
	include('inc/second-sidebar.php');
	include('inc/footer.php');
?>