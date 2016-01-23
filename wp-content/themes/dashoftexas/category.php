<?php get_header(); ?>

	<!-- BEGIN ON CANVAS CONTAINER -->
	<div class="wrapper">

		<!-- BEGIN ON CANVAS CONTAINER -->
		<div class="on-canvas">      
			
			<div class="main-content main-content--categories">
				<div class="container">
					<section class="content">		
						
						<h1 class="page-title"><?php single_cat_title(); ?></h1>			
						
						<ul class="categories">

							<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
							
								<li class="category">
									<a class="category__postlink" href="<?php the_permalink() ?>"><?php the_title(); ?></a>
									<div class="block" style="background-image: url(<?php the_field('category_page_image'); ?>);">
										<a href="<?php the_permalink() ?>"></a>
									</div>
									<p class="categories categories__tag">
									<?php the_category( ', ' ); ?>
									</p>
								</li>
								
							<?php endwhile; ?>
						
						</ul>
						
                        <p class="pagination"><?php posts_nav_link(); ?></p>
                        
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