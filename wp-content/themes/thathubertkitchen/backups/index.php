<?php get_header(); ?>
  <body> 
    <div class="row">
      <div class="large-8 columns" role="content">
        <article>

          <!-- Start the Loop. -->

            <?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>

          <div class="row">
            <div class="large-8 columns">
            <a href="<?php the_permalink(); ?>"><h3><?php the_title(); ?></a></h3>
            </div>
            <div class="large-4 columns text-right">
              <?php the_time('F jS, Y') ?> 
            </div>
          </div>
          <div class="row">
            <div class="large-12 columns">
               <!-- Display the Post's content in a div box. -->
               <div class="entry">
                 <?php the_content(); ?>
               </div>
              <hr>

               <!-- Stop The Loop (but note the "else:" - see next line). -->

               <?php endwhile; ?>

               <!-- REALLY stop The Loop. -->

               <?php endif; ?>
   
          </article>

      </div>
      <aside class="large-4 columns">
        <?php get_sidebar(); ?>
      </aside> 
    </div>
  <?php get_footer(); ?>
