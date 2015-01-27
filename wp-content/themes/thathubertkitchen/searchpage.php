<?php
/*
Template Name: Search Page
*/
?>

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
			<header class="page-header">
				<h1 class="page-title"><?php printf( __( 'Search Results for: %s', 'thathubertcouple' ), get_search_query() ); ?></h1>
			</header><!-- .page-header -->

				<?php
                    global $query_string;

                    $query_args = explode("&", $query_string);
                    $search_query = array();

                    foreach($query_args as $key => $string) {
                        $query_split = explode("=", $string);
                        $search_query[$query_split[0]] = urldecode($query_split[1]);
                    } // foreach

                    $search = new WP_Query($search_query);
                    ?>
      <aside class="large-4 columns">
        <?php get_sidebar(); ?>
      </aside> 
    </div>
