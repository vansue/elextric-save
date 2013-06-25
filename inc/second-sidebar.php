	<div id="second-sidebar">
		<?php
			if(isset($_SESSION['last_name'])) :
		?>
		<div id="shopping-cart" class="group">
			<h2><a href="#">Xin chào <?php echo $_SESSION['last_name']; ?> </a></h2>
			<div id="cart-details">
				<p>3 sản phẩm</p>
				<div id="border-cart"></div>
				<p>Tổng cộng: <span>350$</span></p>
			</div>
			<a href="#" data-tooltip="Checkout" class="tool"><img src="images/shoppingcart.png" alt="Checkout" /></a>
		</div>

		<?php endif; ?>

		<div class="typical">
			<h3><a href="#">Sản phẩm mới</a></h3>
			<div class="box">
				<h4><a href="#">Motorola 156 MK-VL</a></h4>
				<a href="#"><img src="images/p2.gif" alt=""></a>
				<p class="price"><span>350$</span> 270$</p>
			</div>
		</div>

		<div class='typical'>
			<h3><a href="#">Electronix's News</a></h3>
			<div class="box" id="boxnews">
				<div>
					<p class='date'>August 20, 2012</p>
					<h4><a href='#' class="newstitle">Lorem ipsum dolor sit amet</a></h4>
					<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Virtutes timidiores. Lorem ipsum dolor sit amet, consectetur adipisicing elit.</p>
				</div>

				<div class="news">
					<p class='date'>August 20, 2012</p>
					<h4><a href='#' class="newstitle">Lorem ipsum dolor sit amet</a></h4>
					<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Virtutes timidiores. Lorem ipsum dolor sit amet, consectetur adipisicing elit.</p>
				</div>

				<div class="news">
					<p class='date'>August 20, 2012</p>
					<h4><a href='#' class="newstitle">Lorem ipsum dolor sit amet</a></h4>
					<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Virtutes timidiores. Lorem ipsum dolor sit amet, consectetur adipisicing elit.</p>
				</div>
			</div>
		</div>

		<div class="banner-adds">
			<a href="#"><img src="images/bann2.jpg" alt=""></a>
		</div>
	</div><!--end #second-sidebar-->
</div><!--end #content-->