<?php if (have_posts()) : while (have_posts()) : the_post(); ?>

	<?php if ( is_home() ) : ?>
		<article class="post">
			<div class="post__header">
				<a href="<?php the_permalink(); ?>" class="post-title"><?php the_title(); ?></a>
				<p class="post-date"><?php the_time( 'F jS, Y') ?></p>
			</div>
			
			<?php the_content(); ?>

			<p class="categories">
				<?php the_category( ' , ' ); ?>
			</p>
	
		</article>
	
	<?php else : ?>

		<article class="post">
			<div class="post__header">
				<h1 class="post-title"><?php the_title(); ?></h1>
				<p class="post-date"><?php the_time( 'F jS, Y') ?></p>
			</div>
			
			<?php the_content(); ?>

			<p class="categories categories__tag">
				<?php the_category( ' , ' ); ?>
			</p>
			
			<?php if ( comments_open() || get_comments_number() ) :
				comments_template();
			endif; ?>
			
		</article>

	<?php endif; ?>

<?php endwhile; ?>
<?php endif; ?>