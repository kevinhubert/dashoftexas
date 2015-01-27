<?php 
/*
Template Name: Archives
*/
get_header(); ?>
  <body> 
    <div class="row">
      <div class="large-8 columns" role="content">
        <article>
          <div id="container">
            <div id="content" role="main">
            	 <p><?php single_cat_title(); ?></p>

			<div class="row">
				<?php 
				$args = array ( 'category' => '2,3' , 'posts_per_page' => 20);
				$myposts = get_posts( $args );
				foreach( $myposts as $post ) :	setup_postdata($post);
				 ?>
			    <?php the_post(); ?>
					<div class="large-4 columns singleRecipe">
						<a href="<?php the_permalink(); ?>">
							<?php the_excerpt_rss(); ?>
						<div class="recipeMask"></div>
						<div class="recipeLabel"><?php the_title(); ?></div></a>
					</div>
				<?php endforeach; ?>
			</div>

            </div><!-- #content -->
          </div><!-- #container --> 
        </article>
      </div>
      <aside class="large-4 columns">
        <?php get_sidebar(); ?>
      </aside> 
    </div>
  <?php get_footer(); ?>