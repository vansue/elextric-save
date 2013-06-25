<!--===== CONTENT =====-->
<div id="content">
	<div id="first-sidebar">
		<div class="typical">
			<h3>Danh mục sản phẩm</h3>
			<ul>
				<?php
					$q = "SELECT cat_id, cat_name FROM p_categories";
					$r = mysqli_query($dbc, $q);
						confirm_query($r, $q);
						$i = 0;
					while($pcats = mysqli_fetch_array($r, MYSQLI_ASSOC)) {
						$i++;
						if ($i%2 == 0) {
							echo "<li><a href='products.php?pcid=".$pcats['cat_id']."' class='old'>".$pcats['cat_name']."</a></li>";
						} else {
							echo "<li><a href='products.php?pcid=".$pcats['cat_id']."' class='even'>".$pcats['cat_name']."</a></li>";
						}
					}
				?>
			</ul>
		</div>

		<div class="typical">
			<h3><a href="#">Sản phẩm nổi bật</a></h3>
			<div class="box">
				<h4><a href="#">Motorola 156 MK-VL</a></h4>
				<a href="#"><img src="images/laptop.png" alt=""></a>
				<p class="price"><span>350$</span> 270$</p>
			</div>
		</div>

		<div class="typical">
			<h3>Newsletter</h3>
			<div class="box">
				<form>
					<input type="text" name="txtnewsletter" id="txtnewsletter" value="your email" />
					<a href="#">Đăng ký</a>
				</form>
			</div>
		</div>

		<div class="banner-adds">
			<a href="#"><img src="images/bann1.jpg" alt="" /></a>
		</div>
	</div><!--end #first-sidebar-->