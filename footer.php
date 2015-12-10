<p>Copyright Dash of Texas</p>

<!-- SCRIPTS -->
<script src="<?php site_url(); ?>/wp-content/themes/dashoftexas/js/owl.carousel.min.js"></script>


<!-- REPLACE IN SCRIPT FILE BEFORE GO LIVE -->
<script type="text/javascript">

	jQuery(document).ready(function($) {
		
		var sliderHeight = $(".slider").height();
		$(".slider").css("height", sliderHeight);
		$(".main-content").css("top", sliderHeight);
		
		$(".menu").on("click" , function(){
			$(".off-canvas").toggleClass("off-canvas--open");
			$(".on-canvas").toggleClass("on-canvas--open");
			$(this).toggleClass("menu--open");
		});
		
		$(".owl-carousel").owlCarousel( {
			navigation : false, // Show next and prev buttons
			slideSpeed : 300,
			paginationSpeed : 400,
			singleItem:true,
			autoPlay: true
		});
			
	});
</script>
<?php wp_footer(); ?>
