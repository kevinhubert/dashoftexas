<?php if (have_posts()) : while (have_posts()) : the_post(); ?>

	<?php if ( is_home() ) : ?>
		<article class="post">
	
			<a href="<?php the_permalink(); ?>" class="post-title"><?php the_title(); ?></a>
			<p class="post-date"><?php the_time( 'F jS, Y') ?></p>
			
			<?php the_content(); ?>
	
		</article>
	
	<?php else : ?>
	

		<article class="post">
	
			<h1 class="post-title"><?php the_title(); ?></h1>
			<p class="post-date"><?php the_time( 'F jS, Y') ?></p>
			
			<?php the_content(); ?>
	
		</article>

	<?php endif; ?>

<?php endwhile; ?>
<?php endif; ?>