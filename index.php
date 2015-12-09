
		<div class="wrapper">  <!-- WRAPPER -->

			
			<div class="on-canvas">  <!-- BEGIN ON CANVAS CONTAINER -->     
	
			
				<section class="slider owl-carousel"> <!-- BEGIN SLIDER  -->
					<div class="slider__slide">
						<span class="callout">
							<p class="callout__title">Maple Oat</p>
							<p class="callout__title callout__title--small">Pecan Cookies</p>
						</span> 
					</div>
					<div class="slider__slide">
						<span class="callout">
							<p class="callout__title">Maple Oat</p>
							<p class="callout__title callout__title--small">Pecan Cookies</p>
						</span> 
					</div>
					<div class="slider__slide">
						<span class="callout">
							<p class="callout__title">Maple Oat</p>
							<p class="callout__title callout__title--small">Pecan Cookies</p>
						</span> 
					</div>
				</section> <!-- END SLIDER -->
				
				
				<div class="main-content">
					<div class="container">
						<section class="content">
							<artice class="post">
								<a href="" class="post-title">Maple Oat Pecan Cookies</a>
								<p class="post-date">October 30th, 2015</p>
								<img src="http://www.dashoftexas.com/wp-content/uploads/2015/10/MapleOatPecanTopPic.jpg" alt="">
								<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Esse accusantium, omnis quia cumque reiciendis inventore distinctio impedit, architecto cupiditate beatae earum molestias non aliquid ut. Unde quibusdam laboriosam amet magni?</p>
								<p>  Lorem ipsum dolor sit amet, consectetur adipisicing elit. Nobis illum suscipit facilis ex illo expedita veniam fugiat a natus animi est, neque temporibus totam reiciendis mollitia beatae reprehenderit ratione pariatur.Lorem ipsum dolor sit amet, consectetur adipisicing elit. Fugiat harum officia, hic debitis libero, numquam sunt optio molestias quam doloribus doloremque officiis aut quod et ratione, inventore culpa architecto animi.Lorem ipsum dolor sit amet, consectetur adipisicing elit. Qui culpa cumque illum voluptate numquam consequatur accusantium soluta necessitatibus fuga tenetur architecto, porro, error ratione sequi nemo sunt ex, officiis neque.</p>
								<img src="http://www.dashoftexas.com/wp-content/uploads/2015/10/IMG_8015-683x1024.jpg" alt="">
								<p> Lorem ipsum dolor sit amet, consectetur adipisicing elit. Sunt laboriosam ad dolorum omnis illum beatae nulla maxime suscipit doloribus consectetur architecto, est cumque voluptatem velit, dignissimos necessitatibus, sit nostrum quod.Lorem ipsum dolor sit amet, consectetur adipisicing elit. Numquam consequatur voluptatum distinctio suscipit quos provident culpa velit odio consectetur doloribus aliquid iure, dolore sapiente sit tenetur officiis est necessitatibus sed.</p>
								<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Laborum suscipit beatae perferendis sapiente facere deserunt inventore quia doloribus, officia magnam reprehenderit veniam voluptas quisquam et incidunt dignissimos, magni architecto esse.</p>
								<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Laborum suscipit beatae perferendis sapiente facere deserunt inventore quia doloribus, officia magnam reprehenderit veniam voluptas quisquam et incidunt dignissimos, magni architecto esse.</p>
								
								<a href="" class="continue">READ MORE</a>
							</artice>
						</section>
						<aside class="sidebar">
							
							
							<p class="sidebar-title--red">Hi, I'm Becca!</p>
							<div class="bio">
								<img src="http://www.dashoftexas.com/wp-content/themes/dashoftexas/img/becca.jpg" alt="">
								<p class="bio__description">
									I’m a 20-something wife, dog-mother, and Star Wars enthusiast. Here, you’ll find recipes that are inspired by our Texas upbringing and Austin kitchen. And maybe a few pictures of dogs, too.
									
									<a href="" class="bio__more">Read More</a>
								</p>
							</div>
							
							<p class="sidebar-title--lightblue">Instagram</p>
							<div class="instagram">
								<script src="http://snapwidget.com/js/snapwidget.js"></script>
<iframe src="http://snapwidget.com/in/?u=ZGFzaG9mdGV4YXN8aW58MjAwfDF8Nnx8bm98MTB8ZmFkZU91dHxvblN0YXJ0fHllc3x5ZXM=&ve=021215" title="Instagram Widget" class="snapwidget-widget" allowTransparency="true" frameborder="0" scrolling="no" style="border:none; overflow:hidden; width:100%;"></iframe>
							</div>
	
							<img class="badge" src="img/badge.png" alt="">
							
						</aside>
						
						
					<footer class="main-footer"> <!-- BEGIN MAIN FOOTER -->
						<p>Copyright Dash of Texas</p>
					</footer>	<!-- END MAIN FOOTER -->
						
					</div>
					
					
				</div>
			
				
			</div>	<!-- END ON CANVAS CONTAINER-->
			
	
			<aside class="off-canvas">	<!-- BEING OFF CANVAS NAVIGATION -->
				
				<p class="offcanvas-title">Dash of <strong>Texas</strong></p>
				<input type="search" placeholder="SEARCH">
				<ul class="mainnav">
					<li><a href="">About</a></li>
					<li><a href="">Savories</a></li>
					<li><a href="">Sweets</a></li>
					<li><a href="">Contact</a></li>
				</ul>
				<footer class="footer">
					<svg class="icon icon-facebook2"><use xlink:href="assets/img/svg/symbol-defs.svg#icon-facebook2"></use></svg>
					<svg class="icon icon-twitter2"><use xlink:href="assets/img/svg/symbol-defs.svg#icon-twitter2"></use></svg>
					<svg class="icon icon-pinterest2"><use xlink:href="assets/img/svg/symbol-defs.svg#icon-pinterest2"></use></svg>
					<svg class="icon icon-instagram"><use xlink:href="assets/img/svg/symbol-defs.svg#icon-instagram"></use></svg>
				</footer>
			</aside>  <!-- END OFF CANVAS NAVIGATION -->
		
		</div> <!-- END WRAPPER -->
		

	<!-- SCRIPTS -->
	<script src="js/owl.carousel.min.js"></script>


	<!-- REPLACE IN SCRIPT FILE BEFORE GO LIVE -->
	<script type="text/javascript">
		
		$(function(){
			$(".menu").on("click" , function(){
				$(".off-canvas").toggleClass("off-canvas--open");
				$(".on-canvas").toggleClass("on-canvas--open");
				$(this).toggleClass("menu--open");
			});
				
		});
		
		$(document).ready(function() {
			$(".owl-carousel").owlCarousel( {
				navigation : false, // Show next and prev buttons
				slideSpeed : 300,
				paginationSpeed : 400,
				singleItem:true
			});
		});
		
	</script>

</body>
</html>