<?php get_header(); ?>

	<!-- BEGIN ON CANVAS CONTAINER -->
	<div class="wrapper">

		<!-- BEGIN ON CANVAS CONTAINER -->
		<div class="on-canvas">      

			<!-- BEGIN SLIDER  -->
			<section class="slider owl-carousel">
				<?php include (TEMPLATEPATH . '/slider.php'); ?>
			</section>
			<!-- END SLIDER -->
			
			<div class="main-content">
				<div class="container">
					<section class="content">
						
						<?php if ( have_posts() ) : ?>
							<?php
								get_template_part( 'content' );
							endif;
							?>
								
					</section>
					
					<!-- BEGIN MAIN SIDEBAR -->
					<aside class="sidebar">
						<?php get_sidebar(); ?>
					</aside>
					<!-- END MAIN SIDEBAR -->
					
					<!-- BEGIN MAIN FOOTER -->
					<footer class="main-footer">
						<?php get_footer(); ?>
					</footer>
					<!-- END MAIN FOOTER -->
					
				</div>
			</div>
		</div>
		<!-- END ON CANVAS CONTAINER-->
		
		<!-- BEING OFF CANVAS NAVIGATION -->
		<aside class="off-canvas">
			<?php include (TEMPLATEPATH . '/offcanvas.php'); ?>
		</aside>
		<!-- END OFF CANVAS NAVIGATION -->
		
	</div>
	<!-- END WRAPPER -->
</body>
</html>