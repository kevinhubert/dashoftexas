<p>Copyright Dash of Texas</p>

<!-- SCRIPTS -->
<script src="<?php site_url(); ?>/wp-content/themes/dashoftexas/js/owl.carousel.min.js"></script>

<!-- Google Ads -->
<script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
<script>
  (adsbygoogle = window.adsbygoogle || []).push({
    google_ad_client: "ca-pub-2693491020856121",
    enable_page_level_ads: true
  });
</script>

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
		
		$(".post__header").first().css("border-top", 0);
		
		$(".owl-carousel").owlCarousel( {
			navigation : false, // Show next and prev buttons
			slideSpeed : 300,
			paginationSpeed : 400,
			singleItem:true,
			autoPlay: true,
			rewindNav: true
		});
			
	});
</script>
<?php wp_footer(); ?>
