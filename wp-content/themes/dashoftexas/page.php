<?php get_header(); ?>

	<!-- BEGIN ON CANVAS CONTAINER -->
	<div class="wrapper">

		<!-- BEGIN ON CANVAS CONTAINER -->
		<div class="on-canvas">      
			
			<div class="main-content main-content--categories">
				<div class="container">
					<section class="content">		
						
						<h1 class="page-title"><?php the_title(); ?></h1>			
						
							<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
							
								<?php the_content(); ?>
								
							<?php endwhile; ?>
												
						<?php endif; ?>
								
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