<?php 
if ( have_posts() ) {
	while ( have_posts() ) {
		the_post(); ?> 
			<div class="slider__slide" style="background-image: url(<?php the_field('banner_image'); ?>)">
				<span class="callout">
					
					<?php if ( is_home() ) : ?>
					
						<a href="<?php the_permalink(); ?>">
							<p class="callout__title"><?php the_field('banner_top_row'); ?></p>
							<p class="callout__title callout__title--small"><?php the_field('banner_bottom_row'); ?></p>
						</a>
					
					<?php else : ?>
					
						<p class="callout__title callout__title--shadow"><?php the_field('banner_top_row'); ?></p>
						<p class="callout__title callout__title--small callout__title--shadow"><?php the_field('banner_bottom_row'); ?></p>
					
					<?php endif; ?>
						
				</span> 
			</div>
	<?php }
} ?>