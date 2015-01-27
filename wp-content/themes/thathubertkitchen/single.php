<?php get_header(); ?>
  <body> 
    <div class="row">
      <div class="large-8 columns" role="content">
        <article>
                       <!-- Start the Loop. -->
               <?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
          <div class="row">
            <div class="large-8 columns">
              <h3><?php the_title(); ?></h3>
            </div>
            <div class="large-4 columns text-right">
              <?php the_time('F jS, Y') ?> 
            </div>
          </div>
          <div class="row">
            <div class="large-12 columns">

               <!-- Test if the current post is in category 3. -->
               <!-- If it is, the div box is given the CSS class "post-cat-three". -->
               <!-- Otherwise, the div box is given the CSS class "post". -->

               <?php if ( in_category('3') ): ?>
                         <div class="post-cat-three">
               <?php else: ?>
                         <div class="post">
               <?php endif; ?>

               <!-- Display the Post's content in a div box. -->

               <div class="entry">
                 <?php the_content(); ?>
               </div>
<a href="//www.pinterest.com/pin/create/button/" data-pin-do="buttonBookmark" ><img src="//assets.pinterest.com/images/pidgets/pinit_fg_en_rect_gray_20.png" /></a>
<!-- Please call pinit.js only once per page -->
<script type="text/javascript" async src="//assets.pinterest.com/js/pinit.js"></script>
              <?php comments_template( $file, $separate_comments ); ?>

               <!-- Stop The Loop (but note the "else:" - see next line). -->

               <?php endwhile; else: ?>

               <!-- The very first "if" tested to see if there were any Posts to -->
               <!-- display.  This "else" part tells what do if there weren't any. -->
               <p>Sorry, no posts matched your criteria.</p>


               <!-- REALLY stop The Loop. -->
               <?php endif; ?>
   
          </article>
   
        <hr/>

      </div>
      <aside class="large-4 columns">
        <?php get_sidebar(); ?>
      </aside> 
    </div>
